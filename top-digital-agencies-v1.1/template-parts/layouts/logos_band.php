<?php
/**
 * Layout Template Part: Partner Logos Band
 */

$title = get_sub_field( 'title' );

// Query partner logos CPT
$logos_query = new WP_Query( array(
    'post_type'      => 'partner_logo',
    'posts_per_page' => 10,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );
?>

<section class="py-8 bg-white/50 border-b border-slate-200 backdrop-blur-sm">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
        <?php if ( $title ) : ?>
            <p class="text-center text-[10px] font-semibold text-slate-400 uppercase tracking-widest mb-5 font-display">
                <?php echo wp_kses_post( $title ); ?>
            </p>
        <?php endif; ?>
        
        <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-6 md:gap-x-16 opacity-50 grayscale hover:opacity-75 transition-opacity">
            <?php
            if ( $logos_query->have_posts() ) :
                while ( $logos_query->have_posts() ) : $logos_query->the_post();
                    $logo_id   = get_the_ID();
                    $logo_url  = get_field( 'logo_url', $logo_id );
                    $logo_name = get_the_title();
                    
                    if ( $logo_url ) :
                        ?>
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $logo_name ); ?>" class="h-6 md:h-7 w-auto object-contain">
                        <?php
                    else :
                        // Fallback text if logo image url is not defined
                        ?>
                        <span class="text-slate-450 font-bold text-[13px] uppercase tracking-wider font-display"><?php echo esc_html( $logo_name ); ?></span>
                        <?php
                    endif;
                endwhile;
                wp_reset_postdata();
            else :
                // Default fallback mock logos matching standard layout
                ?>
                <span class="text-slate-450 font-bold text-[13px] uppercase tracking-wider font-display">Nestle</span>
                <span class="text-slate-450 font-bold text-[13px] uppercase tracking-wider font-display">Google</span>
                <span class="text-slate-450 font-bold text-[13px] uppercase tracking-wider font-display">Hyundai</span>
                <span class="text-slate-450 font-bold text-[13px] uppercase tracking-wider font-display">L'Oreal</span>
                <span class="text-slate-450 font-bold text-[13px] uppercase tracking-wider font-display">Volvo</span>
                <span class="text-slate-450 font-bold text-[13px] uppercase tracking-wider font-display">Samsung</span>
                <?php
            endif;
            ?>
        </div>
    </div>
</section>
