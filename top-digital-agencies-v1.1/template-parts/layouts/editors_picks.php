<?php
/**
 * Layout Template Part: Editors Picks
 */

$title = get_sub_field( 'title' );

// Query top 3 ranked agencies
$picks_query = new WP_Query( array(
    'post_type'      => 'agency',
    'posts_per_page' => 3,
    'meta_key'       => 'agency_rank',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
    'post_status'    => 'publish',
) );
?>

<section class="py-14 md:py-18 bg-slate-50/50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-10">
            <div>
                <span class="section-label text-slate-800 mb-2 block"><?php _e( 'Editor\'s Picks', 'top-digital-agencies' ); ?></span>
                <h2 class="text-[1.75rem] font-bold text-slate-900 tracking-tight font-display"><?php echo wp_kses_post( $title ); ?></h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/rankings/' ) ); ?>" class="mt-3 sm:mt-0 text-[13px] font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1 transition-colors">
                <span><?php _e( 'See full rankings', 'top-digital-agencies' ); ?></span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 max-w-5xl mx-auto">
            <?php
            if ( $picks_query->have_posts() ) :
                while ( $picks_query->have_posts() ) : $picks_query->the_post();
                    $id = get_the_ID();
                    $logo_text  = get_field( 'logo_text', $id );
                    $logo_image = get_field( 'logo_image', $id );
                    $rank       = get_field( 'agency_rank', $id );
                    $rating     = get_field( 'rating_value', $id );
                    $reviews    = get_field( 'review_count', $id );
                    $budget     = get_field( 'budget', $id );
                    
                    $cities = get_the_terms( $id, 'agency_city' );
                    $city_name = ( ! empty( $cities ) && ! is_wp_error( $cities ) ) ? $cities[0]->name : '';
                    ?>
                    <div class="card-hover bg-white border border-slate-200 rounded-xl p-5 flex flex-col justify-between cursor-pointer shadow-sm relative" onclick="window.location.href='<?php the_permalink(); ?>'">
                        <div>
                            <div class="flex justify-between items-start mb-3 mt-2">
                                <?php if ( $logo_image ) : ?>
                                    <img src="<?php echo esc_url( $logo_image ); ?>" alt="<?php the_title_attribute(); ?>" class="w-11 h-11 rounded-lg object-cover border border-slate-100 bg-white">
                                <?php else : ?>
                                    <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-brand-600 text-white font-bold text-xs uppercase"><?php echo esc_html( $logo_text ? $logo_text : substr( get_the_title(), 0, 2 ) ); ?></div>
                                <?php endif; ?>
                                
                                <span class="bg-amber-750 inline-flex items-center gap-0.5 text-white text-[9px] font-bold px-2 py-0.5 rounded font-mono uppercase tracking-wide border border-amber-600/30 shadow-sm" style="background-color: #b45309;">
                                    <i data-lucide="award" class="w-2.5 h-2.5"></i> #<?php echo esc_html( $rank ); ?>
                                </span>
                            </div>
                            
                            <h3 class="font-extrabold text-[15px] text-slate-900 leading-snug font-display hover:text-brand-600 transition-colors mb-1"><?php the_title(); ?></h3>
                            <p class="text-[13px] text-slate-500 leading-relaxed truncate-2-lines mb-4"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        </div>
                        
                        <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400">
                                <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3 h-3"></i> <?php echo esc_html( $city_name ); ?></span>
                                <span class="flex items-center gap-1"><i data-lucide="wallet" class="w-3 h-3"></i> <?php echo esc_html( $budget ); ?></span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-mono text-slate-500 pt-1">
                                <div class="flex items-center gap-0.5">
                                    <i data-lucide="star" class="w-3 h-3 text-amber-400 fill-amber-400"></i>
                                    <span class="font-bold text-slate-700"><?php echo esc_html( $rating ); ?></span>
                                    <span class="text-slate-400 font-mono">(<?php echo esc_html( $reviews ); ?>)</span>
                                </div>
                                <span class="text-brand-600 font-semibold flex items-center gap-0.5"><?php _e( 'Profile', 'top-digital-agencies' ); ?> <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i></span>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="text-[13px] text-slate-400 col-span-3 text-center">' . __( 'No agencies found in database.', 'top-digital-agencies' ) . '</p>';
            endif;
            ?>
        </div>
    </div>
</section>
