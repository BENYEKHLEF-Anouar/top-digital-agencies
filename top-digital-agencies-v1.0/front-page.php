<?php
/**
 * The template for displaying the Front Page
 */

get_header();

if ( have_rows( 'page_layouts' ) ) :
    while ( have_rows( 'page_layouts' ) ) : the_row();
        $layout = get_row_layout();
        // Remove layout_ prefix to map directly to layout part name (e.g. layout_hero_section becomes hero_section)
        $clean_layout = str_replace( 'layout_', '', $layout );
        get_template_part( 'template-parts/layouts/' . $clean_layout );
    endwhile;
else :
    // Fallback template content if no layouts are defined
    ?>
    <div class="max-w-4xl mx-auto px-6 py-20 text-center">
        <h1 class="text-3xl font-extrabold mb-4 font-display"><?php the_title(); ?></h1>
        <div class="prose text-slate-500 max-w-lg mx-auto">
            <?php the_content(); ?>
        </div>
        <div class="mt-8 text-sm text-slate-400 font-mono">
            <?php _e( '[Modular Page Layouts field is empty. Edit this page in admin panel to insert blocks]', 'top-digital-agencies' ); ?>
        </div>
    </div>
    <?php
endif;

get_footer();
?>
