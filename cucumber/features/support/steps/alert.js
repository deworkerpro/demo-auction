const { Then } = require('@cucumber/cucumber')
const { expect } = require('chai')

Then('I see success {string}', async function (message) {
  await this.page.waitForSelector('[data-testid="alert-success"]')
  const text = await this.page.$eval('[data-testid="alert-success"]', el => el.innerText)
  expect(text).to.include(message)
})

Then('I see error {string}', async function (message) {
  await this.page.waitForSelector('[data-testid="alert-error"]')
  const text = await this.page.$eval('[data-testid="alert-error"]', el => el.innerText)
  expect(text).to.include(message)
})
