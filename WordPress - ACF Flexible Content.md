# ACF Flexible Content

> Related: [[WordPress - Custom Post Types (CPT)]] · [[WordPress - WP-CLI]]

---

## What is ACF?

**ACF = Advanced Custom Fields**

It's a WordPress plugin that lets you add extra fields to any post, page, or Custom Post Type — beyond just the title and the text editor.

**Example:** On a "Team Member" CPT, you might want:
- A profile photo
- A job title
- A short bio
- Social media links

ACF lets you define exactly those fields. They show up as a clean form inside the WordPress admin when editing that post type.

> **Free vs Pro:** The free version covers most field types. **ACF Pro** adds Flexible Content, Repeater, Gallery, and the ability to create CPTs/Taxonomies directly.

---

## What is Flexible Content?

**Flexible Content** is ACF Pro's most powerful field type.

Instead of a fixed set of fields on every page, it lets you build a **page builder-style system** from reusable content blocks — where the editor picks which blocks to use and in what order, per page.

Think of it like **LEGO**: you design a set of block types, and the editor assembles them however they need.

**Example block types you could create:**
- Hero section (heading + subtitle + button + background image)
- Text + Image side by side
- Testimonials slider
- FAQ accordion
- Stats row (numbers with labels)
- Call to Action banner

The editor can then build any page like this:

```
[Hero]
[Text + Image]
[Stats Row]
[Testimonials]
[Call to Action]
[Text + Image]   ← same block, different content
```

Every block can be reordered, repeated, or removed — without touching the theme code.

---

## How to Set It Up

### Step 1 — Install ACF Pro

Flexible Content is a Pro-only feature. Purchase and install **ACF Pro** from advancedcustomfields.com.

---

### Step 2 — Create a Field Group

A **Field Group** is a container that holds your fields and defines where they appear.

1. Go to **ACF > Field Groups > Add New**
2. Give it a name (e.g. "Page Builder")
3. Set the **Location Rule**: where should this field group appear?
   - e.g. "Show this field group if **Post Type** is equal to **Page**"

---

### Step 3 — Add a Flexible Content Field

Inside your field group:
1. Click **Add Field**
2. Field Type → **Flexible Content**
3. Give it a **Field Name** (e.g. `page_builder`) — this is what you'll reference in your theme code

---

### Step 4 — Add Layouts

Each **Layout** = one type of content block.

Click **Add Layout** and for each one define:
- A **Label** (shown to the editor, e.g. "Hero Section")
- A **Name** (used in code, e.g. `hero_section`)
- Its own **Sub-Fields** (e.g. `heading`, `subheading`, `button_text`, `button_url`, `background_image`)

Repeat for each block type. Example set of layouts:

| Layout Label | Layout Name | Sub-Fields |
|---|---|---|
| Hero Section | `hero_section` | heading, subheading, button_text, button_url, background_image |
| Text + Image | `text_image` | heading, body_text, image, image_position (left/right) |
| Testimonials | `testimonials` | title, items (repeater: quote, author, photo) |
| Stats Row | `stats_row` | items (repeater: number, label) |
| Call to Action | `cta` | heading, text, button_text, button_url, background_color |

---

### Step 5 — Display It in Your Theme

In your template file (e.g. `page.php` or `single-portfolio.php`), loop through the layouts:

```php
if ( have_rows('page_builder') ) :
    while ( have_rows('page_builder') ) : the_row();

        if ( get_row_layout() == 'hero_section' ) :
            $heading    = get_sub_field('heading');
            $subheading = get_sub_field('subheading');
            $btn_text   = get_sub_field('button_text');
            $btn_url    = get_sub_field('button_url');
            $bg_image   = get_sub_field('background_image');
            get_template_part('template-parts/hero');

        elseif ( get_row_layout() == 'text_image' ) :
            get_template_part('template-parts/text-image');

        elseif ( get_row_layout() == 'cta' ) :
            get_template_part('template-parts/cta');

        endif;

    endwhile;
endif;
```

Each `template-parts/` file handles the HTML for that block, using `get_sub_field()` to pull the field values.

---

## ACF Field Types Overview

| Field Type | What it stores | Notes |
|---|---|---|
| Text | Single line of text | Most common |
| Textarea | Multiple lines of text | For longer content |
| Wysiwyg | Rich text editor | Full formatting options |
| Image | An image | Returns ID, URL, or array |
| Gallery | Multiple images | Returns array |
| File | Any file upload | PDF, video, etc. |
| True/False | A checkbox toggle | Great for show/hide options |
| Select | A dropdown | Define the options in ACF |
| Radio Button | Choose one from a list | Visual alternative to Select |
| Checkbox | Choose multiple from a list | Returns array |
| Color Picker | A color value | Returns hex code |
| **Repeater** | A repeatable group of sub-fields | Pro only |
| **Flexible Content** | Multiple layout blocks, each with its own fields | Pro only |
| Relationship | Links to other posts/pages | Returns array of post IDs |
| Post Object | Select a specific post | Returns post object |
| Link | A URL with label and target | Cleaner than a text field |
| Google Map | A map location | Returns lat/lng array |
| Date Picker | A date | Returns formatted string |
| Number | A numeric value | Can set min/max |

---

## ACF + Elementor

If you're using Elementor Pro, you can display ACF field values directly in your Elementor designs without writing PHP.

### How it works

1. In Elementor, add any widget (e.g. Heading, Image, Text Editor)
2. Click the **dynamic icon** (the database icon) next to the content field
3. Choose **ACF Field** from the list
4. Select your field name

Elementor pulls the value from ACF for the current post/page automatically.

### What this means for your workflow

- Design the layout in **Elementor** (visual, no code)
- Manage the content through **ACF fields** (structured, clean)
- Each post/page can have different content while using the same Elementor template

---

## ACF JSON Sync (Important for Production Workflows)

ACF can save your field group definitions as **JSON files** in your theme folder.

This means:
- Your ACF settings are version-controlled with your theme (via git)
- Moving from local to production is as easy as uploading the theme folder
- You won't lose your field group setup if the database is wiped

**Enable it:** ACF saves JSON automatically to `/wp-content/themes/your-theme/acf-json/` — just make sure that folder exists and is writable.

---

## Quick Reference

| What | How |
|---|---|
| Requires | ACF Pro |
| Create field group | ACF > Field Groups > Add New |
| Add Flexible Content field | Add Field > Field Type > Flexible Content |
| Add a layout block | Inside Flexible Content > Add Layout |
| Display in theme | `have_rows()` + `get_row_layout()` + `get_sub_field()` |
| Use with Elementor | Elementor Pro > Dynamic Tags > ACF Field |
| Sync settings as files | `/acf-json/` folder in your theme |
| Create CPTs in ACF Pro | ACF > Post Types > Add New (v6.1+) |

---

*Last updated: June 2026*
