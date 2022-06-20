import { Then } from '@cucumber/cucumber'
import { expect } from 'chai'
import { CustomWorld } from '../world'

Then('I see success {string}', async function (this: CustomWorld, message: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.waitForSelector('[data-testid="alert-success"]')
  const text = await this.page.$eval('[data-testid="alert-success"]', el => el.textContent)
  expect(text).to.include(message)
})

Then('I see error {string}', async function (this: CustomWorld, message: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.waitForSelector('[data-testid="alert-error"]')
  const text = await this.page.$eval('[data-testid="alert-error"]', el => el.textContent)
  expect(text).to.include(message)
})
