init: init-ci frontend-ready
init-ci: docker-down-clear \
	api-clear frontend-clear cucumber-clear \
	docker-pull docker-build docker-up \
	api-init frontend-init cucumber-init
up: docker-up
down: docker-down
restart: down up
check: api-check frontend-check cucumber-check test-e2e
test-smoke: api-fixtures cucumber-clear cucumber-smoke
test-e2e: api-fixtures cucumber-clear cucumber-e2e

update-deps: api-deps-update frontend-deps-update cucumber-deps-update restart

docker-up:
	docker compose up --detach

docker-down:
	docker compose down --remove-orphans --timeout=1

docker-down-clear:
	docker compose down --volumes --remove-orphans --timeout=1

docker-pull:
	docker compose pull

docker-build:
	docker compose build --pull

api-clear:
	docker run --rm --volume "${PWD}/api":/app --workdir /app alpine:3.23 sh -c 'rm -rf var/cache/* var/log/* var/test/*'

api-init: api-permissions api-deps-install api-wait-db api-migrations api-fixtures

api-permissions:
	docker run --rm --volume "${PWD}/api":/app --workdir /app alpine:3.23 chmod 777 var/cache var/log var/test

api-deps-install:
	docker compose run --rm api-php-cli composer install

api-deps-update:
	docker compose run --rm api-php-cli composer update

api-wait-db:
	docker compose run --rm api-php-cli wait-for-it api-postgres:5432 -t 30

api-migrations:
	docker compose run --rm api-php-cli composer app migrations:migrate -- --no-interaction

api-fixtures:
	docker compose run --rm api-php-cli composer app fixtures:load
	docker compose restart wiremock

api-backup:
	docker compose run --rm api-postgres-backup

api-check: api-validate-schema api-lint api-analyze api-test

api-validate-schema:
	docker compose run --rm api-php-cli composer app orm:validate-schema -- -v

api-lint:
	docker compose run --rm api-php-cli composer lint
	docker compose run --rm api-php-cli composer rector -- --dry-run
	docker compose run --rm api-php-cli composer php-cs-fixer fix -- --dry-run --diff

api-lint-fix:
	docker compose run --rm api-php-cli composer rector
	docker compose run --rm api-php-cli composer php-cs-fixer fix

api-analyze:
	docker compose run --rm api-php-cli composer psalm -- --no-diff

api-analyze-diff:
	docker compose run --rm api-php-cli composer psalm

api-test:
	docker compose run --rm api-php-cli composer test

api-test-coverage:
	docker compose run --rm api-php-cli composer test-coverage

api-test-unit:
	docker compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=unit

api-test-functional:
	docker compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-functional-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

frontend-clear:
	docker run --rm --volume "${PWD}/frontend":/app --workdir /app alpine:3.23 sh -c 'rm -rf .ready build'

frontend-init: frontend-deps-install

frontend-deps-install:
	docker compose run --rm frontend-node-cli yarn install

frontend-deps-update:
	docker compose run --rm frontend-node-cli yarn upgrade

frontend-ready:
	docker run --rm --volume "${PWD}/frontend":/app --workdir /app alpine:3.23 touch .ready

frontend-check: frontend-lint frontend-ts-check frontend-test

frontend-lint:
	docker compose run --rm frontend-node-cli yarn eslint
	docker compose run --rm frontend-node-cli yarn stylelint

frontend-lint-fix:
	docker compose run --rm frontend-node-cli yarn eslint-fix
	docker compose run --rm frontend-node-cli yarn stylelint-fix
	docker compose run --rm frontend-node-cli yarn prettier

frontend-ts-check:
	docker compose run --rm frontend-node-cli yarn ts-check

frontend-test:
	docker compose run --rm frontend-node-cli yarn test --watchAll=false

frontend-test-watch:
	docker compose run --rm frontend-node-cli yarn test

cucumber-clear:
	docker run --rm --volume "${PWD}/cucumber":/app --workdir /app alpine:3.23 sh -c 'rm -rf var/*'

cucumber-init: cucumber-deps-install

cucumber-deps-install:
	docker compose run --rm cucumber-node-cli yarn install

cucumber-deps-update:
	docker compose run --rm cucumber-node-cli yarn upgrade

cucumber-check: cucumber-lint cucumber-ts-check

cucumber-lint:
	docker compose run --rm cucumber-node-cli yarn lint

cucumber-lint-fix:
	docker compose run --rm cucumber-node-cli yarn lint-fix

cucumber-ts-check:
	docker compose run --rm cucumber-node-cli yarn ts-check

cucumber-smoke:
	docker compose run --rm cucumber-node-cli yarn smoke

cucumber-e2e:
	docker compose run --rm cucumber-node-cli yarn e2e

build: build-frontend build-api

build-frontend:
	docker --log-level=debug buildx build --pull --file=frontend/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-frontend:${IMAGE_TAG} frontend

build-api:
	docker --log-level=debug buildx build --pull --file=api/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-api:${IMAGE_TAG} api
	docker --log-level=debug buildx build --pull --file=api/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG} api
	docker --log-level=debug buildx build --pull --file=api/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/auction-api-php-cli:${IMAGE_TAG} api
	docker --log-level=debug buildx build --pull --file=api/docker/common/postgres-backup/Dockerfile --tag=${REGISTRY}/auction-api-postgres-backup:${IMAGE_TAG} api/docker/common

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

push: push-frontend push-api

push-frontend:
	docker push ${REGISTRY}/auction-frontend:${IMAGE_TAG}

push-api:
	docker push ${REGISTRY}/auction-api:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api-php-cli:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api-postgres-backup:${IMAGE_TAG}

testing-build: testing-build-testing-api-php-cli testing-build-cucumber

testing-build-testing-api-php-cli:
	docker --log-level=debug buildx build --pull --file=api/docker/testing/php-cli/Dockerfile --tag=${REGISTRY}/auction-testing-api-php-cli:${IMAGE_TAG} api

testing-build-cucumber:
	docker --log-level=debug buildx build --pull --file=cucumber/docker/testing/node/Dockerfile --tag=${REGISTRY}/auction-cucumber-node-cli:${IMAGE_TAG} cucumber

testing-init:
	docker compose -f compose-testing.yml up -d
	docker compose -f compose-testing.yml run --rm api-php-cli wait-for-it api-postgres:5432 -t 60
	docker compose -f compose-testing.yml run --rm api-php-cli php bin/app.php migrations:migrate --no-interaction
	docker compose -f compose-testing.yml run --rm testing-api-php-cli php bin/app.php fixtures:load --no-interaction
	sleep 15

testing-smoke:
	docker compose -f compose-testing.yml run --rm cucumber-node-cli yarn smoke-ci

testing-e2e:
	docker compose -f compose-testing.yml run --rm cucumber-node-cli yarn e2e-ci

testing-down-clear:
	docker compose -f compose-testing.yml down --volumes --remove-orphans

try-testing: try-build try-testing-build try-testing-init try-testing-smoke try-testing-e2e try-testing-down-clear

try-testing-build:
	REGISTRY=localhost IMAGE_TAG=0 make testing-build

try-testing-init:
	REGISTRY=localhost IMAGE_TAG=0 make testing-init

try-testing-smoke:
	REGISTRY=localhost IMAGE_TAG=0 make testing-smoke

try-testing-e2e:
	REGISTRY=localhost IMAGE_TAG=0 make testing-e2e

try-testing-down-clear:
	REGISTRY=localhost IMAGE_TAG=0 make testing-down-clear

validate-jenkins:
	curl --user ${USER} -X POST -F "jenkinsfile=<Jenkinsfile" ${HOST}/pipeline-model-converter/validate

deploy:
	envsubst < compose-production.yml > compose-production-env.yml
	DOCKER_HOST=ssh://deploy@${HOST}:${PORT} docker stack deploy --compose-file compose-production-env.yml auction --with-registry-auth --prune

deploy-clean:
	rm -f compose-production-env.yml
