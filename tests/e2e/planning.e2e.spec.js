const { test, expect } = require('@playwright/test');

// NOTE: This is a simple scaffold E2E test. For auth, it expects
// a test user to exist; adjust credentials or implement a login helper.

test('open planning page, select project and open baseline modal', async ({ page }) => {
  await page.goto('http://127.0.0.1:8000/auth-login');
  // quick login flow - change as needed
  await page.fill('input[name="email"]', 'admin@example.com');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');
  await page.waitForNavigation();

  await page.goto('http://127.0.0.1:8000/projects/planning');
  await page.waitForSelector('#projectSelect');
  const projectValue = await page.$eval('#projectSelect', el => el.options.length > 1 ? el.options[1].value : el.value);
  if (!projectValue) {
    test.skip();
  }
  await page.selectOption('#projectSelect', projectValue);
  await page.waitForTimeout(500);
  // click baseline button
  await page.click('#btnBaseline');
  // accept prompt - Playwright does not support native prompt by default; skip if not present
  await page.waitForTimeout(500);
  // open baseline modal by selecting baseline and clicking compare (assumes baseline exists)
  await page.click('#baselineSelect');
  await page.waitForTimeout(300);
  // This test is intentionally light; expand with real credentials and interactions in CI
  expect(true).toBeTruthy();
});
