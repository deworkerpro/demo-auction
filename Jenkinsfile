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
