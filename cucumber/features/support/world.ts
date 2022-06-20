import { Browser, Page } from 'puppeteer'
import { setWorldConstructor, World } from '@cucumber/cucumber'

export class CustomWorld extends World {
  browser: Browser | null = null
  page: Page | null = null
}

setWorldConstructor(CustomWorld)
