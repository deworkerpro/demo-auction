const { When } = require('cucumber')

When('I open {string} page', async function (uri) {
  return await this.page.goto('http://gateway:8080' + uri)
})
