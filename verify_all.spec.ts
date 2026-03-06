import { test, expect } from '@playwright/test';

test('verify all main pages', async ({ page }) => {
  // Public Index
  await page.goto('http://localhost:8000/index.php');
  await page.screenshot({ path: 'verify_public_index.png' });

  // Public Status
  await page.goto('http://localhost:8000/penerimaan_hari_ini.php');
  await page.screenshot({ path: 'verify_public_status.png' });

  // Public Reporting
  await page.goto('http://localhost:8000/input_penerima.php');
  await page.screenshot({ path: 'verify_public_reporting.png' });

  // Admin Login
  await page.goto('http://localhost:8000/admin/index.php');
  await page.fill('input[name="username"]', 'admin');
  await page.fill('input[name="password"]', 'admin123');
  await page.click('button[type="submit"]');
  await expect(page).toHaveURL(/.*admin\/index.php/);
  await page.screenshot({ path: 'verify_admin_dashboard.png' });

  // Admin Complaints
  await page.goto('http://localhost:8000/admin/complaints.php');
  await page.screenshot({ path: 'verify_admin_complaints.png' });
});
