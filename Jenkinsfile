pipeline {
  agent {
    dockerfile true
  }  
  stages {
    stage('PHPUnit') {
      steps {
        sh 'cd /app && bin/phpunit'
      }
    }
  }
}