const puppeteer = require('puppeteer')
const { Before, After, Status } = require('@cucumber/cucumber')

Before(async function () {
  this.browser = await puppeteer.launch({
    args: [
      '--disable-dev-shm-usage',
      '--no-sandbox'
    ]
  })
  this.page = await this.browser.newPage()
  await this.page.setViewport({ width: 1280, height: 720 })
})

After(async function (testCase) {
  if (testCase.result.status === Status.FAILED) {
    const name = testCase.pickle.uri.replace(/^\/app\/features\//, '').replace(/\//g, '_') +
      '-' +
      testCase.pickle.name.toLowerCase().replace(/[^\w]/g, '_')
    await this.page.screenshot({ path: 'var/' + name + '.png', fullPage: true })
  }
  await this.page.close()
  await this.browser.close()
})
