const puppeteer = require('puppeteer')
const { Before, After, Status } = require('cucumber')

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
    const name = testCase.sourceLocation.uri + '-' + testCase.sourceLocation.line
    await this.page.screenshot({ path: 'var/' + name.replace(/\//g, '_') + '.png', fullPage: true })
  }
  await this.page.close()
  await this.browser.close()
})
