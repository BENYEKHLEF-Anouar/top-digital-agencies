<?php
/**
 * Theme Functions and Definitions
 * Text Domain: top-digital-agencies
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/* ==========================================================================
   1. Theme Setup and Enqueue
   ========================================================================== */

function tda_theme_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Register Navigation Menus
    register_nav_menus( array(
        'primary' => __( 'Primary Navigation', 'top-digital-agencies' ),
        'footer'  => __( 'Footer Navigation', 'top-digital-agencies' ),
    ) );

    // Switch default core markup for search form, comment form, and comments to output valid HTML5.
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );
}
add_action( 'after_setup_theme', 'tda_theme_setup' );

/**
 * Enqueue scripts and styles.
 */
function tda_theme_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style( 'tda-fonts', 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Geist:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap', array(), null );

    // Enqueue Tailwind via CDN (for simplicity & flexibility in editing)
    wp_enqueue_script( 'tda-tailwind', 'https://cdn.tailwindcss.com', array(), null, false );

    // Enqueue Lucide Icons
    wp_enqueue_script( 'tda-lucide', 'https://unpkg.com/lucide@latest', array(), null, true );

    // Enqueue GSAP for Premium Motion Mechanics
    wp_enqueue_script( 'tda-gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), null, true );
    wp_enqueue_script( 'tda-gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array( 'tda-gsap' ), null, true );

    // Enqueue Custom Styles
    wp_enqueue_style( 'tda-styles', get_template_directory_uri() . '/assets/css/theme-styles.css', array(), '1.0.0' );

    // Enqueue Custom Scripts
    wp_enqueue_script( 'tda-scripts', get_template_directory_uri() . '/assets/js/theme-scripts.js', array(), '1.0.0', true );

    // Serialize and Inject Dynamic Agency Data for client-side search (High Performance / 0ms lag)
    $agency_data = tda_get_serialized_agency_data();
    wp_localize_script( 'tda-scripts', 'AGENCY_DATA', $agency_data );

    // Inline Tailwind configurations matching v3_2 prototype
    wp_add_inline_script( 'tda-tailwind', "
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Geist', 'system-ui', 'sans-serif'],
                        display: ['Space Grotesk', 'system-ui', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        }
                    }
                }
            }
        }
    " );
}
add_action( 'wp_enqueue_scripts', 'tda_theme_scripts' );


/* ==========================================================================
   2. Custom Post Types & Custom Taxonomies Registration
   ========================================================================== */

function tda_register_custom_post_types_and_taxonomies() {
    
    // Custom Taxonomy: Services (agency_service)
    register_taxonomy( 'agency_service', array( 'agency' ), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => _x( 'Services', 'taxonomy general name', 'top-digital-agencies' ),
            'singular_name'     => _x( 'Service', 'taxonomy singular name', 'top-digital-agencies' ),
            'search_items'      => __( 'Search Services', 'top-digital-agencies' ),
            'all_items'         => __( 'All Services', 'top-digital-agencies' ),
            'parent_item'       => __( 'Parent Service', 'top-digital-agencies' ),
            'parent_item_colon' => __( 'Parent Service:', 'top-digital-agencies' ),
            'edit_item'         => __( 'Edit Service', 'top-digital-agencies' ),
            'update_item'       => __( 'Update Service', 'top-digital-agencies' ),
            'add_new_item'      => __( 'Add New Service', 'top-digital-agencies' ),
            'new_item_name'     => __( 'New Service Name', 'top-digital-agencies' ),
            'menu_name'         => __( 'Services', 'top-digital-agencies' ),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'agency-service' ),
        'show_in_rest'      => true,
    ) );

    // Custom Taxonomy: Cities (agency_city)
    register_taxonomy( 'agency_city', array( 'agency' ), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => _x( 'Cities', 'taxonomy general name', 'top-digital-agencies' ),
            'singular_name'     => _x( 'City', 'taxonomy singular name', 'top-digital-agencies' ),
            'search_items'      => __( 'Search Cities', 'top-digital-agencies' ),
            'all_items'         => __( 'All Cities', 'top-digital-agencies' ),
            'edit_item'         => __( 'Edit City', 'top-digital-agencies' ),
            'update_item'       => __( 'Update City', 'top-digital-agencies' ),
            'add_new_item'      => __( 'Add New City', 'top-digital-agencies' ),
            'new_item_name'     => __( 'New City Name', 'top-digital-agencies' ),
            'menu_name'         => __( 'Cities', 'top-digital-agencies' ),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'agency-city' ),
        'show_in_rest'      => true,
    ) );

    // CPT: Agency (agency)
    register_post_type( 'agency', array(
        'labels'             => array(
            'name'               => _x( 'Agencies', 'post type general name', 'top-digital-agencies' ),
            'singular_name'      => _x( 'Agency', 'post type singular name', 'top-digital-agencies' ),
            'menu_name'          => _x( 'Agencies', 'admin menu', 'top-digital-agencies' ),
            'name_admin_bar'     => _x( 'Agency', 'add new on admin bar', 'top-digital-agencies' ),
            'add_new'            => _x( 'Add New', 'agency', 'top-digital-agencies' ),
            'add_new_item'       => __( 'Add New Agency', 'top-digital-agencies' ),
            'new_item'           => __( 'New Agency', 'top-digital-agencies' ),
            'edit_item'          => __( 'Edit Agency', 'top-digital-agencies' ),
            'view_item'          => __( 'View Agency', 'top-digital-agencies' ),
            'all_items'          => __( 'All Agencies', 'top-digital-agencies' ),
            'search_items'       => __( 'Search Agencies', 'top-digital-agencies' ),
            'not_found'          => __( 'No agencies found.', 'top-digital-agencies' ),
            'not_found_in_trash' => __( 'No agencies found in Trash.', 'top-digital-agencies' ),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'agency' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-businessperson',
        'supports'           => array( 'title', 'excerpt', 'thumbnail' ),
        'show_in_rest'       => true,
    ) );

    // CPT: Specialty Hub (specialty_hub)
    register_post_type( 'specialty_hub', array(
        'labels'             => array(
            'name'               => _x( 'Specialties', 'post type general name', 'top-digital-agencies' ),
            'singular_name'      => _x( 'Specialty Hub', 'post type singular name', 'top-digital-agencies' ),
            'menu_name'          => _x( 'Specialties', 'admin menu', 'top-digital-agencies' ),
            'add_new_item'       => __( 'Add New Specialty Hub', 'top-digital-agencies' ),
            'edit_item'          => __( 'Edit Specialty Hub', 'top-digital-agencies' ),
            'view_item'          => __( 'View Specialty Hub', 'top-digital-agencies' ),
            'all_items'          => __( 'All Specialty Hubs', 'top-digital-agencies' ),
        ),
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'rewrite'            => array( 'slug' => 'specialty' ),
        'menu_icon'          => 'dashicons-awards',
        'supports'           => array( 'title' ),
        'show_in_rest'       => true,
    ) );

    // CPT: Stat Metric (stat_metric)
    register_post_type( 'stat_metric', array(
        'labels'             => array(
            'name'               => _x( 'Stat Metrics', 'post type general name', 'top-digital-agencies' ),
            'singular_name'      => _x( 'Stat Metric', 'post type singular name', 'top-digital-agencies' ),
            'menu_name'          => _x( 'Stat Metrics', 'admin menu', 'top-digital-agencies' ),
            'add_new_item'       => __( 'Add New Stat Metric', 'top-digital-agencies' ),
        ),
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-chart-bar',
        'supports'           => array( 'title' ),
        'show_in_rest'       => true,
    ) );

}
add_action( 'init', 'tda_register_custom_post_types_and_taxonomies' );


/* ==========================================================================
   3. Programmatic ACF Configuration (ACF PHP SDK)
   ========================================================================== */

function tda_register_acf_field_groups() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    // 3.1. Field Group: Stat Metric Fields
    acf_add_local_field_group( array(
        'key' => 'group_stat_fields',
        'title' => __( 'Stat Metric Fields', 'top-digital-agencies' ),
        'fields' => array(
            array(
                'key' => 'field_stat_number',
                'label' => __( 'Stat Number/Value', 'top-digital-agencies' ),
                'name' => 'stat_number',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_stat_label',
                'label' => __( 'Stat Label', 'top-digital-agencies' ),
                'name' => 'stat_label',
                'type' => 'text',
                'required' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'stat_metric',
                ),
            ),
        ),
    ) );

    // 3.2. Field Group: Specialty Hub Fields
    acf_add_local_field_group( array(
        'key' => 'group_specialty_fields',
        'title' => __( 'Specialty Hub Fields', 'top-digital-agencies' ),
        'fields' => array(
            array(
                'key' => 'field_icon_svg',
                'label' => __( 'Icon SVG String', 'top-digital-agencies' ),
                'name' => 'icon_svg',
                'type' => 'textarea',
                'instructions' => __( 'Paste raw XML/SVG content or Lucide icon parameters.', 'top-digital-agencies' ),
                'rows' => 4,
            ),
            array(
                'key' => 'field_direct_link_parameter',
                'label' => __( 'Direct Service Filter Parameter', 'top-digital-agencies' ),
                'name' => 'direct_link_parameter',
                'type' => 'text',
                'instructions' => __( 'Slugs used in the filters, e.g. "seo" or "paid-ads".', 'top-digital-agencies' ),
            ),
            array(
                'key' => 'field_sub_services',
                'label' => __( 'Sub-Services List', 'top-digital-agencies' ),
                'name' => 'sub_services',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __( 'Add Sub-Service', 'top-digital-agencies' ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_sub_service_name',
                        'label' => __( 'Service Name', 'top-digital-agencies' ),
                        'name' => 'service_name',
                        'type' => 'text',
                        'required' => 1,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'specialty_hub',
                ),
            ),
        ),
    ) );

    // 3.3. Field Group: Agency Fields
    acf_add_local_field_group( array(
        'key' => 'group_agency_fields',
        'title' => __( 'Agency Information', 'top-digital-agencies' ),
        'fields' => array(
            array(
                'key' => 'field_logo_text',
                'label' => __( 'Logo Initials / Text fallback', 'top-digital-agencies' ),
                'name' => 'logo_text',
                'type' => 'text',
            ),
            array(
                'key' => 'field_logo_image',
                'label' => __( 'Logo Image File', 'top-digital-agencies' ),
                'name' => 'logo_image',
                'type' => 'image',
                'return_format' => 'url',
            ),
            array(
                'key' => 'field_rating_value',
                'label' => __( 'Rating (Decimal)', 'top-digital-agencies' ),
                'name' => 'rating_value',
                'type' => 'text',
                'default_value' => '4.5',
            ),
            array(
                'key' => 'field_review_count',
                'label' => __( 'Review Count', 'top-digital-agencies' ),
                'name' => 'review_count',
                'type' => 'text',
                'default_value' => '10',
            ),
            array(
                'key' => 'field_agency_rank',
                'label' => __( 'Agency Rank', 'top-digital-agencies' ),
                'name' => 'agency_rank',
                'type' => 'text',
            ),
            array(
                'key' => 'field_budget',
                'label' => __( 'Minimum Monthly Budget', 'top-digital-agencies' ),
                'name' => 'budget',
                'type' => 'text',
                'default_value' => '10,000 MAD/mo',
            ),
            array(
                'key' => 'field_project',
                'label' => __( 'Average Project Range', 'top-digital-agencies' ),
                'name' => 'project',
                'type' => 'text',
                'default_value' => '30K-100K MAD',
            ),
            array(
                'key' => 'field_team_size',
                'label' => __( 'Team Size Count', 'top-digital-agencies' ),
                'name' => 'team_size',
                'type' => 'text',
                'default_value' => '10-25',
            ),
            array(
                'key' => 'field_clients_served',
                'label' => __( 'Clients Served', 'top-digital-agencies' ),
                'name' => 'clients_served',
                'type' => 'text',
                'default_value' => '50+',
            ),
            array(
                'key' => 'field_founded',
                'label' => __( 'Year Founded', 'top-digital-agencies' ),
                'name' => 'founded',
                'type' => 'text',
                'default_value' => '2020',
            ),
            array(
                'key' => 'field_address',
                'label' => __( 'Full Physical Address', 'top-digital-agencies' ),
                'name' => 'address',
                'type' => 'text',
            ),
            array(
                'key' => 'field_email',
                'label' => __( 'Contact Email', 'top-digital-agencies' ),
                'name' => 'email',
                'type' => 'email',
            ),
            array(
                'key' => 'field_phone',
                'label' => __( 'Contact Phone', 'top-digital-agencies' ),
                'name' => 'phone',
                'type' => 'text',
            ),
            array(
                'key' => 'field_website',
                'label' => __( 'Website Link', 'top-digital-agencies' ),
                'name' => 'website',
                'type' => 'text',
            ),
            // Telemetry Fields
            array(
                'key' => 'field_pagespeed_score',
                'label' => __( 'PageSpeed Score', 'top-digital-agencies' ),
                'name' => 'pagespeed_score',
                'type' => 'number',
                'min' => 0,
                'max' => 100,
                'default_value' => 90,
            ),
            array(
                'key' => 'field_core_web_vitals',
                'label' => __( 'Core Web Vitals Status', 'top-digital-agencies' ),
                'name' => 'core_web_vitals',
                'type' => 'select',
                'choices' => array(
                    'PASS' => 'PASS',
                    'FAIL' => 'FAIL',
                ),
                'default_value' => 'PASS',
            ),
            array(
                'key' => 'field_code_cleanliness',
                'label' => __( 'Code Cleanliness Index %', 'top-digital-agencies' ),
                'name' => 'code_cleanliness',
                'type' => 'number',
                'min' => 0,
                'max' => 100,
                'default_value' => 95,
            ),
            array(
                'key' => 'field_case_studies',
                'label' => __( 'Case Studies / Highlights', 'top-digital-agencies' ),
                'name' => 'case_studies',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => __( 'Add Case Study', 'top-digital-agencies' ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_case_title',
                        'label' => __( 'Case Title', 'top-digital-agencies' ),
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_case_tag',
                        'label' => __( 'Case Tag / Specialty', 'top-digital-agencies' ),
                        'name' => 'tag',
                        'type' => 'text',
                        'required' => 1,
                    ),
                ),
            ),
            array(
                'key' => 'field_why_listed',
                'label' => __( 'Why Listed (Checklist reasons)', 'top-digital-agencies' ),
                'name' => 'why_listed',
                'type' => 'repeater',
                'layout' => 'table',
                'button_label' => __( 'Add Reason', 'top-digital-agencies' ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_reason_text',
                        'label' => __( 'Criteria / Detail (accepts HTML/strong)', 'top-digital-agencies' ),
                        'name' => 'point_text',
                        'type' => 'text',
                        'required' => 1,
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'agency',
                ),
            ),
        ),
    ) );

    // 3.4. Field Group: Homepage Page Layouts (Flexible Content Builder)
    acf_add_local_field_group( array(
        'key' => 'group_homepage_fields',
        'title' => __( 'Modular Page Layouts', 'top-digital-agencies' ),
        'fields' => array(
            array(
                'key' => 'field_page_layouts',
                'label' => __( 'Page Blocks Assembly', 'top-digital-agencies' ),
                'name' => 'page_layouts',
                'type' => 'flexible_content',
                'layouts' => array(
                    // Layout: Hero
                    'layout_hero_section' => array(
                        'key' => 'layout_hero_section',
                        'name' => 'hero_section',
                        'label' => __( 'Hero Section', 'top-digital-agencies' ),
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_hero_title',
                                'label' => __( 'Hero Heading', 'top-digital-agencies' ),
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'The Independent Directory of Top Digital Marketing Agencies in Morocco, Ranked.',
                            ),
                            array(
                                'key' => 'field_hero_lede',
                                'label' => __( 'Hero Lede Paragraph', 'top-digital-agencies' ),
                                'name' => 'lede',
                                'type' => 'textarea',
                                'rows' => 3,
                                'default_value' => 'Find and compare vetted agencies based on client satisfaction, performance audits, and verified industry results.',
                            ),
                        ),
                    ),
                    // Layout: Challenge
                    'layout_challenge' => array(
                        'key' => 'layout_challenge',
                        'name' => 'challenge',
                        'label' => __( 'Challenge Section ("Sound Familiar")', 'top-digital-agencies' ),
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_challenge_heading',
                                'label' => __( 'Section Heading', 'top-digital-agencies' ),
                                'name' => 'heading',
                                'type' => 'text',
                                'default_value' => 'Most businesses hire the wrong agency within 48 hours.',
                            ),
                            array(
                                'key' => 'field_challenge_description',
                                'label' => __( 'Description copy', 'top-digital-agencies' ),
                                'name' => 'description',
                                'type' => 'textarea',
                                'rows' => 3,
                            ),
                            array(
                                'key' => 'field_quote_text',
                                'label' => __( 'Vetted Quote Text', 'top-digital-agencies' ),
                                'name' => 'quote_text',
                                'type' => 'textarea',
                                'rows' => 3,
                            ),
                            array(
                                'key' => 'field_quote_author',
                                'label' => __( 'Quote Author', 'top-digital-agencies' ),
                                'name' => 'quote_author',
                                'type' => 'text',
                            ),
                            array(
                                'key' => 'field_quote_role',
                                'label' => __( 'Quote Author Role', 'top-digital-agencies' ),
                                'name' => 'quote_role',
                                'type' => 'text',
                            ),
                        ),
                    ),
                    // Layout: How We Solve It
                    'layout_how_we_solve' => array(
                        'key' => 'layout_how_we_solve',
                        'name' => 'how_we_solve',
                        'label' => __( 'How We Solve It (Editorial Approach)', 'top-digital-agencies' ),
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_hws_title',
                                'label' => __( 'Section Title', 'top-digital-agencies' ),
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'A better way to choose an agency.',
                            ),
                        ),
                    ),
                    // Layout: Stats Band
                    'layout_stats_band' => array(
                        'key' => 'layout_stats_band',
                        'name' => 'stats_band',
                        'label' => __( 'Stats band counter', 'top-digital-agencies' ),
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_stats_title',
                                'label' => __( 'Introductory Title', 'top-digital-agencies' ),
                                'name' => 'title',
                                'type' => 'text',
                            ),
                        ),
                    ),
                    // Layout: Editors Picks
                    'layout_editors_picks' => array(
                        'key' => 'layout_editors_picks',
                        'name' => 'editors_picks',
                        'label' => __( 'Editors Picks Grid', 'top-digital-agencies' ),
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_picks_title',
                                'label' => __( 'Section Heading', 'top-digital-agencies' ),
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'This month\'s highest rated.',
                            ),
                        ),
                    ),
                    // Layout: Specialties
                    'layout_specialties' => array(
                        'key' => 'layout_specialties',
                        'name' => 'specialties',
                        'label' => __( 'Specialties Hubs Grid', 'top-digital-agencies' ),
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_spec_title',
                                'label' => __( 'Section Heading', 'top-digital-agencies' ),
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'Find your exact need.',
                            ),
                        ),
                    ),
                    // Layout: Trust & Independence
                    'layout_trust' => array(
                        'key' => 'layout_trust',
                        'name' => 'trust',
                        'label' => __( 'Trust & Independence guidelines', 'top-digital-agencies' ),
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_trust_title',
                                'label' => __( 'Section Heading', 'top-digital-agencies' ),
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'We don\'t take money from agencies to rank them higher.',
                            ),
                            array(
                                'key' => 'field_trust_copy',
                                'label' => __( 'Independence copy', 'top-digital-agencies' ),
                                'name' => 'description',
                                'type' => 'textarea',
                                'rows' => 3,
                            ),
                        ),
                    ),
                    // Layout: Footer CTA Matchmaker
                    'layout_footer_cta' => array(
                        'key' => 'layout_footer_cta',
                        'name' => 'footer_cta',
                        'label' => __( 'Footer CTA Band', 'top-digital-agencies' ),
                        'display' => 'block',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_fcta_title',
                                'label' => __( 'CTA Title', 'top-digital-agencies' ),
                                'name' => 'title',
                                'type' => 'text',
                                'default_value' => 'Ready to find the right digital agency?',
                            ),
                            array(
                                'key' => 'field_fcta_description',
                                'label' => __( 'CTA Description', 'top-digital-agencies' ),
                                'name' => 'description',
                                'type' => 'textarea',
                                'rows' => 3,
                            ),
                        ),
                    ),
                ),
                'button_label' => __( 'Add Modular Layout Block', 'top-digital-agencies' ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
            ),
        ),
    ) );
}
add_action( 'acf/init', 'tda_register_acf_field_groups' );


/* ==========================================================================
   4. Serializing Agency Data for Premium Preloaded Instant Search (Localize)
   ========================================================================== */

function tda_get_serialized_agency_data() {
    $agencies = array();

    $query = new WP_Query( array(
        'post_type'      => 'agency',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ) );

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();

            // Fetch Services associated with the agency
            $services = array();
            $service_terms = get_the_terms( $id, 'agency_service' );
            if ( ! empty( $service_terms ) && ! is_wp_error( $service_terms ) ) {
                foreach ( $service_terms as $term ) {
                    $services[] = $term->name;
                }
            }

            // Fetch City associated with the agency
            $city = '';
            $city_terms = get_the_terms( $id, 'agency_city' );
            if ( ! empty( $city_terms ) && ! is_wp_error( $city_terms ) ) {
                $city = $city_terms[0]->name; // Grab first associated city
            }

            // Case Studies array
            $case_studies = array();
            if ( have_rows( 'case_studies', $id ) ) {
                while ( have_rows( 'case_studies', $id ) ) {
                    the_row();
                    $case_studies[] = array(
                        'title' => get_sub_field( 'title' ),
                        'tag'   => get_sub_field( 'tag' ),
                    );
                }
            }

            // Why Listed checklist
            $why_listed = array();
            if ( have_rows( 'why_listed', $id ) ) {
                while ( have_rows( 'why_listed', $id ) ) {
                    the_row();
                    $why_listed[] = get_sub_field( 'point_text' );
                }
            }

            // Build structural localized object
            $agencies[] = array(
                'id'            => get_post_field( 'post_name', $id ),
                'name'          => get_the_title(),
                'rank'          => intval( get_field( 'agency_rank', $id ) ),
                'logo'          => get_field( 'logo_image', $id ) ? get_field( 'logo_image', $id ) : '',
                'logoText'      => get_field( 'logo_text', $id ) ? get_field( 'logo_text', $id ) : '',
                'rating'        => floatval( get_field( 'rating_value', $id ) ),
                'reviews'       => intval( get_field( 'review_count', $id ) ),
                'city'          => $city,
                'services'      => $services,
                'budget'        => get_field( 'budget', $id ) ? get_field( 'budget', $id ) : '10,000 MAD/mo',
                'project'       => get_field( 'project', $id ) ? get_field( 'project', $id ) : '30K-100K MAD',
                'teamSize'      => get_field( 'team_size', $id ) ? get_field( 'team_size', $id ) : '10-25',
                'clientsServed' => get_field( 'clients_served', $id ) ? get_field( 'clients_served', $id ) : '50+',
                'founded'       => get_field( 'founded', $id ) ? get_field( 'founded', $id ) : '2020',
                'address'       => get_field( 'address', $id ) ? get_field( 'address', $id ) : '',
                'email'         => get_field( 'email', $id ) ? get_field( 'email', $id ) : '',
                'phone'         => get_field( 'phone', $id ) ? get_field( 'phone', $id ) : '',
                'website'       => get_field( 'website', $id ) ? get_field( 'website', $id ) : '',
                'stats'         => array(
                    'speed'  => intval( get_field( 'pagespeed_score', $id ) ),
                    'vitals' => get_field( 'core_web_vitals', $id ) ? get_field( 'core_web_vitals', $id ) : 'PASS',
                    'code'   => get_field( 'code_cleanliness', $id ) . '%',
                ),
                'link'          => get_permalink( $id ),
                // Seed fallback translation text structures (EN default)
                'en' => array(
                    'usp'         => get_the_excerpt( $id ),
                    'description' => get_the_excerpt( $id ),
                    'whyListed'   => $why_listed,
                    'caseStudies' => $case_studies,
                ),
                'fr' => array(
                    'usp'         => get_the_excerpt( $id ), // Duplicates standard for lookup
                    'description' => get_the_excerpt( $id ),
                    'whyListed'   => $why_listed,
                    'caseStudies' => $case_studies,
                )
            );
        }
        wp_reset_postdata();
    }

    return $agencies;
}


/* ==========================================================================
   5. Theme Setup Automation & Seeding on Theme Activation
   ========================================================================== */

function tda_activate_theme_automation( $force = false ) {
    if ( $force ) {
        // Delete existing CPTs to avoid duplicates
        $cpts = array( 'agency', 'specialty_hub', 'stat_metric' );
        foreach ( $cpts as $cpt ) {
            $posts = get_posts( array(
                'post_type'   => $cpt,
                'numberposts' => -1,
                'post_status' => 'any',
            ) );
            if ( is_array( $posts ) ) {
                foreach ( $posts as $p ) {
                    wp_delete_post( $p->ID, true );
                }
            }
        }
    }

    // 5.1. Create standard Pages if they don't exist
    $pages_to_create = array(
        'Home'        => array( 'template' => '', 'is_front' => true ),
        'Directory'   => array( 'template' => 'template-directory.php', 'is_front' => false ),
        'Blog'        => array( 'template' => '', 'is_front' => false ),
        'Rankings'    => array( 'template' => 'template-rankings.php', 'is_front' => false ),
        'About'       => array( 'template' => 'template-about.php', 'is_front' => false ),
        'Methodology' => array( 'template' => 'template-methodology.php', 'is_front' => false ),
        'Contact'     => array( 'template' => 'template-contact.php', 'is_front' => false ),
    );

    foreach ( $pages_to_create as $page_title => $meta ) {
        $page_check = get_page_by_title( $page_title );
        $page_id = 0;

        if ( ! isset( $page_check->ID ) ) {
            $page_id = wp_insert_post( array(
                'post_title'   => $page_title,
                'post_content' => '',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
        } else {
            $page_id = $page_check->ID;
            if ( $page_check->post_status !== 'publish' ) {
                wp_update_post( array(
                    'ID'          => $page_id,
                    'post_status' => 'publish',
                ) );
            }
        }

        if ( $page_id ) {
            if ( $meta['template'] ) {
                update_post_meta( $page_id, '_wp_page_template', $meta['template'] );
            }

            if ( $meta['is_front'] ) {
                // Set as static front page
                update_option( 'show_on_front', 'page' );
                update_option( 'page_on_front', $page_id );

                // Seed flexible layouts on Home Page if forcing or empty
                if ( function_exists( 'update_field' ) ) {
                    $existing_layouts = get_field( 'field_page_layouts', $page_id );
                    if ( $force || empty( $existing_layouts ) ) {
                        $layouts = array(
                            array(
                                'acf_fc_layout' => 'hero_section',
                                'title' => 'The Independent Directory of Top Digital Marketing Agencies in Morocco, Ranked.',
                                'lede' => 'Find and compare vetted agencies based on client satisfaction, performance audits, and verified industry results. Zero paid placement, 100% objective rankings.',
                            ),
                            array(
                                'acf_fc_layout' => 'challenge',
                                'heading' => 'Most businesses hire the wrong agency within 48 hours.',
                                'description' => 'The Moroccan digital agency market is crowded, noisy, and opaque. Beautiful websites hide mediocre work. Glowing testimonials are rarely verified. And by the time you realize you\'ve made the wrong choice, your budget is already gone.',
                                'quote_text' => 'We burned through two agencies and 200,000 dirhams before we found one that actually delivered. If this directory had existed, we would have saved six months.',
                                'quote_author' => 'Youssef Benali',
                                'quote_role' => 'CMO, Craft Morocco',
                            ),
                            array(
                                'acf_fc_layout' => 'how_we_solve',
                                'title' => 'A better way to choose an agency.',
                            ),
                            array(
                                'acf_fc_layout' => 'stats_band',
                                'title' => 'Verified numbers speaking for themselves.',
                            ),
                            array(
                                'acf_fc_layout' => 'editors_picks',
                                'title' => 'This month\'s highest rated.',
                            ),
                            array(
                                'acf_fc_layout' => 'specialties',
                                'title' => 'Find your exact need.',
                            ),
                            array(
                                'acf_fc_layout' => 'trust',
                                'title' => 'We don\'t take money from agencies to rank them higher.',
                                'description' => 'Our rankings are editorial, not commercial. We evaluate every agency against the same four criteria, publish our methodology openly, and update scores quarterly based on real performance data.',
                            ),
                            array(
                                'acf_fc_layout' => 'footer_cta',
                                'title' => 'Ready to find the right digital agency?',
                                'description' => 'Skip the sales pitches. Match your project details in 2 minutes and let our independent audits reveal the perfect partner for your growth goals.',
                            )
                        );
                        update_field( 'field_page_layouts', $layouts, $page_id );
                    }
                }
            }

            if ( $page_title === 'Blog' ) {
                update_option( 'page_for_posts', $page_id );
            }
        }
    }

    // 5.2. Create Navigation Menu
    $menu_name = 'Primary Navigation';
    $menu_exists = wp_get_nav_menu_object( $menu_name );
    if ( $menu_exists && $force ) {
        wp_delete_nav_menu( $menu_exists->term_id );
        $menu_exists = false;
    }
    if ( ! $menu_exists ) {
        $menu_id = wp_create_nav_menu( $menu_name );
        
        // Add pages to menu
        foreach ( $pages_to_create as $page_title => $meta ) {
            $page = get_page_by_title( $page_title );
            if ( $page ) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'     => __( strtolower( $page_title ), 'top-digital-agencies' ),
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ) );
            }
        }

        // Assign to theme location
        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['primary'] = $menu_id;
        $locations['footer'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    // 5.3. Seed Taxonomies & Custom Post Types
    $service_terms = array( 'SEO', 'Paid Ads', 'Social Media', 'Web Design', 'Branding', 'Content Marketing' );
    foreach ( $service_terms as $term ) {
        if ( ! term_exists( $term, 'agency_service' ) ) {
            wp_insert_term( $term, 'agency_service' );
        }
    }

    $city_terms = array( 'Casablanca', 'Rabat', 'Tangier', 'Marrakech', 'Agadir' );
    foreach ( $city_terms as $term ) {
        if ( ! term_exists( $term, 'agency_city' ) ) {
            wp_insert_term( $term, 'agency_city' );
        }
    }

    // Seed Stat Metrics
    $stats = array(
        '150+'   => 'Agencies researched and listed',
        '2,400+' => 'Client reviews verified by our team',
        '6'      => 'Moroccan cities covered',
        '0'      => 'Paid placements or promoted ranks',
    );
    foreach ( $stats as $num => $label ) {
        $check = get_page_by_title( $label, OBJECT, 'stat_metric' );
        if ( ! $check ) {
            $stat_id = wp_insert_post( array(
                'post_title'  => $label,
                'post_status' => 'publish',
                'post_type'   => 'stat_metric',
            ) );
            if ( $stat_id && function_exists( 'update_field' ) ) {
                update_field( 'field_stat_number', $num, $stat_id );
                update_field( 'field_stat_label', $label, $stat_id );
            }
        }
    }

    // Seed Specialty Hubs
    $hubs = array(
        'SEO' => array(
            'svg' => '<i data-lucide="search" class="w-4 h-4"></i>',
            'link' => 'SEO',
            'subs' => array( 'On-Page SEO', 'Technical Audits', 'Local Search SEO', 'Link Building' )
        ),
        'Paid Ads' => array(
            'svg' => '<i data-lucide="megaphone" class="w-4 h-4"></i>',
            'link' => 'Paid Ads',
            'subs' => array( 'Google PPC campaigns', 'Social Ads (Meta & TikTok)', 'Retargeting funnels', 'Shopping/E-com PPC' )
        ),
        'Social Media' => array(
            'svg' => '<i data-lucide="share-2" class="w-4 h-4"></i>',
            'link' => 'Social Media',
            'subs' => array( 'Community management', 'Influencer strategy', 'Content creation', 'Social listening' )
        ),
        'Web Design' => array(
            'svg' => '<i data-lucide="layout" class="w-4 h-4"></i>',
            'link' => 'Web Design',
            'subs' => array( 'UX/UI Wireframing', 'Responsive development', 'E-commerce engineering', 'Web performance audits' )
        ),
        'Branding' => array(
            'svg' => '<i data-lucide="palette" class="w-4 h-4"></i>',
            'link' => 'Branding',
            'subs' => array( 'Visual identity design', 'Brand styleguides', 'Packaging design', 'Brand strategy workshops' )
        ),
        'Content Marketing' => array(
            'svg' => '<i data-lucide="file-text" class="w-4 h-4"></i>',
            'link' => 'Content Marketing',
            'subs' => array( 'SEO copywriting', 'Newsletter marketing', 'Whitepapers & eBooks', 'Content automation' )
        )
    );
    foreach ( $hubs as $title => $data ) {
        $check = get_page_by_title( $title, OBJECT, 'specialty_hub' );
        if ( ! $check ) {
            $hub_id = wp_insert_post( array(
                'post_title'  => $title,
                'post_status' => 'publish',
                'post_type'   => 'specialty_hub',
            ) );
            if ( $hub_id && function_exists( 'update_field' ) ) {
                update_field( 'field_icon_svg', $data['svg'], $hub_id );
                update_field( 'field_direct_link_parameter', $data['link'], $hub_id );
                
                $subs_array = array();
                foreach ( $data['subs'] as $sub_name ) {
                    $subs_array[] = array( 'service_name' => $sub_name );
                }
                update_field( 'field_sub_services', $subs_array, $hub_id );
            }
        }
    }



    // Seed Agencies
    $agencies_to_seed = array(
        'RMD' => array(
            'rank' => '1',
            'rating' => '4.9',
            'reviews' => '42',
            'city' => 'Tangier',
            'services' => array( 'SEO', 'Paid Ads', 'Social Media', 'Web Design', 'Content Marketing' ),
            'budget' => '15,000 MAD/mo',
            'project' => '50K-200K MAD',
            'team_size' => '25-50',
            'clients' => '150+',
            'founded' => '2018',
            'address' => 'Avenue Mohammed VI, Bureau 45, Tangier 90000',
            'email' => 'contact@rmd.ma',
            'website' => 'www.rmd.ma',
            'speed' => 98,
            'clean' => 99,
            'usp' => 'Digital growth partner for ambitious Moroccan brands.',
            'why' => array(
                '<strong>Verified Reviews:</strong> 42 verified client reviews with an average rating of 4.9/5.0',
                '<strong>Portfolio Quality:</strong> Documented case studies showing 150%+ average ROI improvement',
                '<strong>Market Presence:</strong> 6+ years in business, 25+ team members, strong reputation',
                '<strong>Client Diversity:</strong> Proven track record across multiple corporate and startup clients'
            ),
            'cases' => array(
                array( 'title' => 'Saas SEO Drive', 'tag' => 'SEO Audit' ),
                array( 'title' => 'Analytics Tracking Setup', 'tag' => 'Data Infra' ),
                array( 'title' => 'Google Ads Scale', 'tag' => 'PPC Search' )
            )
        ),
        'Pixagram' => array(
            'rank' => '2',
            'rating' => '4.8',
            'reviews' => '38',
            'city' => 'Casablanca',
            'services' => array( 'Social Media', 'Branding', 'Content Marketing', 'Paid Ads' ),
            'budget' => '10,000 MAD/mo',
            'project' => '30K-150K MAD',
            'team_size' => '15-25',
            'clients' => '90+',
            'founded' => '2019',
            'address' => 'Boulevard Anfa, Res. Yasmina, Casablanca 20000',
            'email' => 'hello@pixagram.ma',
            'website' => 'www.pixagram.ma',
            'speed' => 95,
            'clean' => 96,
            'usp' => 'Creative digital agency specializing in social media and brand storytelling.',
            'why' => array(
                '<strong>Verified Reviews:</strong> 38 verified client reviews with an average rating of 4.8/5.0',
                '<strong>Branding Excellence:</strong> Vetted records for creative design and identity campaign execution',
                '<strong>Social Engagement:</strong> High benchmark engagement rates on client social channels',
                '<strong>Local Market Focus:</strong> Deep expertise in Moroccan consumer behavior and Darija dialect'
            ),
            'cases' => array(
                array( 'title' => 'Creator Campaigns', 'tag' => 'Social Growth' ),
                array( 'title' => 'Visual Brand Refresh', 'tag' => 'Brand Design' ),
                array( 'title' => 'E-Commerce Media Buys', 'tag' => 'Social Ads' )
            )
        ),
        'MediaBoost' => array(
            'rank' => '3',
            'rating' => '4.7',
            'reviews' => '31',
            'city' => 'Rabat',
            'services' => array( 'Paid Ads', 'SEO', 'Content Marketing' ),
            'budget' => '12,000 MAD/mo',
            'project' => '40K-180K MAD',
            'team_size' => '10-20',
            'clients' => '75+',
            'founded' => '2020',
            'address' => 'Avenue Allal Ben Abdellah, Agdal, Rabat 10000',
            'email' => 'growth@mediaboost.ma',
            'website' => 'www.mediaboost.ma',
            'speed' => 91,
            'clean' => 94,
            'usp' => 'Performance-driven agency focused on paid acquisition and CRO.',
            'why' => array(
                '<strong>Verified Reviews:</strong> 31 verified client reviews with an average rating of 4.7/5.0',
                '<strong>E-commerce Scaling:</strong> Proven record of scaling Shopify and custom eCommerce setups in Morocco',
                '<strong>Analytics Audit:</strong> Expert setups for advanced tracking, attribution, and server-side configurations',
                '<strong>CRO Expertise:</strong> Clear frameworks that yield average conversion improvements of 20%+'
            ),
            'cases' => array(
                array( 'title' => 'Media Buying Setups', 'tag' => 'Facebook Ads' ),
                array( 'title' => 'CRO Audit & Landings', 'tag' => 'Conversion Rate' ),
                array( 'title' => 'Attribution Tracking', 'tag' => 'Analytics Setup' )
            )
        )
    );

    foreach ( $agencies_to_seed as $name => $data ) {
        $check = get_page_by_title( $name, OBJECT, 'agency' );
        if ( ! $check ) {
            $agency_id = wp_insert_post( array(
                'post_title'   => $name,
                'post_excerpt' => $data['usp'],
                'post_status'  => 'publish',
                'post_type'    => 'agency',
            ) );

            if ( $agency_id ) {
                // Set Taxonomies
                $city_term = get_term_by( 'name', $data['city'], 'agency_city' );
                if ( $city_term ) {
                    wp_set_post_terms( $agency_id, array( $city_term->term_id ), 'agency_city' );
                }

                $service_ids = array();
                foreach ( $data['services'] as $s_name ) {
                    $s_term = get_term_by( 'name', $s_name, 'agency_service' );
                    if ( $s_term ) {
                        $service_ids[] = $s_term->term_id;
                    }
                }
                wp_set_post_terms( $agency_id, $service_ids, 'agency_service' );

                // Set ACF Fields
                if ( function_exists( 'update_field' ) ) {
                    update_field( 'field_logo_text', $name, $agency_id );
                    update_field( 'field_agency_rank', $data['rank'], $agency_id );
                    update_field( 'field_rating_value', $data['rating'], $agency_id );
                    update_field( 'field_review_count', $data['reviews'], $agency_id );
                    update_field( 'field_budget', $data['budget'], $agency_id );
                    update_field( 'field_project', $data['project'], $agency_id );
                    update_field( 'field_team_size', $data['team_size'], $agency_id );
                    update_field( 'field_clients_served', $data['clients'], $agency_id );
                    update_field( 'field_founded', $data['founded'], $agency_id );
                    update_field( 'field_address', $data['address'], $agency_id );
                    update_field( 'field_email', $data['email'], $agency_id );
                    update_field( 'field_website', $data['website'], $agency_id );
                    
                    update_field( 'field_pagespeed_score', $data['speed'], $agency_id );
                    update_field( 'field_core_web_vitals', 'PASS', $agency_id );
                    update_field( 'field_code_cleanliness', $data['clean'], $agency_id );

                    $why_array = array();
                    foreach ( $data['why'] as $point ) {
                        $why_array[] = array( 'point_text' => $point );
                    }
                    update_field( 'field_why_listed', $why_array, $agency_id );

                    update_field( 'field_case_studies', $data['cases'], $agency_id );
                }
            }
        }
    }
}
add_action( 'after_switch_theme', 'tda_activate_theme_automation' );

/**
 * Trigger re-seeding when tda_reseed is passed in admin URL.
 */
function tda_admin_reseed_trigger() {
    if ( is_admin() && current_user_can( 'manage_options' ) && isset( $_GET['tda_reseed'] ) ) {
        // Force complete re-seeding
        tda_activate_theme_automation( true );
        
        // Redirect back to dashboard to display notice
        wp_safe_redirect( admin_url( '?tda_seeded=1' ) );
        exit;
    }
}
add_action( 'admin_init', 'tda_admin_reseed_trigger' );

/**
 * Display a notice when theme is successfully seeded.
 */
function tda_admin_reseed_notice() {
    if ( is_admin() && isset( $_GET['tda_seeded'] ) ) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Top Digital Agencies theme data and pages have been successfully seeded with mock content!', 'top-digital-agencies' ); ?></p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'tda_admin_reseed_notice' );


/* ==========================================================================
   6. Dynamic Menu Filtering Classes Injection
   ========================================================================== */

/**
 * Filter dynamic menu links to append .nav-link and evaluate active status classes.
 */
function tda_filter_menu_link_attributes( $atts, $item, $args ) {
    if ( $args->theme_location === 'primary' || $args->theme_location === 'footer' ) {
        // Build base class list
        $classes = 'nav-link text-[13px] font-medium transition-colors duration-150';

        // Check if menu item is currently active
        if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
            $classes .= ' active font-semibold text-slate-900';
        } else {
            $classes .= ' text-slate-600 hover:text-slate-900';
        }

        // Add class attribute
        if ( isset( $atts['class'] ) ) {
            $atts['class'] .= ' ' . $classes;
        } else {
            $atts['class'] = $classes;
        }
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'tda_filter_menu_link_attributes', 10, 3 );

/**
 * Custom Fallback Menu matching v3_2 layout when no custom menu is defined in admin.
 */
function tda_fallback_navigation_menu() {
    $menu_items = array(
        'home'        => home_url( '/' ),
        'directory'   => home_url( '/directory/' ),
        'rankings'    => home_url( '/rankings/' ),
        'blog'        => home_url( '/blog/' ),
        'about'       => home_url( '/about/' ),
        'methodology' => home_url( '/methodology/' ),
        'contact'     => home_url( '/contact/' ),
    );

    $current_url = home_url( add_query_arg( array(), $wp->request ) );
    
    foreach ( $menu_items as $name => $url ) {
        $active_class = ( trailingslashit( $current_url ) === trailingslashit( $url ) ) ? 'active font-semibold text-slate-900' : 'text-slate-600 hover:text-slate-900';
        echo '<a href="' . esc_url( $url ) . '" class="nav-link text-[13px] font-medium transition-colors ' . esc_attr( $active_class ) . '">' . esc_html( $name ) . '</a>';
    }
}
