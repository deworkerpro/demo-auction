import { Given } from '@cucumber/cucumber'
import { CustomWorld } from '../world'

Given('I am a guest user', () => null)

Given('I am a user', async function (this: CustomWorld) {
  if (!this.page) {
    throw new Error('Page is undefined')
  }
  await this.page.evaluateOnNewDocument(() => {
    localStorage.setItem('auth.tokens', JSON.stringify({
      accessToken:
        'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJmcm9udGVuZCIsImp' +
        '0aSI6IjljY2UxNjgyYmY0ZTY3ODU5OTk4MTc4NWI5ZmQyZTkzNWE1MmVlYjc5YmM' +
        'zYTAwNzQ0YWY5ZmUxNWUwMzc0MWFmZjEyMzI5M2YzY2Y5ODJhIiwiaWF0IjoxNjU' +
        'wMjAwODYwLjMxOTE0OSwibmJmIjoxNjUwMjAwODYwLjMxOTE1MywiZXhwIjozMzI' +
        'wNzEwOTY2MC4zMTU0NTYsInN1YiI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTA' +
        'wMDAwMDAwMDAwMSIsInNjb3BlcyI6WyJjb21tb24iXSwicm9sZSI6InVzZXIifQ.' +
        'izIw14sqt8dMMVQOrg8nUYblbarishmclf5F3offjDku5neQ3vNZVDHPr8g0vTtV' +
        'xMRk3D4HwmcTF_nki-MPDGWt8CMHG4dpMsfJpPrybq2ccmTQMX9oDk_7A_R3ldLt' +
        '0DvmTYmaEsKdspYZFzIjKre0458Bvw8z0xgXmnpaS9uaV7w8fArbNViQza5KkrFu' +
        'AV3AbY-wF28_yBndRnVpYesBTXr9ijD1InRXT2js74CinCZj_4cv0ymbtu1Y9b5s' +
        'YC_Zi_VTYcmIFvyXCK6gIIIbY-xc8NKwC3nb1SdbqLc3qxiuM8VpjlO7ZsLSoV1m' +
        'PSfWoH1XdWult-dumRCo_w',
      expires: new Date().getTime() + 36000000,
      refreshToken: ''
    }))
  })
})
