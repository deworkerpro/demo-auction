import { CustomWorld } from '../world'
import { Given } from '@cucumber/cucumber'

Given('I have {string} authorize feature', async function (this: CustomWorld, feature: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.setCookie({
    name: 'features',
    value: feature,
    domain: 'api.localhost',
    httpOnly: false,
    secure: false,
    path: '/authorize',
    sameSite: 'Lax'
  })
})

Given('I do not have {string} authorize feature', async function (this: CustomWorld, feature: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.setCookie({
    name: 'features',
    value: '!' + feature,
    domain: 'api.localhost',
    httpOnly: false,
    secure: false,
    path: '/authorize',
    sameSite: 'Lax'
  })
})
