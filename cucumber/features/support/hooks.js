const puppeteer = require('puppeteer')
const { Before, After } = require('cucumber')

Before(async function () {
  this.browser = await puppeteer.launch({
    args: [
      '--disable-dev-shm-usage',
      '--no-sandbox'
    ]
  })
})

After(async function () {
  await this.browser.close()
})
