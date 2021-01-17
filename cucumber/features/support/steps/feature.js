const { Given } = require('@cucumber/cucumber')

Given('I have {string} feature', async function (feature) {
  await this.page.setCookie({
    name: 'features',
    value: feature,
    domain: 'gateway',
    httpOnly: false,
    secure: false,
    path: '/',
    sameSite: 'lax'
  })
})

Given('I do not have {string} feature', async function (feature) {
  await this.page.setCookie({
    name: 'features',
    value: '!' + feature,
    domain: 'gateway',
    httpOnly: false,
    secure: false,
    path: '/',
    sameSite: 'lax'
  })
})
