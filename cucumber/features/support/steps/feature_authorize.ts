import { CustomWorld } from '../world'
import { Given } from '@cucumber/cucumber'

Given('I have {string} authorize features', async function (this: CustomWorld, features: string) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.setCookie({
    name: 'features',
    value: features,
    domain: 'api.localhost',
    httpOnly: false,
    secure: false,
    path: '/authorize',
    sameSite: 'Lax'
  })
})
