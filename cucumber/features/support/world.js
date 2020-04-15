const { setWorldConstructor } = require('cucumber')

function CustomWorld ({ attach }) {
  this.attach = attach
  this.browser = null
  this.page = null
}

setWorldConstructor(CustomWorld)
