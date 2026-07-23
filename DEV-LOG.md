# Swatle PM — Development Log

> Tracks development progress, restore points, and session summaries.

---

## Restore Points

### RP-1 · 2026-07-15 · Initial Commit (Base)
**Tag/Commit:** `a995dd46` — "Initial commit: Swatle PM - full feature set"  
**State:** Original Taskly SaaS base with 6 custom modules already built (Chat, Agents, KB, Forms, Sprints, Portal). All vendor files committed for zero-build shared hosting deployment.  
**Working:** Dashboard, all 6 custom modules, basic project management  
**Not Working:** Many features from newer Taskly version missing (Gantt, Todos, Contracts, Google Meet, Calendar, Notes, Taxes, etc.)

---

### RP-2 · 2026-07-14 · Shared Hosting Fixes
**Commits:** `4bbbd801` → `72bcdcf1`  
**Changes:**
- Fixed subdirectory routing at `codecartz.com/sundal/` (LiteSpeed/cPanel)
- Suppressed PHP 8.5 `PDO::MYSQL_ATTR_SSL_CA` deprecation warnings
- Fixed Ziggy URL for subdirectory deployment
- Fixed i18n translation URL for subdirectory
- Root `index.php` rewritten as proper subdirectory entry point

---

### RP-3 · 2026-07-15 · Feature Sync from Upstream (Modules 1–6)
**Session Date:** 2026-07-15  
**State:** Synced with latest CodeCanyon Taskly version. All missing features ported.

#### Module 1 — Database Migrations
- Ran 2 pending migrations: `drop_slack_telegram_settings_tables`, `update_invoices_status_enum_partialpaid`

#### Module 2 — Backend Methods + Routes
- **ProjectController** added: `gantt()`, `ganttUpdate()`, `publicView()`, `updateSharedSettings()`, `generateShareLink()`
- **InvoiceController** added: `preview()`, `approvePayment()`, `rejectPayment()`
- **TaskController** added: `getCalendarTasks()`
- **CompanyController** added: `export()`, `import()`, `downloadSample()`
- **UserController** added: `destroyLoginHistory()`, `updateLayoutDirection()`
- **Routes added:** Gantt, public-view, invoice preview/approve/reject, task calendar API, company export/import, referral routes
- **HandleInertiaRequests:** Added `avatar`, `workspace_role`, `storageSettings`, `allowed_file_types`, `max_file_size`, `flash.status/statusType`, `userLanguage`

#### Module 3 — Models + Shared Props
- **Invoice model:** `getTaxRateAttribute()`, `setTaxRateAttribute()` (multi-tax support)
- **Task model:** `assignedUser()` relationship alias
- **User model:** `planOrders()`, `getCurrentWorkspaceRole()`, `findWorkspaceWithActivePlan()`
- **Project model:** `shared_settings` + `password` in `$fillable`, cast to `array`

#### Module 4 — Controllers + Pages + Components
- 54 controllers replaced (all 30 payment controllers updated for invoice payment links)
- TimesheetController, ProjectController, TaskController fully replaced
- SettingsController, SystemSettingsController fully replaced
- 23+ frontend pages replaced/updated
- 22+ components updated

#### Module 5 — Providers, Seeders, Configs, Permissions
- EventServiceProvider, AppServiceProvider replaced
- Config: `dompdf.php`, `excel.php`, `services.php` added
- Seeders replaced and run: PermissionSeeder (+100 permissions), RoleSeeder, EmailTemplateSeeder, NotificationTemplateSeeder
- DatabaseSeeder updated with new demo seeders
- **1,284 permissions auto-assigned to all 6 roles**
- Total: 366 permissions, 673 routes

#### Module 6 — Final UI + Helpers + Referral Fix
- 10 more pages replaced (landing page, brand settings, notification templates, etc.)
- 12 helper functions added: `isRegistrationEnabled`, `defultnotificationAndsetting`, `getPaymentMethodConfig2`, `emailNotificationEnabled`, `isTelegramEnabled`, `isSlackEnabled`, `getSlackWebhookUrl`, `getTelegramConfig`, `isNotificationTypeEnabled`, `setNotificationTypeStatus`, `createDefaultNotificationTemplates`, `createDefaultNotificationTemplateSettings`
- **Referral routes** uncommented (were causing blank dashboard)
- `bootstrap/autoload-packages.php` created for permanent vendor package registration

---

### RP-4 · 2026-07-17 · Crash Fix — Excel Config Breaking Boot
**Issue:** `config/excel.php` copied in Module 5 caused app to crash at boot with `Class "Maatwebsite\Excel\Excel" not found`. The vendor autoloader mappings had been reverted by a `git checkout` command.

**Root Cause:** `vendor/composer/autoload_psr4.php` and `autoload_static.php` are git-tracked with the original sundal autoloader (doesn't include Excel/DomPDF/Google). Any `git checkout HEAD -- vendor/composer/` reverts the manually-added mappings.

**Fix Applied:**
1. Removed `config/excel.php` and `config/dompdf.php` (not needed at boot — auto-discovered by Laravel service providers)
2. Created `bootstrap/autoload-packages.php` — git-tracked custom PSR-4 autoloader for all extra vendor packages
3. Loaded in `public/index.php` and `artisan` after the composer autoloader
4. This is permanent — never affected by `git checkout`

**Status:** App working. All 6 custom modules active.

---

---

### RP-5 · 2026-07-22 · Tester Bug Fixes (Round 1)

**Bugs received:** 6 from tester. Assessment done before coding — 4 fixed in code, 1 by design, 1 requires server-side migration.

#### Bug 1 — Dashboard: "Manage Companies" buried at bottom ✅ Fixed
- Added `Manage Companies` as a primary action button at the top of the Super Admin dashboard (next to Refresh).
- File: `resources/js/pages/dashboard.tsx`

#### Bug 2 — Dashboard: Move Plan/Orders stats inside company ⛔ By Design
- Super Admin seeing global totals (all companies, all plans, all orders, total revenue) is the correct and expected behavior for a platform admin.
- Per-company drilldown is accessible via the Companies list → company profile modal.
- No code change. Documented for tester.

#### Bug 3 — Dashboard: No "i" info icons on cards ✅ Fixed
- Added `Info` icon with `Tooltip` to the Total Companies, Total Plans, and Plan Orders cards explaining what each metric represents.
- File: `resources/js/pages/dashboard.tsx`

#### Bug 4 — Companies: Can't directly access company profile ✅ Fixed
- Company name was plain text in both list view and grid/card view.
- Made company name a clickable button in both views — opens the company info modal on click.
- File: `resources/js/pages/companies/index.tsx`

#### Bug 5 — Forms: 500 error on form creation ✅ Root cause identified — server fix required
- Root cause: Custom migrations for Forms module have never been run on the production server.
- Tables missing: `forms`, `form_fields`, `form_submissions`
- Code is correct (Form model auto-generates token via `boot()` hook).
- **Server fix:** `php artisan migrate --force`

#### Bug 6 — Integrations: Submenus → 500 error ✅ Same root cause as Bug 5
- Root cause: Custom module migrations not run on production server.
- Tables missing: `kb_categories`, `kb_articles`, `kb_attachments`, `api_keys`, `zapier_hooks`, `agents`
- **Server fix:** `php artisan migrate --force` (same command as Bug 5)

**After this RP:**
- Upload `public/build/` to server
- Run `php artisan migrate --force` on server (fixes both Bug 5 and Bug 6)
- Run `php artisan optimize:clear`

---

## Health Metrics (as of RP-4)

| Metric | Value |
|---|---|
| Routes | 673 |
| Permissions | 366 |
| Roles | 6 |
| Custom Modules | 6 (Chat, Agents, KB, Forms, Sprints, Portal) |
| Payment Gateways | 32 |
| Helper Functions | ~45 |
| Google_Client | ✅ |
| Maatwebsite Excel | ✅ |
| DomPDF | ✅ |

---

## Known Pending Items

- [ ] Commit and deploy to `codecartz.com/sundal/` (shared hosting)
- [ ] Run `php artisan migrate` on production after deploy
- [ ] Test all 6 custom modules end-to-end
- [ ] Configure OpenAI API key for Agents/Chatbot
- [ ] Configure Google OAuth for Google Meet/Calendar
- [ ] Set up real-time (polling/WebSocket) for Chat module
- [ ] Form Builder — email notification on submission
- [ ] Client Portal — mobile-friendly styling

---

## Dev Commands Reference

```bash
# Start local server
php artisan serve

# Clear all caches
php artisan optimize:clear

# Clear permission cache
php artisan permission:cache-reset

# Run migrations
php artisan migrate --force

# Re-seed permissions + roles
php artisan db:seed --class=PermissionSeeder --force
php artisan db:seed --class=RoleSeeder --force

# Build frontend
npm run build

# List all routes
php artisan route:list

# Check route count
php artisan route:list | Measure-Object
```
