<?php
/**
 * The template for displaying archive pages (and Blog home)
 */

get_header();
?>

<div class="page" id="page-blog">
    <!-- Blog Hero Section -->
    <section class="relative z-10 bg-white/80 border-b border-slate-200 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8 pt-16 pb-12 text-center">
            <span class="section-label text-slate-800 mb-2 block"><?php _e( 'Editorial', 'top-digital-agencies' ); ?></span>
            <h1 class="text-[2.25rem] md:text-[3.25rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-5 font-display">
                <?php _e( 'Marketing Insights.', 'top-digital-agencies' ); ?>
            </h1>
            <p class="text-[16px] md:text-[18px] text-slate-500 max-w-xl mx-auto leading-relaxed">
                <?php _e( 'Practical breakdowns on SEO, branding, and local positioning, written by our lead editors.', 'top-digital-agencies' ); ?>
            </p>
        </div>
    </section>

    <!-- Blog Posts Grid Section -->
    <section class="py-12 md:py-16 bg-slate-50/50">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
            <?php if ( have_posts() ) : ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    <?php
                    while ( have_posts() ) : the_post();
                        ?>
                        <article class="card-hover bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col justify-between cursor-pointer" onclick="window.location.href='<?php the_permalink(); ?>'">
                            <div class="p-5">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="w-full h-40 rounded-lg overflow-hidden border border-slate-100 mb-4">
                                        <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex items-center gap-2 text-[10px] uppercase font-bold text-slate-400 font-mono tracking-wider mb-2.5">
                                    <span>
                                        <?php
                                        $categories = get_the_category();
                                        if ( ! empty( $categories ) ) {
                                            echo esc_html( $categories[0]->name );
                                        } else {
                                            _e( 'Guide', 'top-digital-agencies' );
                                        }
                                        ?>
                                    </span>
                                    <span>&middot;</span>
                                    <span><?php echo get_the_date( 'M Y' ); ?></span>
                                </div>
                                
                                <h3 class="font-extrabold text-[16px] text-slate-900 leading-snug font-display hover:text-brand-600 transition-colors mb-2">
                                    <?php the_title(); ?>
                                </h3>
                                
                                <p class="text-[13px] text-slate-400 leading-relaxed truncate-2-lines">
                                    <?php echo wp_trim_words( get_the_excerpt(), 18, '...' ); ?>
                                </p>
                            </div>
                            
                            <div class="px-5 pb-5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-mono text-slate-400">
                                <span><?php _e( 'by', 'top-digital-agencies' ); ?> <?php the_author(); ?></span>
                                <span class="text-brand-600 font-semibold flex items-center gap-0.5">
                                    <?php _e( 'Read', 'top-digital-agencies' ); ?> <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </span>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    ?>
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    <?php
                    echo paginate_links( array(
                        'type'      => 'list',
                        'next_text' => '<i data-lucide="chevron-right" class="w-4 h-4"></i>',
                        'prev_text' => '<i data-lucide="chevron-left" class="w-4 h-4"></i>',
                        'class'     => 'pagination flex items-center gap-1 font-mono text-[13px]',
                    ) );
                    ?>
                </div>
            <?php else : ?>
                <div class="text-center py-20 bg-white border border-slate-200 rounded-xl max-w-xl mx-auto shadow-sm">
                    <p class="text-slate-500 font-medium"><?php _e( 'No articles found matching your query.', 'top-digital-agencies' ); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php
get_footer();
?>
