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
                    <?php
                    // Rank badge style matching common.js tiers
                    $rank_badge_data = array(
                        1 => array( 'label' => 'Rank 1 · Top Ranked', 'cls' => 'bg-amber-700', 'icon' => 'award' ),
                        2 => array( 'label' => 'Rank 2 · Featured',   'cls' => 'bg-teal-700',  'icon' => 'star' ),
                        3 => array( 'label' => 'Rank 3 · Top Pick',   'cls' => 'bg-brand-600', 'icon' => 'sparkles' )
                    );
                    $badge = isset( $rank_badge_data[ $rank ] ) ? $rank_badge_data[ $rank ] : array( 'label' => 'Rank ' . $rank, 'cls' => 'bg-slate-700', 'icon' => '' );
                    ?>
                    <div class="card-hover bg-white rounded-xl border border-slate-200 p-6 cursor-pointer relative shadow-sm" onclick="window.location.href='<?php the_permalink(); ?>'">
                        <div class="absolute -top-0 left-6 -translate-y-1/2">
                            <span class="<?php echo esc_attr( $badge['cls'] ); ?> inline-flex items-center gap-1 text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm font-mono uppercase tracking-wide">
                                <?php if ( $badge['icon'] ) : ?>
                                    <i data-lucide="<?php echo esc_attr( $badge['icon'] ); ?>" class="w-3 h-3"></i>
                                <?php endif; ?>
                                <?php echo esc_html( $badge['label'] ); ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-3 mb-4 mt-2">
                            <?php if ( $logo_image ) : ?>
                                <img src="<?php echo esc_url( $logo_image ); ?>" alt="<?php the_title_attribute(); ?>" class="w-11 h-11 rounded-lg object-cover border border-slate-100 bg-white">
                            <?php else : ?>
                                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-brand-600 text-white font-bold text-xs uppercase"><?php echo esc_html( $logo_text ? $logo_text : substr( get_the_title(), 0, 2 ) ); ?></div>
                            <?php endif; ?>
                            <div>
                                <h3 class="font-bold text-[15px] text-slate-900 font-display"><?php the_title(); ?></h3>
                                <div class="flex items-center gap-1">
                                    <?php
                                    $floor = floor( $rating );
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        if ( $i <= $floor ) {
                                            echo '<i data-lucide="star" class="w-3.5 h-3.5 text-amber-400 fill-amber-400 inline"></i>';
                                        } elseif ( $i - $rating < 1 ) {
                                            echo '<i data-lucide="star-half" class="w-3.5 h-3.5 text-amber-400 fill-amber-400 inline"></i>';
                                        } else {
                                            echo '<i data-lucide="star" class="w-3.5 h-3.5 text-slate-200 inline"></i>';
                                        }
                                    }
                                    ?>
                                    <span class="text-[13px] font-semibold text-slate-700 font-mono"><?php echo esc_html( $rating ); ?></span>
                                    <span class="text-[11px] text-slate-400 font-mono">(<?php echo esc_html( $reviews ); ?>)</span>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-[13px] text-slate-500 mb-4 leading-relaxed h-10 overflow-hidden"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        
                        <div class="flex flex-wrap gap-1 mb-4">
                            <?php
                            $services_terms = get_the_terms( $id, 'agency_service' );
                            if ( ! empty( $services_terms ) && ! is_wp_error( $services_terms ) ) {
                                $count = 0;
                                foreach ( $services_terms as $term ) {
                                    if ( $count >= 3 ) break;
                                    echo '<span class="tag-pill bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono text-[11px] border border-slate-150">' . esc_html( $term->name ) . '</span>';
                                    $count++;
                                }
                            }
                            ?>
                        </div>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <span class="text-[12px] text-slate-500 flex items-center gap-1 font-mono">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> <?php echo esc_html( $city_name ); ?>
                            </span>
                            <span class="text-[12.5px] font-bold text-brand-600 hover:text-brand-700 transition-colors font-mono">
                                <?php _e( 'View Profile', 'top-digital-agencies' ); ?>
                            </span>
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
