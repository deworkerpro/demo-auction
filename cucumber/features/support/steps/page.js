const { When, Then } = require('@cucumber/cucumber')
const { expect } = require('chai')

When('I open {string} page', { wrapperOptions: { retry: 2 }, timeout: 30000 }, async function (uri) {
  return await this.page.goto('http://gateway:8080' + uri)
})

Then('I see {string}', async function (value) {
  const content = await this.page.content()
  expect(content).to.include(value)
})

Then('I do not see {string}', async function (value) {
  const content = await this.page.content()
  expect(content).to.not.include(value)
})

Then('I see {string} element', async function (id) {
  await this.page.waitForSelector('[data-testid=' + id + ']')
})

Then('I see {string} header', async function (value) {
  await this.page.waitForSelector('h1')
  const text = await this.page.$eval('h1', el => el.innerText)
  expect(text).to.equals(value)
})
