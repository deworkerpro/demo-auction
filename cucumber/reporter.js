const reporter = require('cucumber-html-reporter')

reporter.generate({
  theme: 'bootstrap',
  jsonFile: 'var/report.json',
  output: 'var/report.html',
  reportSuiteAsScenarios: true,
  scenarioTimestamp: true,
  launchReport: false,
  storeScreenshots: true,
  noInlineScreenshots: true,
  screenshotsDirectory: 'var/screenshots',
})
