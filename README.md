# via-adlucem-wp

Code for [via-adlucem.com](https://via-adlucem.com): an Archicon-inspired architecture-studio site built on WordPress with Elementor, Polylang (EN default, AZ, UZ) and Contact Form 7.

## Layout

| Path | What it is |
|---|---|
| `wp-content/themes/via-adlucem/` | Child theme of **Hello Elementor**: header, footer, styles, scripts, custom Elementor widgets |
| `wp-content/mu-plugins/va-deploy.php` | Deploys this repository onto the server (`va_deploy( $ref )` / `wp va deploy --ref=main`) |
| `content/i18n/{en,az,uz}.php` | Sample content per language used by the seed script |
| `content/placeholders/` | Placeholder images in the brand palette (replace in the Media Library) |
| `tools/seed.php` | One-time setup: languages, Elementor kit, forms, pages, menus, Polylang strings |

## Theme

- Font: Space Grotesk. Colours: brand palette (navy `#003695`, orange `#FDAD34`, greys `#E6E5E3`…`#121110`) as CSS variables in `assets/css/main.css` and as Elementor global colours.
- Custom Elementor widgets (category **Via Adlucem**): **VA Hero Slider**, **VA Projects Slider**, **VA Accordion**.
- Helper CSS classes for Elementor (Advanced → CSS Classes):
  - containers: `va-section`, `va-section--tight`, `va-section--surface`, `va-section--dark`, `va-page-banner`, `va-services`, `va-counters`
  - widgets: `va-label` (small caps label with orange dot), `va-statement`, `va-statement--xl`, `va-lead`, `va-btn-outline`, `va-image-tall`, `va-reveal` (fade-up on scroll)
- Header/footer texts are Polylang strings: **Languages → Translations → group "Via Adlucem"**.
- Social links: **Appearance → Customize → Via Adlucem — social links**.

## Deploying

Every change is committed here first, then pulled onto the live site:

```php
va_deploy( '<branch-or-commit>' ); // run on the server (Novamira execute-php or `wp va deploy --ref=...`)
```

The deploy syntax-checks all PHP before installing and swaps folders atomically.

Page content made in Elementor lives in the database; exports are kept in `content/exports/`.
