# Training ACF Summary: Custom WordPress Theme Implementation (`index-digital`)

This document lists the architectural structure, custom database models, dynamic template designs, and theme configurations implemented for the **`index-digital`** WordPress theme inside the **`training-acf`** Local WP instance.

---

## 1. Core Objective & Architecture
The project translates the static HTML/CSS/JS mockups from the Figma export ([`vfinale/`](file:///c:/Users/anoua/OneDrive/Desktop/top-digital-agencies/vfinale)) into a high-performance, dynamic WordPress theme.
* **Performance First**: Built as a classic PHP-based theme. It completely avoids page builders (like Elementor or Divi) to achieve the target 98/100 Core Web Vitals score.
* **Flexible Page Builder**: Implemented using **ACF Pro's Flexible Content** engine. This allows administrators to construct the front page using custom layout blocks directly from the WordPress editor.
* **Theme Automation**: Features an initialization script that fully builds, seeds, and configures the database and navigation menus upon theme activation.

---

## 2. Directory & File Structure
All custom theme files are developed within the local theme path:  
[`/wp-content/themes/index-digital/`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/)

* **Core Templates**:
  * [`style.css`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/style.css): Holds theme declarations and metadata.
  * [`functions.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/functions.php): Custom Post Types, Taxonomies, ACF SDK registrations, and theme activation hook.
  * [`header.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/header.php): Wordmark, global navigation layout, search toggle, and language toggle header.
  * [`footer.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/footer.php): Bottom menu columns, interactive matching wizard overlay, and command palette.
  * [`front-page.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/front-page.php): Static front-page router that loops over and displays ACF layout blocks.
  * [`page.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/page.php): Generic fallback routing structure for interior content.
* **Asset Bundling**:
  * [`assets/css/theme-styles.css`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/assets/css/theme-styles.css): Complete custom typography, color tokens, and layout styles.
  * [`assets/js/theme-scripts.js`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/assets/js/theme-scripts.js): Interactivity scripts, search indexing, wizard validation, and page routing logic.
* **Layout Layouts (`/template-parts/layouts/`)**:
  * [`hero.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/template-parts/layouts/hero.php): Top section header, action links, and matchmaking triggers.
  * [`logos_band.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/template-parts/layouts/logos_band.php): Banner displaying client partner wordmarks.
  * [`challenge.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/template-parts/layouts/challenge.php): Cards highlighting user pain points ("Sound Familiar").
  * [`feature_row.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/template-parts/layouts/feature_row.php): Multi-module layout for data telemetry, directory ranking lists, and slider inputs.
  * [`stats_band.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/template-parts/layouts/stats_band.php): Highlight band containing stats counter cards.
  * [`compliance.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/template-parts/layouts/compliance.php): Cards outlining compliance verification criteria.
  * [`specialties.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/template-parts/layouts/specialties.php): Service-specific categories grid with deep links.
  * [`footer_cta.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/template-parts/layouts/footer_cta.php): Bottom panel brief CTA link container.

---

## 3. Custom Database Models & Post Types
To avoid raw hardcoded values, specific CPTs and taxonomies were registered in `functions.php` to serve content dynamically:

### Custom Post Types (CPTs)
1. **`partner_logo` (Partner Logos)**: Represents trusted companies utilizing the directory.
   * *Seeded items*: `nestle`, `google`, `hyundai`, `l'oreal`, `volvo`, `samsung`.
2. **`stat_metric` (Stat Metrics)**: Highlights directory performance and numbers.
   * *Seeded items*: 148 Audited Agencies, 4,200+ Successful Matches, 0.82s Average Load Speed, 100% Independent Scores.
3. **`specialty_hub` (Specialty Hubs)**: Represents service category directory indexes.
   * *Custom fields (ACF)*: `icon_svg` (raw SVG string), `direct_link_parameter` (query filter slug), and `sub_services` (repeater sub-field list).
4. **`agency` (Agencies)**: The core content type for listings. 
   * *Custom fields (ACF)*: `logo_text` (Initials logo style), `logo_image` (custom file array upload), `rating_value` (float value), `review_count` (total reviews), and `agency_rank` (listing placement).
   * *Seeded items*: 
     * **RMD Marketing** (Rank #1, Rating 4.9, 18 reviews, city: Paris, service: SEO/PPC).
     * **Pixagram** (Rank #2, Rating 4.8, 14 reviews, cities: Paris/Lyon, service: Social/Branding).

### Custom Taxonomies
* **`agency_service` (Services)**: Hierarchical directory indexing. Seeded terms: `SEO`, `PPC`, `Social Media`, `Branding & Design`, and `Tech & Web dev`.
* **`agency_city` (Cities)**: Location directory indexing. Seeded terms: `Paris`, `Lyon`, and `Casablanca`.

---

## 4. ACF Local Field Groups Registration
Instead of saving configurations to JSON files that need manual import, all fields are registered programmatically in `functions.php` using ACF's PHP SDK API:
* **`group_stat_fields`**: Hooks into the `stat_metric` post type editor, adding numerical values and text labels.
* **`group_specialty_fields`**: Hooks into the `specialty_hub` post type editor, adding icons, deep link mapping, and service arrays.
* **`group_agency_fields`**: Hooks into the `agency` post type editor to store ratings, reviewer metrics, ranks, and custom logos.
* **`group_homepage_fields`**: Hooks into page editors (type: `page`) to render the full range of flexible layouts (Hero, Logos, Pain Points, Features, Stats, Compliance, Specialties, Footer CTAs).

---

## 5. Theme Switch Automation Hook
A specialized setup routine hooks into the theme activation event (`after_switch_theme`):
1. **Creates Pages**: Automatically inserts standard pages (`Home`, `Directory`, `Blog`, `Methodology`, and `Contact`) if they do not exist.
2. **Assigns Homepage**: Sets the newly generated `Home` page as the static Front Page of the WordPress site.
3. **Sets Up Menus**: Creates a menu named "Primary Navigation", populates it with lowercased links to all five pages, and assigns it to the theme's `primary` navigation menu slot.
4. **Seeds Taxonomies & CPTs**: Inserts categories, cities, logos, metrics, specialty items, and agency posts.
5. **Populates Page Layouts**: Programmatically fills the `page_layouts` field for the Home page with a complete set of layout arrays and seeding content. This ensures the site renders **immediately** on theme activation without needing any manual dashboard assembly.

---

## 6. Dynamic Navigation & Header Menu Filtering
To preserve the design system styling and active indicators from the Figma mockups, navigation links are filtered dynamically rather than hardcoded:
* **Standard WP Menu Integration**: The header layout in [`header.php`](file:///c:/Users/anoua/Local%20Sites/training-acf/app/public/wp-content/themes/index-digital/header.php) calls `wp_nav_menu()` for the `primary` navigation theme location.
* **Attributes Filtering Hook**: Custom logic in `functions.php` filters the link attributes via the `nav_menu_link_attributes` hook.
  * **Class Injection**: Appends the `.nav-link` CSS class to each dynamic menu list item's anchor tag to match global stylesheet styles.
  * **Active State Routing**: Evaluates native WordPress menu item classes (`current-menu-item`, `current-menu-ancestor`, etc.) and appends the `.active` class to the current active menu item, highlighting the current page name dynamically.
* **Smart HTML Fallback**: Implements the `index_digital_fallback_menu` callback to render structured mock markup matching the original theme layout when no custom menu is defined in the WordPress admin panel.

