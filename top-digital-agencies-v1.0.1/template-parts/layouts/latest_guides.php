<?php
/**
 * Layout Template Part: Latest Guides & Articles
 */

$title = get_sub_field( 'title' );

// Query latest 3 blog posts
$guides_query = new WP_Query( array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
) );
?>

<section class="py-14 bg-white/70 border-b border-slate-200 backdrop-blur-sm">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="section-label text-slate-800 mb-1 block"><?php _e( 'Editorial', 'top-digital-agencies' ); ?></span>
                <h2 class="text-[1.5rem] font-bold text-slate-900 tracking-tight font-display">
                    <?php echo $title ? wp_kses_post( $title ) : __( 'Latest guides & rankings.', 'top-digital-agencies' ); ?>
                </h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="hidden sm:flex items-center gap-1 text-[13px] font-semibold text-brand-600 hover:text-brand-700">
                <span><?php _e( 'All articles', 'top-digital-agencies' ); ?></span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <?php
            if ( $guides_query->have_posts() ) :
                while ( $guides_query->have_posts() ) : $guides_query->the_post();
                    $post_id = get_the_ID();
                    $badge = get_field( 'article_badge', $post_id ) ? get_field( 'article_badge', $post_id ) : __( 'Guide', 'top-digital-agencies' );
                    $image_url = get_the_post_thumbnail_url( $post_id, 'medium_large' );
                    if ( ! $image_url ) {
                        // Fallback image urls matching unsplash resources
                        $image_url = 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop';
                    }
                    ?>
                    <div onclick="window.location.href='<?php the_permalink(); ?>'" class="card-hover bg-white border border-slate-200 rounded-xl overflow-hidden cursor-pointer group shadow-sm">
                        <div class="h-48 overflow-hidden bg-slate-100">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-5">
                            <span class="text-[11px] font-semibold text-slate-800 uppercase tracking-wider"><?php echo esc_html( $badge ); ?></span>
                            <h3 class="font-bold text-slate-900 mt-1 mb-2 text-[15px] font-display hover:text-brand-600 transition-colors"><?php the_title(); ?></h3>
                            <p class="text-[13px] text-slate-500 leading-relaxed truncate-2-lines"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
                            <div class="flex items-center gap-2 mt-4 text-[11px] text-slate-400">
                                <span><?php echo get_the_date(); ?></span>
                                <span>&middot;</span>
                                <span><?php echo esc_html( get_field( 'read_time', $post_id ) ? get_field( 'read_time', $post_id ) : '6 min' ); ?> <?php _e( 'read', 'top-digital-agencies' ); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Default static fallback mockup cards
                ?>
                <div class="card-hover bg-white border border-slate-200 rounded-xl overflow-hidden cursor-pointer group shadow-sm" onclick="window.location.href='<?php echo esc_url( home_url( '/blog/' ) ); ?>'">
                    <div class="h-48 overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop" alt="Top Agencies" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-5">
                        <span class="text-[11px] font-semibold text-slate-800 uppercase tracking-wider"><?php _e( 'Ranking', 'top-digital-agencies' ); ?></span>
                        <h3 class="font-bold text-slate-900 mt-1 mb-2 text-[15px] font-display"><?php _e( 'Top Digital Marketing Agencies in Morocco', 'top-digital-agencies' ); ?></h3>
                        <p class="text-[13px] text-slate-500 leading-relaxed"><?php _e( 'Our definitive ranking of the best agencies based on verified reviews and performance data.', 'top-digital-agencies' ); ?></p>
                        <div class="flex items-center gap-2 mt-4 text-[11px] text-slate-400">
                            <span><?php _e( 'Updated June 2024', 'top-digital-agencies' ); ?></span>
                            <span>&middot;</span>
                            <span>8 min read</span>
                        </div>
                    </div>
                </div>
                <div class="card-hover bg-white border border-slate-200 rounded-xl overflow-hidden cursor-pointer group shadow-sm" onclick="window.location.href='<?php echo esc_url( home_url( '/blog/' ) ); ?>'">
                    <div class="h-48 overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=400&fit=crop" alt="SEO Casablanca" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-5">
                        <span class="text-[11px] font-semibold text-slate-800 uppercase tracking-wider"><?php _e( 'Guide', 'top-digital-agencies' ); ?></span>
                        <h3 class="font-bold text-slate-900 mt-1 mb-2 text-[15px] font-display"><?php _e( 'Best SEO Agencies in Casablanca', 'top-digital-agencies' ); ?></h3>
                        <p class="text-[13px] text-slate-500 leading-relaxed"><?php _e( 'A curated list of search engine optimization experts helping Moroccan brands rank higher.', 'top-digital-agencies' ); ?></p>
                        <div class="flex items-center gap-2 mt-4 text-[11px] text-slate-400">
                            <span><?php _e( 'Updated May 2024', 'top-digital-agencies' ); ?></span>
                            <span>&middot;</span>
                            <span>6 min read</span>
                        </div>
                    </div>
                </div>
                <div class="card-hover bg-white border border-slate-200 rounded-xl overflow-hidden cursor-pointer group shadow-sm" onclick="window.location.href='<?php echo esc_url( home_url( '/blog/' ) ); ?>'">
                    <div class="h-48 overflow-hidden bg-slate-100">
                        <img src="https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=800&h=400&fit=crop" alt="Social Media Compared" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-5">
                        <span class="text-[11px] font-semibold text-slate-800 uppercase tracking-wider"><?php _e( 'Comparison', 'top-digital-agencies' ); ?></span>
                        <h3 class="font-bold text-slate-900 mt-1 mb-2 text-[15px] font-display"><?php _e( 'Social Media Agencies Compared', 'top-digital-agencies' ); ?></h3>
                        <p class="text-[13px] text-slate-500 leading-relaxed"><?php _e( 'Side-by-side comparison of the top social media management agencies in Morocco.', 'top-digital-agencies' ); ?></p>
                        <div class="flex items-center gap-2 mt-4 text-[11px] text-slate-400">
                            <span><?php _e( 'Updated April 2024', 'top-digital-agencies' ); ?></span>
                            <span>&middot;</span>
                            <span>10 min read</span>
                        </div>
                    </div>
                </div>
                <?php
            endif;
            ?>
        </div>
    </div>
</section>
