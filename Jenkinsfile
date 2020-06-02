pipeline {
    agent any
    options {
        timestamps()
    }
    environment {
        CI = 'true'
    }
    stages {
        stage("One") {
            steps {
                sh "sleep 1"
            }
        }
        stage("Two") {
            steps {
                sh "sleep 1"
            }
        }
        stage("Three") {
            steps {
                sh "sleep 1"
            }
        }
    }
}
