pipeline {
    agent any
    options {
        timestamps()
    }
    environment {
        CI = 'true'
        REGISTRY = credentials("REGISTRY")
        IMAGE_TAG = sh(returnStdout: true, script: "echo '${env.BUILD_TAG}' | sed 's/%2F/-/g'").trim()
    }
    stages {
        stage("Init") {
            steps {
                sh "make init-ci"
            }
        }
        stage("Valid") {
            steps {
                sh "make api-validate-schema"
            }
        }
        stage("Lint") {
            parallel {
                stage("API") {
                    steps {
                        sh "make api-lint"
                    }
                }
                stage("Frontend") {
                    steps {
                        sh "make frontend-lint"
                    }
                }
                stage("Cucumber") {
                    steps {
                        sh "make cucumber-lint"
                    }
                }
            }
        }
        stage("Analyze") {
            steps {
                sh "make api-analyze"
            }
        }
        stage("Test") {
            parallel {
                stage("API") {
                    steps {
                        sh "make api-test"
                    }
                    post {
                        failure {
                            archiveArtifacts 'api/var/log/**/*'
                        }
                    }
                }
                stage("Front") {
                    steps {
                        sh "make frontend-test"
                    }
                }
            }
        }
        stage("Down") {
            steps {
                sh "make docker-down-clear"
            }
        }
        stage("Build") {
            steps {
                sh "make build"
            }
        }
        stage("Testing") {
            stages {
                stage("Build") {
                    steps {
                        sh "make testing-build"
                    }
                }
                stage("Init") {
                    steps {
                        sh "make testing-init"
                    }
                }
                stage("Smoke") {
                    steps {
                        sh "make testing-smoke"
                    }
                    post {
                        failure {
                            archiveArtifacts 'cucumber/var/*'
                        }
                    }
                }
                stage("E2E") {
                    steps {
                        sh "make testing-e2e"
                    }
                    post {
                        failure {
                            archiveArtifacts 'cucumber/var/*'
                        }
                    }
                }
                stage("Down") {
                    steps {
                        sh "make testing-down-clear"
                    }
                }
            }
        }
        stage("Push") {
            when {
                branch "master"
            }
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'REGISTRY_AUTH',
                        usernameVariable: 'USER',
                        passwordVariable: 'PASSWORD'
                    )
                ]) {
                    sh "docker login -u=$USER -p='$PASSWORD' $REGISTRY"
                }
                sh "make push"
            }
        }
        stage ('Prod') {
            when {
                branch "master"
            }
            steps {
                withCredentials([
                    string(credentialsId: 'PRODUCTION_HOST', variable: 'HOST'),
                    string(credentialsId: 'PRODUCTION_PORT', variable: 'PORT'),
                    string(credentialsId: 'API_DB_PASSWORD', variable: 'API_DB_PASSWORD'),
                    string(credentialsId: 'API_MAILER_HOST', variable: 'API_MAILER_HOST'),
                    string(credentialsId: 'API_MAILER_PORT', variable: 'API_MAILER_PORT'),
                    string(credentialsId: 'API_MAILER_USER', variable: 'API_MAILER_USER'),
                    string(credentialsId: 'API_MAILER_PASSWORD', variable: 'API_MAILER_PASSWORD'),
                    string(credentialsId: 'API_MAILER_FROM_EMAIL', variable: 'API_MAILER_FROM_EMAIL'),
                    string(credentialsId: 'SENTRY_DSN', variable: 'SENTRY_DSN')
                ]) {
                    sshagent (credentials: ['PRODUCTION_AUTH']) {
                        sh "BUILD_NUMBER=${env.BUILD_NUMBER} make deploy"
                    }
                }
            }
        }
    }
    post {
        always {
            sh "make docker-down-clear || true"
            sh "make testing-down-clear || true"
            sh "make deploy-clean || true"
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
