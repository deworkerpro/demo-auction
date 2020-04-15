const { setWorldConstructor } = require('cucumber')

function CustomWorld () {
  this.browser = null
}

setWorldConstructor(CustomWorld)
