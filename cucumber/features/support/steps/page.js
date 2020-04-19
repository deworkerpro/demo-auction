const { When } = require('cucumber')

When('I open {string} page', { wrapperOptions: { retry: 2 }, timeout: 30000 }, async function (uri) {
  return await this.page.goto('http://gateway:8080' + uri)
})
