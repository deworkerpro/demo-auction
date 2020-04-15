const { When, Then } = require('cucumber')
const { expect } = require('chai')

When('I open {string} page', async function (uri) {
  return await this.page.goto('http://gateway:8080' + uri)
})

Then('I see welcome block', async function () {
  await this.page.waitForSelector('[data-test=welcome]')
  const text = await this.page.$eval('[data-test=welcome] h1', el => el.textContent)
  expect(text).to.eql('Auction')
})
