I read the plugin. Best current feature improvement: make the existing popup + subscriber flow reliable before adding more
  popup types.

  Highest Priority

  1. Fix built-in newsletter form handling. Multiple forms currently share id="arpc-popup-creator-wrapper", and JS targets the
     first submit button globally, so multiple popups/shortcodes can break each other. See includes/Views/frontend/newsletter-
     form.php:26 and assets/js/popup-form.js:4.

  2. Improve subscriber validation and admin usefulness. Email is sanitized as text, not email, and subscriber admin still has
     placeholders like Hello World for popup attribution and broken bulk delete paths. See includes/Controllers/Ajax.php:41 and
     includes/Data_Table/Subscribers_List_Table.php:219.

  3. Add lightweight analytics: views, opens, closes, conversions, conversion rate per popup. The frontend already centralizes
     openPopup() and form success handling, so this is a natural extension. See assets/js/popup-main.js:107 and assets/js/
     popup-form.js:26.

  Next Best Improvements
  4. Add a popup preview/test mode from the editor. The metabox has many controls, but users need to see the result without
  publishing and visiting a matched frontend page.

  5. Optimize frontend popup loading. render_popups() queries all published popups and filters afterward. As popup count grows,
     query only active candidates and add priority/conflict rules. See includes/Controllers/Frontend.php:39.

  6. Consolidate builder UX. You have Gutenberg patterns plus legacy/custom title, subtitle, image, template settings. The
     product would feel cleaner if Gutenberg content became the primary builder and the metabox focused on behavior, targeting,
     and appearance.

  7. Wire or remove dead REST code. Rest_Api exists but is not instantiated, so its endpoint is inactive. See includes/
     Controllers/Rest_Api.php:10.

  Also, composer run phpcs currently fails because PHPCS does not know about the installed WPCS standards. Fixing the
  installed_paths setup should come before larger refactors so quality checks are dependable.

