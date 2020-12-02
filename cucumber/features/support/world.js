const { setWorldConstructor } = require('@cucumber/cucumber')

function CustomWorld ({ attach }) {
  this.attach = attach
  this.browser = null
  this.page = null
}

setWorldConstructor(CustomWorld)
