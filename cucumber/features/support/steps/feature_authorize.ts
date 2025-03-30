import { CustomWorld } from '../world'
import { Given } from '@cucumber/cucumber'

Given('I have {string} authorize features', async function (this: CustomWorld, features: string) {
  if (!this.browser) {
    throw new Error('Page is undefined')
  }
  await this.browser.setCookie({
    name: 'features',
    value: features,
    domain: 'api.localhost',
    httpOnly: false,
    secure: false,
    path: '/authorize',
    sameSite: 'Lax'
  })
})
