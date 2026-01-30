# Trend Today – Translation files

- **trendtoday.pot** – Template of translatable strings (source for translators).
- **trendtoday-en_US.po** – English (US) translations (source for compiling).
- **en_US.mo** – Compiled English translations. **WordPress loads this file** for themes (it looks for `{locale}.mo`, e.g. `en_US.mo`, not `trendtoday-en_US.mo`).

## Regenerating from theme root

1. **Update .pot** (after adding/changing strings in PHP):
   ```bash
   php makepot.php
   ```

2. **Update English .po** from .pot (fills Thai→English map):
   ```bash
   php make-en-po.php
   ```

3. **Compile .po to .mo** (creates `languages/en_US.mo`):
   ```bash
   php po2mo.php
   ```

Or with WP-CLI: `wp i18n make-pot . languages/trendtoday.pot --domain=trendtoday`

## Switching site language

In **Settings → General**, set **Site Language** to **English** to use the English translations. The theme loads from `load_theme_textdomain( 'trendtoday', get_template_directory() . '/languages' );` in `inc/theme-setup.php`.
