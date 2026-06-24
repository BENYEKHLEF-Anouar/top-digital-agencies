<?php
/**
 * The template for displaying standard pages
 */

get_header();

if ( have_rows( 'page_layouts' ) ) :
    while ( have_rows( 'page_layouts' ) ) : the_row();
        $layout = get_row_layout();
        $clean_layout = str_replace( 'layout_', '', $layout );
        get_template_part( 'template-parts/layouts/' . $clean_layout );
    endwhile;
else :
    // Standard template loop
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            ?>
            <section class="py-16 md:py-24 bg-white/80 border-b border-slate-200 backdrop-blur-sm">
                <div class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8">
                    <header class="mb-10 text-center">
                        <h1 class="text-[2.25rem] md:text-[3.25rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] font-display">
                            <?php the_title(); ?>
                        </h1>
                    </header>
                    <div class="prose prose-slate max-w-none text-[15px] text-slate-600 leading-relaxed space-y-6">
                        <?php the_content(); ?>
                    </div>
                </div>
            </section>
            <?php
        endwhile;
    endif;
endif;

get_footer();
?>
