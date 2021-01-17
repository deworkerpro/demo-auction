const { Then } = require('@cucumber/cucumber')

Then('I see welcome block', async function () {
  await this.page.waitForSelector('[data-testid=welcome]')
})
