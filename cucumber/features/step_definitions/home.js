const { Then } = require('cucumber')
const { expect } = require('chai')

Then('I see welcome block', async function () {
  await this.page.waitForSelector('[data-test=welcome]')
  const text = await this.page.$eval('[data-test=welcome] h1', el => el.textContent)
  expect(text).to.eql('Auction')
})
