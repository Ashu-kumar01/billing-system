================================================================================
 BILLING SYSTEM - PROJECT DOCUMENTATION (A to Z)
================================================================================
For any developer joining this project. Read top to bottom once, then use as
reference. Written in plain language on purpose.


--------------------------------------------------------------------------------
1. WHAT THIS PROJECT IS
--------------------------------------------------------------------------------
A billing / invoicing management system for a shop or small business (like a
mini Zoho Books / QuickBooks). It manages:

  - Customers & Suppliers
  - Products, Categories, Units, Stock
  - Invoices (sales) with line items, tax, discount, PDF/print
  - Purchases (buying stock from suppliers) with line items
  - Payments (receiving money from customers / paying suppliers)
  - Expenses
  - Reports (sales, purchases, expenses, customers, profit & loss)
  - Users with roles (owner, admin, manager, cashier)
  - Company settings (invoice prefix, currency, GST rate, etc.)
  - A dashboard with charts and stats

Tech stack: Laravel 12, PHP 8.3, MySQL, Blade templates, Tailwind CSS 3,
Alpine.js, Chart.js, SweetAlert2, Font Awesome icons, barryvdh/laravel-dompdf
for PDF generation.


--------------------------------------------------------------------------------
2. IMPORTANT RULE: DATABASE ALREADY EXISTS
--------------------------------------------------------------------------------
The MySQL database "billing_system" already exists with all tables already
designed. THERE ARE NO LARAVEL MIGRATIONS FOR THE BUSINESS TABLES.

  - Do NOT run migrations that create/alter the business tables.
  - Do NOT add new columns "just because it would be convenient" - work with
    what exists.
  - If you truly need a schema change, that is a real decision to raise with
    whoever owns the database, not something to silently add via migration.

The only Laravel-generated tables (from Breeze/framework defaults) are:
cache, cache_locks, failed_jobs, job_batches, jobs, migrations,
password_reset_tokens, sessions.

Everything else (categories, customers, products, invoices, etc.) is the
pre-existing business schema. See section 5 for the full table list.


--------------------------------------------------------------------------------
3. HOW TO RUN THE PROJECT LOCALLY
--------------------------------------------------------------------------------
Requirements: PHP 8.3+, Composer, Node.js + npm, MySQL running with a
database called "billing_system" already created and populated.

Steps:
  1. composer install
  2. npm install
  3. Copy .env.example to .env if you don't have one, then set:
       DB_DATABASE=billing_system
       DB_USERNAME=root
       DB_PASSWORD=(your password)
  4. php artisan key:generate   (only if APP_KEY is empty)
  5. npm run build              (compiles Tailwind CSS + JS into public/build)
     - or: npm run dev           (for live-reloading while developing)
  6. php artisan serve           (starts the app at http://127.0.0.1:8000)

Log in with any user that exists in the "users" table of the database
(password must be a bcrypt hash - use php artisan tinker to create one if
needed, see section 9).


--------------------------------------------------------------------------------
4. FOLDER STRUCTURE - WHERE TO FIND THINGS
--------------------------------------------------------------------------------
app/Models/                  -> Eloquent models (one per table), with
                                 relationships and small helper methods.
app/Http/Controllers/        -> One controller per module (CustomerController,
                                 ProductController, InvoiceController, etc.)
app/Http/Requests/           -> Form Request classes = validation rules for
                                 each Store/Update action.
routes/web.php                -> ALL routes for the whole app live here.
resources/views/              -> Blade templates.
  layouts/app.blade.php        -> Main authenticated layout (sidebar + topbar).
  layouts/sidebar.blade.php    -> Left navigation menu (edit this to add/remove
                                   a menu item).
  layouts/topbar.blade.php     -> Top header bar (search box, dark mode
                                   toggle, notifications, profile dropdown).
  layouts/guest.blade.php       -> Layout used by login/register pages.
  components/                  -> Reusable Blade components, see section 7.
  dashboard.blade.php          -> The dashboard/home page.
  categories/, units/,
  customers/, suppliers/,
  products/, invoices/,
  payments/, expenses/,
  expense-categories/,
  purchases/, reports/,
  users/, settings/             -> One folder per module, each usually has
                                   index.blade.php (list), create.blade.php
                                   (add form), edit.blade.php (edit form),
                                   show.blade.php (detail page).
resources/css/app.css         -> Tailwind + all custom design classes (see
                                  section 6). This is the ONE file that
                                  controls the whole visual look of the app.
resources/js/app.js            -> Alpine.js, SweetAlert2 and Chart.js are
                                  loaded here as window globals.
tailwind.config.js             -> Color palette, dark mode setting, fonts.


--------------------------------------------------------------------------------
5. DATABASE TABLES (business schema, already exists)
--------------------------------------------------------------------------------
stores              - multi-store support (name, code, gst_number, etc.)
counters            - billing counters/terminals inside a store
users                - store_id, name, email, phone, password, role (enum:
                       owner/admin/manager/cashier), status
customers            - name, email, phone, address, opening_balance, status
suppliers             - name, company_name, email, phone, gst_number, status
categories            - product categories (name, slug, status)
units                 - product units of measure (name, short_name, status)
products              - category_id, unit_id, supplier_id, sku, barcode,
                        cost_price, selling_price, stock, alert_quantity
product_stocks        - per-store stock snapshot (quantity, reserved,
                        available)
stock_movements       - audit log of every stock change (purchase, sale,
                        adjustment, return, damage, transfer)
invoices              - customer_id, store_id, counter_id, user_id,
                        invoice_no, totals, paid/due amounts, payment_status
                        (paid/partial/due)
invoice_items         - line items belonging to an invoice
purchases              - supplier_id, store_id, invoice_no, totals,
                        paid/due amounts, status (pending/completed/cancelled)
purchase_items        - line items belonging to a purchase
payments               - can belong to EITHER an invoice OR a purchase
                        (amount, payment_method, transaction_id, payment_date)
expenses               - title, amount, category (enum), expense_date
expense_categories     - a SEPARATE lookup table from the expense "category"
                        enum column (see note in section 8)
settings               - simple key/value store used for company settings
                        (see App\Models\Setting::get()/set())


--------------------------------------------------------------------------------
6. DESIGN SYSTEM - HOW STYLING WORKS
--------------------------------------------------------------------------------
Everything is Tailwind CSS, but instead of repeating long utility strings
everywhere, we defined reusable custom classes in resources/css/app.css. If
you are building a new page, USE THESE, don't invent new ones:

  .card              -> white/rounded/bordered/shadow container box
  .btn-primary        -> main blue gradient button
  .btn-secondary       -> plain white/bordered button
  .btn-danger          -> red button (for destructive actions)
  .btn-sm              -> smaller padding variant, combine with the above
  .form-input / .form-select / .form-textarea
                        -> all form fields
  .form-label          -> label above a form field
  .badge-success / .badge-warning / .badge-danger / .badge-info / .badge-muted
                        -> small colored pill labels (used for statuses)
  .table-modern        -> put this class on <table> for the styled list
                          tables (sticky header, hover rows, spacing)
  .nav-link-item        -> sidebar menu link style (has .active variant)

Color tokens (used as text-ink, bg-surface-muted, border-border, etc.):
  primary   #2563EB (blue)      - buttons, links, active states
  secondary #0EA5E9 (sky blue)  - secondary accents
  success   #22C55E (green)     - paid/success indicators
  danger    #EF4444 (red)       - delete/errors/overdue
  warning   #F59E0B (amber)     - partial/pending indicators
  ink                            - main text color (auto light/dark, see below)
  muted                          - secondary/lighter text
  border                          - all borders
  surface / surface.subtle/muted/soft - background shades used for page and
                                        card backgrounds

IMPORTANT: ink, muted, border and surface.* are NOT fixed hex colors. They are
CSS variables (defined at the top of app.css inside :root and .dark) so that
the ENTIRE app automatically switches between light and dark mode without
needing to add "dark:" classes to every single page. If you use these tokens
consistently, new pages get dark mode "for free".

Reusable Blade components (in resources/views/components/):
  <x-app-layout>                 -> wraps every authenticated page
  <x-guest-layout>                -> wraps login/register/forgot-password
  <x-page-header title="..." subtitle="...">
        <x-slot name="actions"> ... </x-slot>   (optional, for buttons)
  </x-page-header>
  <x-stat-card label="..." value="..." icon="fa-..." color="primary|success|warning|danger|secondary" />
  <x-badge color="success|warning|danger|info|muted">Text</x-badge>
  <x-empty-state icon="fa-..." title="..." subtitle="..." />
  <x-delete-form :action="route('xxx.destroy', $model)" label="{{ $model->name }}" />
        (renders a trash icon button with a SweetAlert2 "are you sure?" popup)
  <x-breadcrumb :items="['Label' => route(...), 'Current Page' => null]" />
  <x-input-label>, <x-text-input>, <x-input-error>  -> form field building
        blocks (already styled, from Laravel Breeze, customized for this app)


--------------------------------------------------------------------------------
7. DARK MODE
--------------------------------------------------------------------------------
There is a sun/moon icon button in the top bar (resources/views/layouts/
topbar.blade.php) that toggles dark mode. How it works:

  1. On page load, a small inline <script> in layouts/app.blade.php checks
     localStorage.getItem('theme') (or the OS preference if never set) and
     adds the "dark" class to <html> BEFORE the page paints, so there's no
     flash of the wrong theme.
  2. Clicking the toggle button adds/removes the "dark" class on <html> via
     Alpine.js and saves the choice to localStorage so it persists across
     page loads and future visits.
  3. Tailwind is configured with darkMode: 'class' in tailwind.config.js, so
     any class prefixed dark: only applies when <html> has the "dark" class.
  4. Most of the actual color-switching happens automatically through the
     CSS variable tokens described in section 6 (ink/muted/border/surface).
     Custom component classes (.card, .btn-secondary, .badge-*, .form-input,
     .table-modern, .nav-link-item) also have explicit dark: overrides baked
     directly into their definitions in app.css, so you don't need to think
     about dark mode when using them - it's already handled.

If you add a brand new page with unusual raw Tailwind colors (e.g. a literal
bg-white or text-gray-900 instead of the tokens above), it will NOT auto-adapt
to dark mode - prefer the tokens/component classes above instead.


--------------------------------------------------------------------------------
8. THINGS THAT LOOK ODD ON PURPOSE (read before "fixing" them)
--------------------------------------------------------------------------------
- expense_categories is a separate lookup table from the `category` enum
  column on the expenses table itself. They are intentionally NOT linked by a
  foreign key - this matches the existing database design. Don't be surprised
  that the Expense create form uses a hardcoded dropdown (rent/salary/
  utilities/transport/maintenance/purchase/other) instead of pulling from
  expense_categories.

- Invoices and Purchases can only have their HEADER fields edited after
  creation (customer/supplier, date, note). Line items cannot be edited once
  saved - this is intentional, because editing line items after stock has
  already been deducted/added would require complex reconciliation logic.
  If you need to change items, delete the invoice/purchase (this reverses the
  stock movement automatically) and recreate it.

- Payments can belong to EITHER an invoice OR a purchase (never both) - a
  payment against an invoice means "customer paid us", a payment against a
  purchase means "we paid the supplier". Both flow through the same
  PaymentController and payments table.

- Every stock change (from an invoice sale, a purchase receipt, or a manual
  deletion/reversal) is logged in the stock_movements table for audit purposes
  - this is populated automatically inside DB transactions in
  InvoiceController and PurchaseController. Don't bypass this when writing
  new stock-affecting code.

- There is no product image / customer photo upload anywhere. The database
  has no image columns for these tables, and per project rules we do not
  modify the existing schema, so this feature was intentionally left out.

- Settings (company name, invoice prefix, currency, GST rate, timezone, etc.)
  are stored as simple key-value rows in the `settings` table, NOT as
  individual columns. Always read/write them through App\Models\Setting::get()
  and Setting::set() - these use Laravel's cache to avoid repeated DB queries,
  bypassing them and querying the Setting model directly will break caching.

- The app is effectively single-store in the UI (even though the database
  supports multiple stores via store_id on several tables). Anywhere a
  store_id is needed, the code uses:
      auth()->user()->store_id ?? \App\Models\Store::first()?->id
  If you need real multi-store support later, that's a bigger feature to plan
  properly, not a quick patch.


--------------------------------------------------------------------------------
9. COMMON TASKS
--------------------------------------------------------------------------------
Create a new user (e.g. to log in for the first time):
    php artisan tinker
    >>> \App\Models\User::create([
            'store_id' => \App\Models\Store::first()->id,
            'name' => 'Your Name',
            'email' => 'you@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'status' => 1,
        ]);

Add a new menu item to the sidebar:
    Edit resources/views/layouts/sidebar.blade.php - it's a simple PHP array
    at the top of the file ($menu), just add a new entry with label/route/icon.

Add a brand new module/page (e.g. "Coupons"):
    1. Model in app/Models/ (if the table already exists in the DB).
    2. Controller: php artisan make:controller CouponController --resource
    3. Form Requests: php artisan make:request StoreCouponRequest (+ Update...)
    4. Add Route::resource('coupons', CouponController::class) to
       routes/web.php inside the auth middleware group.
    5. Views in resources/views/coupons/ (index/create/edit/show), following
       the same patterns as an existing simple module like categories/ or
       units/ - copy one of those as a starting template.
    6. Add a sidebar link (see above) if it should be reachable from the menu.

Change the color palette:
    Edit tailwind.config.js (the theme.extend.colors section) and/or the CSS
    variables at the top of resources/css/app.css, then run npm run build.

Regenerate compiled CSS/JS after any Blade/CSS/JS change (for production):
    npm run build
(While developing, run "npm run dev" instead and leave it running - it will
auto-recompile on save.)

Generate an invoice PDF:
    Already wired up. GET /invoices/{id}/pdf downloads a PDF, and
    GET /invoices/{id}/print opens a print-ready HTML view in a new tab.
    Same pattern exists for purchases at /purchases/{id}/pdf.
    The PDF template (plain inline CSS, not Tailwind, because dompdf has
    limited CSS support) lives at resources/views/invoices/print.blade.php
    and resources/views/purchases/pdf.blade.php.


--------------------------------------------------------------------------------
10. KNOWN LIMITATIONS / NOT IMPLEMENTED
--------------------------------------------------------------------------------
- No image/photo uploads (products, customers) - no DB columns for it.
- No true multi-tenant/multi-store switching UI - defaults to the first store.
- No Excel export (only CSV export on the Sales report) - a full Excel
  package (e.g. maatwebsite/excel) was intentionally skipped to keep
  dependencies lean; add it later if truly needed.
- Language dropdown and some notification bell UI are visual placeholders
  (not wired to real functionality) - only dark mode toggle is fully live.
- No activity log / audit trail beyond stock_movements.
- No roles/permissions table - roles are a fixed enum on the users table
  (owner/admin/manager/cashier). If you need granular permissions later,
  that's a real feature to design, not a quick edit.


--------------------------------------------------------------------------------
11. WHO TO ASK / WHERE TO LOOK WHEN STUCK
--------------------------------------------------------------------------------
- Route not found?            -> check routes/web.php, it's all in one file.
- Wrong data on a page?        -> check the matching Controller in
                                   app/Http/Controllers/.
- Validation error message wrong/missing?
                                -> check app/Http/Requests/Store*/Update*.
- Style looks off?             -> check resources/css/app.css first for the
                                   relevant custom class, then
                                   tailwind.config.js for color tokens.
- Something about stock/totals doesn't add up?
                                -> check InvoiceController@store /
                                   PurchaseController@store - totals are
                                   ALWAYS recalculated server-side from the
                                   submitted line items, never trusted from
                                   the client, for correctness.

================================================================================
 END OF FILE
================================================================================
