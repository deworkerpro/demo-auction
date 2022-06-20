import { CustomWorld } from '../world'
import { When, Then } from '@cucumber/cucumber'
import { expect } from 'chai'

When('I fill {string} field with {string}', async function (this: CustomWorld, name: string, value: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.waitForSelector('[name=' + name + ']')
  await this.page.type('[name=' + name + ']', value)
})

When('I check {string} checkbox', async function (this: CustomWorld, name: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.waitForSelector('[name=' + name + ']')
  await this.page.click('[name=' + name + ']')
})

Then('I click submit button', async function (this: CustomWorld) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.click('button[type=submit]')
})

Then('I see validation error {string}', async function (this: CustomWorld, message: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.waitForSelector('[data-testid=violation]')
  const errors = await this.page.$$eval('[data-testid=violation]', els => els.map(el => el.textContent))
  expect(errors.toString()).to.include(message)
})
