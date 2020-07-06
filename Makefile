init: docker-down-clear \
	api-clear frontend-clear cucumber-clear \
	docker-pull docker-build docker-up \
	api-init frontend-init cucumber-init
up: docker-up
down: docker-down
restart: down up
check: lint analyze validate-schema test
lint: api-lint frontend-lint cucumber-lint
analyze: api-analyze
validate-schema: api-validate-schema
test: api-test api-fixtures frontend-test
test-unit: api-test-unit
test-functional: api-test-functional api-fixtures
test-smoke: api-fixtures cucumber-clear cucumber-smoke
test-e2e: api-fixtures cucumber-clear cucumber-e2e

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull --include-deps

docker-build:
	docker-compose build

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* var/test/*'

api-init: api-permissions api-composer-install api-wait-db api-migrations api-fixtures

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var/cache var/log var/test

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-wait-db:
	docker-compose run --rm api-php-cli wait-for-it api-postgres:5432 -t 30

api-migrations:
	docker-compose run --rm api-php-cli composer app migrations:migrate

api-fixtures:
	docker-compose run --rm api-php-cli composer app fixtures:load

api-validate-schema:
	docker-compose run --rm api-php-cli composer app orm:validate-schema

api-lint:
	docker-compose run --rm api-php-cli composer lint
	docker-compose run --rm api-php-cli composer cs-check

api-analyze:
	docker-compose run --rm api-php-cli composer psalm

api-test:
	docker-compose run --rm api-php-cli composer test

api-test-coverage:
	docker-compose run --rm api-php-cli composer test-coverage

api-test-unit:
	docker-compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker-compose run --rm api-php-cli composer test-coverage -- --testsuite=unit

api-test-functional:
	docker-compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-functional-coverage:
	docker-compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

frontend-clear:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine sh -c 'rm -rf .ready build'

frontend-init: frontend-yarn-install frontend-ready

frontend-yarn-install:
	docker-compose run --rm frontend-node-cli yarn install

frontend-ready:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine touch .ready

frontend-lint:
	docker-compose run --rm frontend-node-cli yarn eslint
	docker-compose run --rm frontend-node-cli yarn stylelint

frontend-eslint-fix:
	docker-compose run --rm frontend-node-cli yarn eslint-fix

frontend-pretty:
	docker-compose run --rm frontend-node-cli yarn prettier

frontend-test:
	docker-compose run --rm frontend-node-cli yarn test --watchAll=false

frontend-test-watch:
	docker-compose run --rm frontend-node-cli yarn test

cucumber-clear:
	docker run --rm -v ${PWD}/cucumber:/app -w /app alpine sh -c 'rm -rf var/*'

cucumber-init: cucumber-assets-install

cucumber-assets-install:
	docker-compose run --rm cucumber-node-cli yarn install

cucumber-lint:
	docker-compose run --rm cucumber-node-cli yarn lint

cucumber-lint-fix:
	docker-compose run --rm cucumber-node-cli yarn lint-fix

cucumber-smoke:
	docker-compose run --rm cucumber-node-cli yarn smoke

cucumber-e2e:
	docker-compose run --rm cucumber-node-cli yarn e2e

build: build-gateway build-frontend build-api

build-gateway:
	docker --log-level=debug build --pull --file=gateway/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-gateway:${IMAGE_TAG} gateway/docker

build-frontend:
	docker --log-level=debug build --pull --file=frontend/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-frontend:${IMAGE_TAG} frontend

build-api:
	docker --log-level=debug build --pull --file=api/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-api:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/auction-api-php-cli:${IMAGE_TAG} api

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

push: push-gateway push-frontend push-api

push-gateway:
	docker push ${REGISTRY}/auction-gateway:${IMAGE_TAG}

push-frontend:
	docker push ${REGISTRY}/auction-frontend:${IMAGE_TAG}

push-api:
	docker push ${REGISTRY}/auction-api:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api-php-cli:${IMAGE_TAG}

testing-build: testing-build-gateway testing-build-testing-api-php-cli testing-build-cucumber

testing-build-gateway:
	docker --log-level=debug build --pull --file=gateway/docker/testing/nginx/Dockerfile --tag=${REGISTRY}/auction-testing-gateway:${IMAGE_TAG} gateway/docker

testing-build-testing-api-php-cli:
	docker --log-level=debug build --pull --file=api/docker/testing/php-cli/Dockerfile --tag=${REGISTRY}/auction-testing-api-php-cli:${IMAGE_TAG} api

testing-build-cucumber:
	docker --log-level=debug build --pull --file=cucumber/docker/testing/node/Dockerfile --tag=${REGISTRY}/auction-cucumber-node-cli:${IMAGE_TAG} cucumber

testing-init:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml up -d
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm api-php-cli wait-for-it api-postgres:5432 -t 60
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm api-php-cli php bin/app.php migrations:migrate --no-interaction
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm testing-api-php-cli php bin/app.php fixtures:load --no-interaction

testing-smoke:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm cucumber-node-cli yarn smoke-ci

testing-e2e:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm cucumber-node-cli yarn e2e-ci

testing-down-clear:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml down -v --remove-orphans

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
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -rf site_${BUILD_NUMBER}'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'mkdir site_${BUILD_NUMBER}'
	scp -o StrictHostKeyChecking=no -P ${PORT} docker-compose-production.yml deploy@${HOST}:site_${BUILD_NUMBER}/docker-compose.yml
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "COMPOSE_PROJECT_NAME=auction" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "REGISTRY=${REGISTRY}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_DB_PASSWORD=${API_DB_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_HOST=${API_MAILER_HOST}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_PORT=${API_MAILER_PORT}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_USER=${API_MAILER_USER}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_PASSWORD=${API_MAILER_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "API_MAILER_FROM_EMAIL=${API_MAILER_FROM_EMAIL}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && echo "SENTRY_DSN=${SENTRY_DSN}" >> .env'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose pull'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -f site'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'ln -sr site_${BUILD_NUMBER} site'

rollback:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose pull'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -f site'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'ln -sr site_${BUILD_NUMBER} site'
