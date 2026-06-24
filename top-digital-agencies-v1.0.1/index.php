<?php
/**
 * The main template file
 * Required fallback for all WordPress themes
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        ?>
        <article class="max-w-4xl mx-auto px-6 py-12">
            <h1 class="text-3xl font-extrabold mb-4 font-display"><?php the_title(); ?></h1>
            <div class="prose text-slate-600">
                <?php the_content(); ?>
            </div>
        </article>
        <?php
    endwhile;
endif;

get_footer();
?>
