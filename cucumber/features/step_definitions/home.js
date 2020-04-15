const { Given, When, Then } = require('cucumber')

Given('I am a guest user', function () {})

When('I open home page', async function () {
  return await this.page.goto('http://gateway:8080')
})

Then('I see welcome block', function () {
  return 'pending'
})
