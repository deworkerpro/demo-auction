import { Given, When, Then } from '@cucumber/cucumber'
import { expect } from 'chai'
import { CustomWorld } from '../world'

const onPage = async function (this: CustomWorld, uri: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  return await this.page.goto('http://localhost' + uri)
}

Given('I am on {string} page', { wrapperOptions: { retry: 2 }, timeout: 30000 }, onPage)

When('I open {string} page', { wrapperOptions: { retry: 2 }, timeout: 30000 }, onPage)

Then('I see {string}', async function (this: CustomWorld, value: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.waitForFunction(
    (text: string) => {
      const el = document.querySelector('body')
      return el ? el.innerText.includes(text) : false
    },
    {},
    value
  )
})

Then('I do not see {string}', async function (this: CustomWorld, value: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  const content = await this.page.content()
  expect(content).to.not.include(value)
})

Then('I see {string} element', async function (this: CustomWorld, id: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.waitForSelector('[data-testid=' + id + ']')
})

Then('I do not see {string} element', async function (this: CustomWorld, id: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  const elem = await this.page.$('[data-testid=' + id + ']')
  expect(elem).to.be.a('null')
})

Then('I click {string} element', async function (this: CustomWorld, id: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.click('[data-testid=' + id + ']')
})

Then('I see {string} header', async function (this: CustomWorld, value: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.waitForFunction(
    (text: string) => {
      const el = document.querySelector('h1')
      return el ? el.innerText.includes(text) : false
    },
    {},
    value
  )
})
