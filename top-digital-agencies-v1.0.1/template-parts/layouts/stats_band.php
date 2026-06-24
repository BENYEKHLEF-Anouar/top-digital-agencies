<?php
/**
 * Layout Template Part: Stats Band Section
 */

$title = get_sub_field( 'title' );

// Query all stat metrics
$stats_query = new WP_Query( array(
    'post_type'      => 'stat_metric',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
) );
?>

<section class="py-12 bg-white/60 border-b border-slate-200 backdrop-blur-sm">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
        <?php if ( $title ) : ?>
            <h3 class="text-center font-display font-semibold text-[14px] uppercase tracking-wider text-slate-400 mb-8"><?php echo wp_kses_post( $title ); ?></h3>
        <?php endif; ?>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <?php
            if ( $stats_query->have_posts() ) :
                while ( $stats_query->have_posts() ) : $stats_query->the_post();
                    $s_id = get_the_ID();
                    $num   = get_field( 'stat_number', $s_id );
                    $label = get_field( 'stat_label', $s_id );
                    ?>
                    <div class="text-left md:text-center">
                        <div class="text-[2.25rem] md:text-[3rem] font-extrabold text-slate-900 tracking-tight font-display"><?php echo esc_html( $num ); ?></div>
                        <div class="text-[12px] text-slate-500 font-semibold mt-1 uppercase tracking-wider"><?php echo esc_html( $label ); ?></div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Default fallbacks from mockup
                ?>
                <div class="text-left md:text-center">
                    <div class="text-[2.25rem] md:text-[3rem] font-extrabold text-slate-900 tracking-tight font-display">150+</div>
                    <div class="text-[12px] text-slate-500 font-semibold mt-1 uppercase tracking-wider"><?php _e( 'Agencies researched and listed', 'top-digital-agencies' ); ?></div>
                </div>
                <div class="text-left md:text-center">
                    <div class="text-[2.25rem] md:text-[3rem] font-extrabold text-slate-900 tracking-tight font-display">2,400+</div>
                    <div class="text-[12px] text-slate-500 font-semibold mt-1 uppercase tracking-wider"><?php _e( 'Client reviews verified by our team', 'top-digital-agencies' ); ?></div>
                </div>
                <div class="text-left md:text-center">
                    <div class="text-[2.25rem] md:text-[3rem] font-extrabold text-slate-900 tracking-tight font-display">6</div>
                    <div class="text-[12px] text-slate-500 font-semibold mt-1 uppercase tracking-wider"><?php _e( 'Moroccan cities covered', 'top-digital-agencies' ); ?></div>
                </div>
                <div class="text-left md:text-center">
                    <div class="text-[2.25rem] md:text-[3rem] font-extrabold text-slate-900 tracking-tight font-display">0</div>
                    <div class="text-[12px] text-slate-500 font-semibold mt-1 uppercase tracking-wider"><?php _e( 'Paid placements or promoted ranks', 'top-digital-agencies' ); ?></div>
                </div>
                <?php
            endif;
            ?>
        </div>
    </div>
</section>
