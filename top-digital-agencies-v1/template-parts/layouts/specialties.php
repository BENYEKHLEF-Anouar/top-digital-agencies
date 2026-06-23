<?php
/**
 * Layout Template Part: Specialties Grid
 */

$title = get_sub_field( 'title' );

// Query specialty hubs CPT
$hubs_query = new WP_Query( array(
    'post_type'      => 'specialty_hub',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
) );
?>

<section class="py-14 bg-white/70 border-b border-slate-200 backdrop-blur-sm">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="section-label text-slate-800 mb-1 block"><?php _e( 'Specialties', 'top-digital-agencies' ); ?></span>
                <h2 class="text-[1.5rem] font-bold text-slate-900 tracking-tight font-display"><?php echo wp_kses_post( $title ); ?></h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="hidden sm:flex items-center gap-1 text-[13px] font-semibold text-brand-600 hover:text-brand-700">
                <span><?php _e( 'All services', 'top-digital-agencies' ); ?></span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <?php
            if ( $hubs_query->have_posts() ) :
                while ( $hubs_query->have_posts() ) : $hubs_query->the_post();
                    $hub_id = get_the_ID();
                    $icon_svg = get_field( 'icon_svg', $hub_id );
                    $link_param = get_field( 'direct_link_parameter', $hub_id );
                    
                    // Retrieve dynamic post count for this taxonomy term
                    $term_count = 0;
                    $term_check = get_term_by( 'name', $link_param, 'agency_service' );
                    if ( isset( $term_check->count ) ) {
                        $term_count = $term_check->count;
                    }
                    ?>
                    <div onclick="window.location.href='<?php echo esc_url( home_url( '/directory/?service=' . urlencode( $link_param ) ) ); ?>'" class="card-hover bg-white border border-slate-200 hover:border-brand-600 rounded-lg p-4 text-center cursor-pointer group shadow-sm">
                        <div class="w-9 h-9 bg-slate-50 text-slate-600 rounded-md flex items-center justify-center mx-auto mb-2.5 group-hover:bg-brand-600 group-hover:text-white transition-colors">
                            <?php echo $icon_svg; // Raw SVG string enqueued ?>
                        </div>
                        <h3 class="font-semibold text-[13px] text-slate-900"><?php the_title(); ?></h3>
                        <p class="text-[11px] text-slate-400 mt-0.5"><?php echo esc_html( $term_count ); ?> <?php _e( 'agencies', 'top-digital-agencies' ); ?></p>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p class="text-[13px] text-slate-400 col-span-6 text-center">' . __( 'No specialties registered.', 'top-digital-agencies' ) . '</p>';
            endif;
            ?>
        </div>
    </div>
</section>
