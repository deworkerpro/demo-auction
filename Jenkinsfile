pipeline {
    agent any
    options {
        timestamps()
    }
    environment {
        CI = 'true'
    }
    stages {
        stage("Init") {
            steps {
                sh "make init"
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
    }
    post {
        always {
            sh "make docker-down-clear || true"
        }
    }
}
