<?php
/**
 * Layout Template Part: Hero Section
 */

$title = get_sub_field( 'title' );
$lede  = get_sub_field( 'lede' );
?>

<section class="relative z-10 bg-white/80 border-b border-slate-200 backdrop-blur-sm">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8 pt-16 pb-10 md:pt-24 md:pb-16">
        <div class="max-w-3xl mx-auto text-center mb-8">
            <h1 class="hero-title text-[2.25rem] md:text-[3.5rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-5 font-display">
                <?php echo wp_kses_post( $title ); ?>
            </h1>
            <p class="text-[16px] md:text-[18px] text-slate-500 mb-8 leading-relaxed max-w-xl mx-auto">
                <?php echo wp_kses_post( $lede ); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center w-full max-w-sm mx-auto sm:max-w-none sm:w-auto mb-10">
                <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="w-full sm:w-auto text-center bg-brand-600 hover:bg-brand-700 text-white font-semibold px-7 py-3 rounded-lg text-[14px] transition-all">
                    <?php _e( 'Browse Agencies', 'top-digital-agencies' ); ?>
                </a>
            </div>
        </div>

        <!-- Search / Filter block -->
        <div class="max-w-3xl mx-auto bg-white rounded-xl border border-slate-200 shadow-sm p-4 md:p-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wider"><?php _e( 'Service', 'top-digital-agencies' ); ?></label>
                    <div class="relative">
                        <select id="home-filter-service" class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-3 pr-8 py-2 text-[13px] text-slate-700 search-focus appearance-none cursor-pointer">
                            <option value="all"><?php _e( 'All Services', 'top-digital-agencies' ); ?></option>
                            <?php
                            $services = get_terms( array( 'taxonomy' => 'agency_service', 'hide_empty' => false ) );
                            if ( ! empty( $services ) && ! is_wp_error( $services ) ) {
                                foreach ( $services as $term ) {
                                    echo '<option value="' . esc_attr( $term->name ) . '">' . esc_html( $term->name ) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wider"><?php _e( 'City', 'top-digital-agencies' ); ?></label>
                    <div class="relative">
                        <select id="home-filter-city" class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-3 pr-8 py-2 text-[13px] text-slate-700 search-focus appearance-none cursor-pointer">
                            <option value="all"><?php _e( 'All Cities', 'top-digital-agencies' ); ?></option>
                            <?php
                            $cities = get_terms( array( 'taxonomy' => 'agency_city', 'hide_empty' => false ) );
                            if ( ! empty( $cities ) && ! is_wp_error( $cities ) ) {
                                foreach ( $cities as $term ) {
                                    echo '<option value="' . esc_attr( $term->name ) . '">' . esc_html( $term->name ) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wider"><?php _e( 'Rating', 'top-digital-agencies' ); ?></label>
                    <div class="relative">
                        <select id="home-filter-rating" class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-3 pr-8 py-2 text-[13px] text-slate-700 search-focus appearance-none cursor-pointer">
                            <option value="any"><?php _e( 'Any Rating', 'top-digital-agencies' ); ?></option>
                            <option value="4.5"><?php _e( '4.5+ Stars', 'top-digital-agencies' ); ?></option>
                            <option value="4.0"><?php _e( '4.0+ Stars', 'top-digital-agencies' ); ?></option>
                        </select>
                    </div>
                </div>
                <div class="flex items-end">
                    <button onclick="triggerHomeSearch()" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2 rounded-lg text-[13px] transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span><?php _e( 'Search', 'top-digital-agencies' ); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function triggerHomeSearch() {
        const s = document.getElementById('home-filter-service').value;
        const c = document.getElementById('home-filter-city').value;
        const r = document.getElementById('home-filter-rating').value;
        
        let url = '<?php echo esc_url( home_url( '/directory/' ) ); ?>?';
        if (s !== 'all') url += 'service=' + encodeURIComponent(s) + '&';
        if (c !== 'all') url += 'city=' + encodeURIComponent(c) + '&';
        if (r !== 'any') url += 'rating=' + encodeURIComponent(r);
        
        window.location.href = url;
    }
</script>
