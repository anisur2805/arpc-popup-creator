# ARPC Popup Creator — Master Prompt

Single source of truth for any agent or developer working on this plugin.
Consolidates `README.md`, `FEATURE_CHECKLIST.md`, `FEATURE_CHECKLIST-2.md`, `Feature3.md`,
and the reference screenshots in `ref/`. When those files disagree with this one, this one wins.

Last consolidated: 2026-08-04.

---

## 1. Product identity

**Name:** Popup Creator (ARPC Popup Creator)
**Slug / text domain:** `arpc-popup-creator`
**Prefix:** `arpc_` / `ARPC_` — namespace `ARPC\Popup\` (PSR-4 → `includes/`)
**Post type:** `arpc_popup` (supports `title`, `editor`, `thumbnail`; `show_in_rest` true)
**Settings meta key:** `arpc_popup_settings` (single serialized array, see §5)
**License:** GPL v2 or later
**Author:** Anisur Rahman

**One-line positioning:** a WordPress-native popup plugin where the popup body **is** the
Gutenberg editor canvas, and the free tier ships features competitors lock behind Pro.

### Non-negotiable product principles

1. **Gutenberg is the builder.** The popup body is block content edited in the normal editor
   canvas. No structured title/subtitle/image fields, no fixed PHP templates as the primary
   authoring path. Legacy template files (`includes/Views/frontend/template1-3.php`,
   `signup-form*.php`) are legacy and should shrink, not grow.
2. **Not a clone.** The `ref/` screenshots (Divi "Popup Pro Settings") define the *feature
   surface*, not the product. Do not rebuild it setting-for-setting or copy its UI. Match the
   capability, then make the UX simpler and more WordPress-native.
3. **Practical and sellable.** Simple, useful, shippable. No speculative architecture, no
   "billion-dollar platform" scope. Every added setting must earn its place.
4. **Free tier must embarrass competitors.** See §3.

---

## 2. Current state (verify before trusting)

Implemented (per code as of this writing):

- `arpc_popup` CPT registered in all contexts (admin, frontend, REST) so block-editor saving works.
- `includes/Services/Popup_Settings.php` — the settings schema: defaults, sanitization, choice
  lists, free-vs-pro trigger gating. **This is the canonical field list.**
- `includes/Views/admin/metabox.php` + `assets/js/metabox.js` + `assets/css/metabox.css` —
  the three-tab settings metabox (General / Customization / Display Conditions).
- `includes/Controllers/Frontend.php` + `assets/js/popup-main.js` + `assets/css/puc-style.css` —
  frontend render + trigger/close/frequency runtime.
- `includes/Controllers/Block_Patterns.php` — starter block patterns.
- Subscribers: `includes/Models/Subscriber.php`, `includes/Data_Table/Subscribers_List_Table.php`,
  `includes/Controllers/Ajax.php`, `assets/js/popup-form.js`.
- `includes/Services/Installer.php` — table creation + `popup_id` column migration.

Known debt (from `Feature3.md`). Items 1, 2, 7, 8 were cleared on 2026-08-04.

1. ~~**Newsletter form collision.**~~ **DONE.** Forms now carry `class="arpc-subscribe-form"` on a
   wrapper with `data-arpc-popup-id`; the duplicate `id="arpc-popup-creator-wrapper"` is gone and
   `assets/js/popup-form.js` binds per form instance.
2. ~~**Subscriber validation + admin.**~~ **DONE.** Email goes through `sanitize_email()` +
   `is_email()`; bulk delete uses the correct `bulk-` + plural nonce and reuses
   `Subscriber::bulk_delete()`; the popup column shows the real popup title; every form now posts
   the owning popup's ID rather than the host page's.
3. **No analytics.** `openPopup()` and form-success are already centralized — views / opens /
   closes / conversions / conversion rate per popup is a natural extension.
4. **No preview/test mode.** Authors must publish and visit a matched page to see the result.
5. **`render_popups()` queries all published popups then filters in PHP.** Query only active
   candidates; add priority / conflict rules as popup count grows.
6. **Builder UX not consolidated.** Gutenberg patterns coexist with legacy title/subtitle/image/
   template settings. Gutenberg content is primary; metabox owns behavior, targeting, appearance only.
7. ~~**`includes/Controllers/Rest_Api.php` is dead.**~~ **DONE — deleted.** It was never
   instantiated, and the CPT's `show_in_rest` already exposes `/wp/v2/arpc_popup` natively.
8. ~~**PHPCS tooling.**~~ **DONE.** `installed_paths` registered, the renamed
   `Universal.Operators.DisallowShortTernary` sniff corrected, the i18n text domain fixed to
   `arpc-popup-creator`, and `includes/*` exempted from the `class-*.php` filename sniffs
   (it is PSR-4). Plugin-wide baseline dropped from 123 errors/127 warnings to 53/94.

Still-unresolved cleanups noticed while doing the above (not yet approved as tasks):

- `includes/Frontend/views/` duplicates `includes/Views/frontend/` and is never included by any
  code path — dead directory.
- Script/style handles such as `admin-subscriber` are unprefixed.
- `composer.json` sets `minimum-stability: dev`, so PHPCSExtra and PHP_CodeSniffer install as
  `dev-develop` / `dev-master` rather than pinned stable releases.

---

## 3. Free vs Pro split (authoritative)

Derived from the 2026 market R&D in `FEATURE_CHECKLIST-2.md`. Competitors benchmarked:
Popup Maker (700k+), OptinMonster (1M+), Convert Pro, Hustle (100k+), Popup Builder (200k+),
Icegram Engage (50k+). Most of them lock Scroll Trigger, Countdown, Slide In, and Notification
Bars behind Pro — this plugin gives all four away.

### FREE — built and advertised

**Popup types:** Modal · Slide In · Notification Bar · Fullscreen
**Triggers:** On Page Load · Delayed · Click · After Scroll 25/50/75% · After X seconds
**Display rules:** Entire Site · Specific Pages · Specific Posts · Categories · Tags · CPT support
**Design:** Gutenberg builder · drag & drop layout (via Gutenberg) · starter pattern library
(3 patterns, grow over time) · responsive controls (per-device visibility)
**Marketing:** email form integration · countdown timer · CTA buttons (Gutenberg buttons block) ·
frequency control (every time / once per period / once only)
**UX:** mobile visibility · close button · ESC close · overlay-click close

### FREE roadmap — NOT built, do NOT advertise

Video Popup · Social Proof block.

~~Floating Button Popup~~ — **DONE 2026-08-04.** `floating_button_enabled` / `_label` /
`_position` render a fixed launcher (`includes/Views/frontend/floating-button.php`) outside the
hidden popup wrapper. It opens the popup under any trigger mode and bypasses the frequency rule
via `openPopup(instance, true)`, so a dismissed popup can be re-opened. Removed at init when the
popup is hidden on the current device. Known limitation: two popups sharing a corner overlap.

~~Per-device sizing controls~~ — **DONE 2026-08-04.** `width_desktop` / `width_tablet` /
`width_mobile` emit `--arpc-width-*` custom properties; 0 inherits the next larger breakpoint.
Applies to the Box Width layout only. CSS breakpoints (1024 / 767) match `currentDevice()`.

~~Frequency period units beyond hours~~ — **DONE 2026-08-04.** "Once Per Period" now offers
minutes / hours / days / weeks / months via `Popup_Settings::period_unit_choices()`; the keys are
kept in sync with the multiplier table in `assets/js/popup-main.js`. Default remains `hour`, so
existing popups are unaffected.

### Gated as PRO but present in code

**Exit Intent** and **Inactivity** triggers are implemented but Pro-gated: shown disabled with a
"Pro" badge in the free UI and **rejected on save** (`Popup_Settings::free_trigger_modes()`
returns `click`, `load`, `scroll` only). Any new Pro-gated feature must follow this same pattern —
visible, badged, and server-side rejected. Never rely on UI-only gating.

### PRO — the paid tier

- **Advanced triggers:** Exit Intent, Inactivity, Form Submission, Purchase, WooCommerce, Custom Event
- **Advanced targeting:** Geo, Device Type, Browser, Referrer URL, UTM params, Returning Visitors,
  Logged-in Users, User Roles
- **Marketing automation:** Smart Segmentation, Lead Scoring, Multi-step Popup, Yes/No Funnel,
  Dynamic Personalization
- **Analytics:** views, conversions, conversion rate, device reports, campaign reports
- **CRO:** A/B testing, split testing, winner selection
- **WooCommerce:** cart abandonment, exit coupon, cross-sell, upsell, product-specific popups
- **Agency:** import/export, global templates, white label, multisite

### The six killer free features (marketing headline)

Modal · Slide In · Notification Bar · Scroll Trigger · Countdown Timer · Gutenberg Builder

### The ten Pro money-makers

Exit Intent · Inactivity · Geo Targeting · UTM Targeting · WooCommerce Rules · Cart Abandonment ·
Analytics · A/B Testing · Multi-step Funnel · Smart Personalization

### USP opportunities (missing from most popup plugins)

Native Gutenberg popup builder · popup scheduling calendar · heatmaps · funnel builder ·
performance score · accessibility checker · AI copy generator · AI template generator ·
analytics dashboard · conversion timeline · WooCommerce revenue attribution · version history.

---

## 4. Reference UI (`ref/`)

Five screenshots of the Divi `dnxte_popup` "Popup Pro Settings" panel. Use them as a **feature
inventory checklist only** — match capability, not layout or wording (§1.2).

| File | Shows |
| --- | --- |
| `ref/…202353.jpg` | General tab, `On Load` trigger selected |
| `ref/…202408.jpg` | General tab, `Click` trigger — reveals Manual Trigger + CSS selectors |
| `ref/…202439.jpg` | Full editor screen + Display Conditions tab |
| `ref/…202510.jpg` | Customization tab, top half (overlay, position grid, animations) |
| `ref/…202519.jpg` | Customization tab, bottom half (close-button styling) |

### General tab

Pop-up Enable toggle · Trigger Mode (Click / On Load / On Scroll / On Exit / On Inactivity) ·
Time Duration (two `sec` inputs: open delay, auto-close delay) · Periodicity (Every Time /
Once Per Period / Once Only; `Once Per Period` reveals a numeric field in `hrs`) · Activity
(Always / Certain Period; `Certain Period` reveals date-time inputs) · Disable Link ·
Close Popup on Overlay Click · Prevent Page Scrolling · Close by Clicking Back Button.

`Click` mode additionally reveals: **Manual Trigger** (auto-generated, e.g. `popup_4431`, with a
copy button), **CSS Custom Selector**, **Close Button CSS Selector**.

### Customization tab

Overlay Background Color (with reset) · Enable Overlay Blur · Overlay Z Index ·
Popup Layout Style (Box Width / Full Width) · Popup Position 3×3 matrix ·
Opening Animation Effect · Closing Animation Effect · Closing Button Position
(Top/Bottom × Left/Center/Right) · Hide Close Button · Close Button Tooltip Text ·
Tooltip Text Color · Tooltip Background Color · Place Close Button Outside ·
Close Button Icon Color · Close Button Background Color · Close Button Icon Size (px) ·
Close Button Padding (linked 4-side, px) · Close Button Margin (linked 4-side, px) ·
Close Button Border Radius (linked 4-corner, %).

Animation option lists (opening ~44 entries, closing ~29) are enumerated in
`FEATURE_CHECKLIST.md` and implemented in `Popup_Settings::opening_animation_options()` /
`closing_animation_options()`. Treat the code as canonical.

### Display Conditions tab

Specify Visibility by Role (All User, Guest, Administrator, Editor, Author, Contributor,
Subscriber, Customer, Shop manager, Web Designer — i.e. built-ins + whatever
`get_editable_roles()` returns) · Hide On Device (Mobile / Tablet / Desktop) ·
Specify Visibility by Page/Post (Include/Exclude selector + target-type selector:
Sitewide / Pages / Posts / CPTs) · row remove action · **Add New Location** repeater button.

---

## 5. Settings schema

`Popup_Settings::defaults()` is the canonical list. Keys, grouped:

- **Behavior:** `enabled`, `trigger_mode`, `scroll_depth` (25/50/75), `open_delay`,
  `auto_close_delay`, `periodicity`, `period_value`, `period_unit`, `activity_mode`,
  `activity_start`, `activity_end`, `open_selector`, `close_selector`, `disable_link`,
  `close_overlay`, `prevent_scroll`, `close_back`
- **Marketing:** `countdown_enabled`, `countdown_target`, `countdown_expire_text`
- **Type / layout:** `popup_type` (modal / slide-in / notification-bar / fullscreen),
  `bar_position`, `layout_style` (box / full), `popup_position` (9-cell grid)
- **Overlay:** `overlay_color`, `overlay_blur`, `overlay_blur_amount`, `overlay_z_index`
- **Animation:** `open_animation`, `close_animation`
- **Close button:** `close_button_position`, `hide_close_button`, `close_tooltip_text`,
  `tooltip_text_color`, `tooltip_background_color`, `close_button_outside`,
  `close_button_icon_color`, `close_button_background_color`, `close_button_icon_size`,
  `close_button_padding`, `close_button_margin`, `close_button_border_radius`
- **Targeting:** `visibility_roles`, `hide_devices`, `display_locations[]`
  (`{ mode: include|exclude, type: sitewide|<post type>, ids: [] }`)

Rules when touching the schema:

- Every new key needs a default **and** a sanitizer in the same commit. Defaults and the
  sanitize map must stay in lockstep — a key in one and not the other is a bug.
- Choice fields go through `sanitize_choice()` against an explicit allow-list. Never trust input.
- Dependent fields are reset server-side when their parent is off (as `activity_*` and `period_*`
  already are). Do not leave stale values in meta.
- Mirror only what the frontend query needs into separate meta (`arpc_active`). Do not fan the
  whole array out into individual meta rows.

---

## 6. Engineering rules

The user's global `CLAUDE.md` governs. Highlights that bite hardest here:

- **Security:** escape every output (`esc_html`/`esc_attr`/`esc_url`/`wp_kses_post`); sanitize
  every input; nonce **and** capability check on every write, AJAX handler, and REST route;
  `$wpdb->prepare()` for every query with a variable.
- **Prefixing:** everything gets `arpc_` / `ARPC\Popup\`. No generic names.
- **Assets:** always `wp_enqueue_script/style` with version + deps. No inline `<script>`/`<link>`.
- **i18n:** every user-facing string wrapped with text domain `arpc-popup-creator`.
- **Scope:** surgical diffs. Touch only what the task needs. >5 files in the plan → stop and explain.
- **Reuse first:** search before writing any new function/hook/option/helper.
- **WooCommerce work** (Pro tier): declare HPOS compatibility, use CRUD not post meta, guard with
  `class_exists()`.

### Definition of Done

- [ ] Works on current stable WordPress (+ WooCommerce where relevant); state versions targeted.
- [ ] PHPCS (WordPress standard): 0 errors, 0 warnings.
- [ ] Plugin Check (PCP): 0 errors; warnings fixed or justified.
- [ ] PHPStan clean at the configured level.
- [ ] PHPUnit passes, or state that no tests exist.
- [ ] `caveman-review` run; findings fixed or justified.
- [ ] Output escaped, input sanitized, writes nonce- and capability-checked.
- [ ] Strings translatable with the right text domain.
- [ ] No PHP notices/warnings in `debug.log` with `WP_DEBUG` on.
- [ ] Pro-gated features rejected server-side, not just hidden in the UI.

Never report a check as passing unless it actually ran. "Not Run" with a reason is acceptable;
a fabricated pass is not.

### Tooling

```bash
composer install          # installs WPCS
composer phpcs            # or: composer lint
composer phpcbf           # or: composer format
pnpm install              # JS deps — pnpm, not npm/yarn
```

Verification: the local site runs under Local (`playground` site). Drive the admin at
`/wp-admin/post-new.php?post_type=arpc_popup` in a real browser (Playwright) for any change
that touches admin UI or frontend output.

---

## 7. How to use this file

Starting a task:

1. Read this file. Read the relevant section of `Popup_Settings.php` — code beats docs.
2. Restate the task in 1–3 lines, list assumptions, ask if a load-bearing one is uncertain.
3. Check §3 — is this Free, Free-roadmap, or Pro? Gate accordingly.
4. Check §2 debt list — is the thing you're about to build sitting on a known bug?
5. Output a numbered plan with the exact file list before editing.
6. Implement surgically, then walk the Definition of Done and report pass/fail per item.

Source files consolidated here: `README.md` (tooling), `FEATURE_CHECKLIST.md` (reference UI
inventory + animation lists), `FEATURE_CHECKLIST-2.md` (market R&D + free/pro split),
`Feature3.md` (open debt), `ref/*.jpg` (reference screenshots), `notes.txt` (scratch).
