pipeline {
    agent any
    options {
        timestamps()
        disableConcurrentBuilds()
    }
    environment {
        CI = 'true'
        REGISTRY = credentials('REGISTRY')
        IMAGE_TAG = sh(
            returnStdout: true,
            script: "echo '${env.BUILD_TAG}' | sed 's/%2F/-/g'"
        ).trim()
        GIT_DIFF_BASE_COMMIT = sh(
            returnStdout: true,
            script: "echo ${env.GIT_PREVIOUS_SUCCESSFUL_COMMIT ?: '`git rev-list HEAD | tail -n 1`'}"
        ).trim()
        GIT_DIFF_API = sh(
            returnStdout: true,
            script: "git diff --name-only ${env.GIT_DIFF_BASE_COMMIT} HEAD -- api || echo 'all'"
        ).trim()
        GIT_DIFF_FRONTEND = sh(
            returnStdout: true,
            script: "git diff --name-only ${env.GIT_DIFF_BASE_COMMIT} HEAD -- frontend || echo 'all'"
        ).trim()
        GIT_DIFF_CUCUMBER = sh(
            returnStdout: true,
            script: "git diff --name-only ${env.GIT_DIFF_BASE_COMMIT} HEAD -- cucumber || echo 'all'"
        ).trim()
        GIT_DIFF_ROOT = sh(
            returnStdout: true,
            script: "{ git diff --name-only ${env.GIT_DIFF_BASE_COMMIT} HEAD -- . || echo 'all'; } | { grep -v / - || true; }"
        ).trim()
    }
    stages {
        stage('Init') {
            steps {
                sh 'touch .docker-images-before'
                sh 'make init-ci'
                sh 'docker compose images --format json | jq --compact-output \'sort_by(.Repository) | .[] | {(.Repository): .Created}\' > .docker-images-after'
                script {
                    DOCKER_DIFF = sh(
                        returnStdout: true,
                        script: 'diff .docker-images-before .docker-images-after || true'
                    ).trim()
                }
            }
        }
        stage('Valid') {
            when {
                expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_API }
            }
            steps {
                sh 'make api-validate-schema'
            }
        }
        stage('Lint') {
            parallel {
                stage('API') {
                    when {
                        expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_API }
                    }
                    steps {
                        sh 'make api-lint'
                    }
                }
                stage('Frontend') {
                    when {
                        expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_FRONTEND }
                    }
                    steps {
                        sh 'make frontend-lint'
                    }
                }
                stage('Cucumber') {
                    when {
                        expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_CUCUMBER }
                    }
                    steps {
                        sh 'make cucumber-lint'
                    }
                }
            }
        }
        stage('Analyze') {
            parallel {
                stage('API') {
                    when {
                        expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_API }
                    }
                    steps {
                        sh 'make api-analyze'
                    }
                }
                stage('Frontend') {
                    when {
                        expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_FRONTEND }
                    }
                    steps {
                        sh 'make frontend-ts-check'
                    }
                }
                stage('Cucumber') {
                    when {
                        expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_CUCUMBER }
                    }
                    steps {
                        sh 'make cucumber-ts-check'
                    }
                }
            }
        }
        stage('Backup') {
            when {
                expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_API }
            }
            steps {
                sh 'make api-backup'
            }
        }
        stage('Test') {
            parallel {
                stage('API') {
                    when {
                        expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_API }
                    }
                    steps {
                        sh 'make api-test'
                    }
                    post {
                        failure {
                            archiveArtifacts 'api/var/log/**/*'
                        }
                    }
                }
                stage('Front') {
                    when {
                        expression { return DOCKER_DIFF || env.GIT_DIFF_ROOT || env.GIT_DIFF_FRONTEND }
                    }
                    steps {
                        sh 'make frontend-test'
                    }
                }
            }
        }
        stage('Down') {
            steps {
                sh 'make docker-down-clear'
            }
        }
        stage('Build') {
            parallel {
                stage('API') {
                    steps {
                        sh 'make build-api'
                    }
                }
                stage('Front') {
                    steps {
                        sh 'make build-frontend'
                    }
                }
            }
        }
        stage('Testing') {
            stages {
                stage('Build') {
                    steps {
                        sh 'make testing-build'
                    }
                }
                stage('Init') {
                    steps {
                        sh 'make testing-init'
                    }
                }
                stage('Smoke') {
                    steps {
                        sh 'make testing-smoke'
                    }
                    post {
                        failure {
                            archiveArtifacts 'cucumber/var/*'
                        }
                    }
                }
                stage('E2E') {
                    steps {
                        sh 'make testing-e2e'
                    }
                    post {
                        failure {
                            archiveArtifacts 'cucumber/var/*'
                        }
                    }
                }
                stage('Down') {
                    steps {
                        sh 'make testing-down-clear'
                    }
                }
            }
        }
        stage('Push') {
            when {
                branch 'master'
            }
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'REGISTRY_AUTH',
                        usernameVariable: 'USER',
                        passwordVariable: 'PASSWORD'
                    )
                ]) {
                    sh 'docker login -u="$USER" -p="$PASSWORD" $REGISTRY'
                }
                sh 'make push'
            }
        }
        stage ('Prod') {
            when {
                branch 'master'
            }
            environment {
                TEMP_PATH = pwd(tmp: true)
            }
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'PRODUCTION_REGISTRY_AUTH',
                        usernameVariable: 'USER',
                        passwordVariable: 'PASSWORD'
                    )
                ]) {
                    sh 'docker login -u="$USER" -p="$PASSWORD" $REGISTRY'
                }
                withCredentials([
                    string(credentialsId: 'PRODUCTION_HOST', variable: 'HOST'),
                    string(credentialsId: 'PRODUCTION_PORT', variable: 'PORT'),
                    file(credentialsId: 'API_DB_PASSWORD_FILE', variable: 'API_DB_PASSWORD_FILE'),
                    string(credentialsId: 'API_MAILER_HOST', variable: 'API_MAILER_HOST'),
                    string(credentialsId: 'API_MAILER_PORT', variable: 'API_MAILER_PORT'),
                    string(credentialsId: 'API_MAILER_USERNAME', variable: 'API_MAILER_USERNAME'),
                    file(credentialsId: 'API_MAILER_PASSWORD_FILE', variable: 'API_MAILER_PASSWORD_FILE'),
                    string(credentialsId: 'API_MAILER_FROM_EMAIL', variable: 'API_MAILER_FROM_EMAIL'),
                    file(credentialsId: 'SENTRY_DSN_FILE', variable: 'SENTRY_DSN_FILE'),
                    file(credentialsId: 'JWT_ENCRYPTION_KEY_FILE', variable: 'JWT_ENCRYPTION_KEY_FILE'),
                    file(credentialsId: 'JWT_PUBLIC_KEY_FILE', variable: 'JWT_PUBLIC_KEY_FILE'),
                    file(credentialsId: 'JWT_PRIVATE_KEY_FILE', variable: 'JWT_PRIVATE_KEY_FILE'),
                    string(credentialsId: 'OAUTH_YANDEX_CLIENT_ID', variable: 'OAUTH_YANDEX_CLIENT_ID'),
                    file(credentialsId: 'OAUTH_YANDEX_CLIENT_SECRET_FILE', variable: 'OAUTH_YANDEX_CLIENT_SECRET_FILE'),
                    string(credentialsId: 'OAUTH_MAILRU_CLIENT_ID', variable: 'OAUTH_MAILRU_CLIENT_ID'),
                    file(credentialsId: 'OAUTH_MAILRU_CLIENT_SECRET_FILE', variable: 'OAUTH_MAILRU_CLIENT_SECRET_FILE'),
                    string(credentialsId: 'BACKUP_AWS_ACCESS_KEY_ID', variable: 'BACKUP_AWS_ACCESS_KEY_ID'),
                    file(credentialsId: 'BACKUP_AWS_SECRET_ACCESS_KEY_FILE', variable: 'BACKUP_AWS_SECRET_ACCESS_KEY_FILE'),
                    string(credentialsId: 'BACKUP_AWS_DEFAULT_REGION', variable: 'BACKUP_AWS_DEFAULT_REGION'),
                    string(credentialsId: 'BACKUP_S3_ENDPOINT', variable: 'BACKUP_S3_ENDPOINT'),
                    string(credentialsId: 'BACKUP_S3_BUCKET', variable: 'BACKUP_S3_BUCKET')
                ]) {
                    sshagent (credentials: ['PRODUCTION_AUTH']) {
                        sh 'mkdir -p ~/.ssh'
                        sh 'ssh-keyscan -p ${PORT} -H ${HOST} > ~/.ssh/known_hosts'
                        sh 'make deploy'
                    }
                }
            }
        }
    }
    post {
        success {
            sh 'mv -f .docker-images-after .docker-images-before'
        }
        always {
            sh 'make docker-down-clear || true'
            sh 'make testing-down-clear || true'
        }
        failure {
            emailext (
                subject: "FAIL Job ${env.JOB_NAME} ${env.BUILD_NUMBER}",
                body: "Check console output at: ${env.BUILD_URL}/console",
                recipientProviders: [[$class: 'DevelopersRecipientProvider']]
            )
        }
    }
}
