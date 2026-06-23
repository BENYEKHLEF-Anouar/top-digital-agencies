# Custom Post Types (CPT)

> Related: [[WordPress - ACF Flexible Content]] · [[WordPress - WP-CLI]]

---

## What is a Custom Post Type?

WordPress has two built-in content types:
- **Posts** — blog articles
- **Pages** — static pages like "About Us" or "Contact"

A **Custom Post Type (CPT)** lets you create your own content type tailored to your project — for example: `Portfolio`, `Team Members`, `Testimonials`, `Services`, `FAQ`.

Think of it like a filing cabinet. The default drawers are "Posts" and "Pages". CPT lets you add new drawers with their own names and rules.

---

## Why Use It?

Without CPT, you'd force everything into Posts or Pages — which gets messy fast.

| Without CPT | With CPT |
|---|---|
| "Post" for a portfolio item | Dedicated "Portfolio" post type |
| "Post" for a team member | Dedicated "Team Members" post type |
| Hard to filter or query separately | Each type is cleanly separated |
| Cluttered admin | Each CPT gets its own admin menu |

---

## How to Create a CPT

### Option 1 — ACF Pro (Recommended if you have it)

Since **ACF Pro v6.1**, you can create CPTs directly inside ACF — no extra plugin needed.

1. Go to **ACF > Post Types > Add New**
2. Fill in the **Slug** (e.g. `portfolio`)
3. Fill in the **Singular Name** and **Plural Name** (e.g. "Portfolio Item" / "Portfolio Items")
4. Set visibility, supports, and save

**Why this is the best option:** ACF manages your CPTs and field groups together. When you export your ACF settings as JSON (for moving between local and production), the CPT definitions are included automatically.

---

### Option 2 — CPT UI Plugin (If you don't have ACF Pro)

Install the free plugin **Custom Post Type UI**.

1. Install → Activate
2. Go to **CPT UI > Add/Edit Post Types**
3. Fill in the slug, labels, and settings
4. Save

---

### Option 3 — Code in functions.php

For developers who prefer not to use a plugin:

```php
function register_portfolio_cpt() {
    $args = array(
        'public'    => true,
        'label'     => 'Portfolio',
        'menu_icon' => 'dashicons-portfolio',
        'supports'  => array('title', 'editor', 'thumbnail'),
    );
    register_post_type('portfolio', $args);
}
add_action('init', 'register_portfolio_cpt');
```

Add this to your theme's `functions.php` or a custom plugin file.

---

## Key Concepts

| Term | Meaning |
|---|---|
| **Slug** | The URL-safe ID, e.g. `portfolio` → `yoursite.com/portfolio/` |
| **Supports** | Features it has: `title`, `editor`, `thumbnail`, `excerpt`, `custom-fields`… |
| **Public** | Whether it appears on the front-end and in search |
| **Has Archive** | Whether it gets a listing page at `yoursite.com/portfolio/` |
| **Menu Icon** | The dashicon shown in the WordPress admin sidebar |
| **Taxonomies** | The categories/tags system for this CPT |

---

## Custom Taxonomies

Just like CPTs are custom content types, **Custom Taxonomies** are custom categories or tags for your CPTs.

**Example:**
- CPT: `Portfolio`
- Custom Taxonomy: `Service Type`
- Terms: "Branding", "Web Design", "Photography"

This lets you filter portfolio items by service type on the front-end.

### How to create a Custom Taxonomy

- **ACF Pro** → **ACF > Taxonomies > Add New**
- **CPT UI plugin** → **CPT UI > Add/Edit Taxonomies**
- **Code** → `register_taxonomy()` in functions.php

---

## Quick Reference

| What | How |
|---|---|
| Create CPT | ACF Pro > Post Types > Add New (v6.1+) |
| Create CPT (no ACF Pro) | CPT UI plugin |
| Create CPT (code) | `register_post_type()` in functions.php |
| Create Taxonomy | ACF Pro > Taxonomies, or CPT UI, or `register_taxonomy()` |
| Add CPT to a URL | Set `'has_archive' => true` and `'public' => true` |

---

*Last updated: June 2026*
