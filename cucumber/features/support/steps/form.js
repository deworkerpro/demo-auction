const { When, Then } = require('@cucumber/cucumber')
const { expect } = require('chai')

When('I fill {string} field with {string}', async function (name, value) {
  await this.page.waitForSelector('[name=' + name + ']')
  await this.page.type('[name=' + name + ']', value)
})

When('I check {string} checkbox', async function (name) {
  await this.page.waitForSelector('[name=' + name + ']')
  await this.page.click('[name=' + name + ']')
})

Then('I click submit button', async function () {
  await this.page.click('button[type=submit]')
})

Then('I see validation error {string}', async function (message) {
  await this.page.waitForSelector('[data-testid=violation]')
  const errors = await this.page.$$eval('[data-testid=violation]', els => els.map(el => el.innerText))
  expect(errors.toString()).to.include(message)
})
