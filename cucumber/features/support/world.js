const { setWorldConstructor } = require('cucumber')

function CustomWorld () {
  this.browser = null
  this.page = null
}

setWorldConstructor(CustomWorld)
