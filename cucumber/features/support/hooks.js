const puppeteer = require('puppeteer')
const { Before, After } = require('cucumber')

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

After(async function () {
  await this.browser.close()
})
