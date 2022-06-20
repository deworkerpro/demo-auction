import { CustomWorld } from '../world'
import { Given } from '@cucumber/cucumber'

Given('I have {string} feature', async function (this: CustomWorld, feature: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.setCookie({
    name: 'features',
    value: feature,
    domain: 'localhost',
    httpOnly: false,
    secure: false,
    path: '/',
    sameSite: 'Lax'
  })
})

Given('I do not have {string} feature', async function (this: CustomWorld, feature: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.setCookie({
    name: 'features',
    value: '!' + feature,
    domain: 'localhost',
    httpOnly: false,
    secure: false,
    path: '/',
    sameSite: 'Lax'
  })
})
