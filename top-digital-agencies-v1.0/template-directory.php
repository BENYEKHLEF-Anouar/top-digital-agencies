<?php
/**
 * Template Name: Directory Template
 */

get_header();
?>

<div class="page" id="page-directory">
    <!-- Header Hero banner -->
    <section class="relative z-10 bg-white/80 border-b border-slate-200 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8 pt-16 pb-10 md:pt-20 md:pb-12 text-center">
            <span class="section-label text-slate-800 mb-2 block"><?php _e( 'Directory', 'top-digital-agencies' ); ?></span>
            <h1 class="text-[2.25rem] md:text-[3.25rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-5 font-display">
                <?php _e( 'Digital Marketing Agencies.', 'top-digital-agencies' ); ?>
            </h1>
            <p class="text-[16px] md:text-[18px] text-slate-500 max-w-xl mx-auto leading-relaxed">
                <?php _e( 'Browse and compare agencies by city, service, and expertise.', 'top-digital-agencies' ); ?>
            </p>
        </div>
    </section>

    <!-- Filters and Results Grid -->
    <section class="py-10 bg-slate-50/50">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Filters Sidebar -->
                <div class="space-y-5 lg:sticky lg:top-20 h-fit">
                    <!-- Search Box -->
                    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                        <label class="block text-[11px] font-semibold text-slate-500 mb-2 uppercase tracking-wider"><?php _e( 'Search', 'top-digital-agencies' ); ?></label>
                        <div class="relative">
                            <input type="text" id="dir-search-input" placeholder="<?php esc_attr_e( 'Agency name...', 'top-digital-agencies' ); ?>" class="w-full bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-lg pl-3 pr-8 py-2 text-[13px] text-slate-700 search-focus outline-none transition-colors">
                            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute right-3 top-2.5"></i>
                        </div>
                    </div>

                    <!-- Services filter -->
                    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                        <label class="block text-[11px] font-semibold text-slate-500 mb-2 uppercase tracking-wider"><?php _e( 'Service', 'top-digital-agencies' ); ?></label>
                        <select id="dir-filter-service" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[13px] text-slate-700 search-focus cursor-pointer">
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

                    <!-- Cities filter -->
                    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                        <label class="block text-[11px] font-semibold text-slate-500 mb-2 uppercase tracking-wider"><?php _e( 'City', 'top-digital-agencies' ); ?></label>
                        <select id="dir-filter-city" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[13px] text-slate-700 search-focus cursor-pointer">
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

                    <!-- Ratings filter -->
                    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                        <label class="block text-[11px] font-semibold text-slate-500 mb-2 uppercase tracking-wider"><?php _e( 'Rating', 'top-digital-agencies' ); ?></label>
                        <select id="dir-filter-rating" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[13px] text-slate-700 search-focus cursor-pointer">
                            <option value="any"><?php _e( 'Any Rating', 'top-digital-agencies' ); ?></option>
                            <option value="4.5"><?php _e( '4.5+ Stars', 'top-digital-agencies' ); ?></option>
                            <option value="4.0"><?php _e( '4.0+ Stars', 'top-digital-agencies' ); ?></option>
                        </select>
                    </div>

                    <!-- Sort criteria -->
                    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                        <label class="block text-[11px] font-semibold text-slate-500 mb-2 uppercase tracking-wider"><?php _e( 'Sort by', 'top-digital-agencies' ); ?></label>
                        <select id="dir-filter-sort" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[13px] text-slate-700 search-focus cursor-pointer">
                            <option value="rank"><?php _e( 'Rank: High to Low', 'top-digital-agencies' ); ?></option>
                            <option value="rating"><?php _e( 'Rating: High to Low', 'top-digital-agencies' ); ?></option>
                            <option value="reviews"><?php _e( 'Reviews: Most to Least', 'top-digital-agencies' ); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Results Column -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="flex justify-between items-center text-[12px] font-mono text-slate-500">
                        <div>
                            <span><?php _e( 'Showing', 'top-digital-agencies' ); ?> </span>
                            <span id="dir-results-count" class="font-bold text-slate-800">0</span>
                            <span> <?php _e( 'agencies', 'top-digital-agencies' ); ?></span>
                        </div>
                    </div>

                    <!-- Container populated dynamically by theme-scripts.js -->
                    <div class="space-y-4" id="directory-cards-container">
                        <!-- Loaded dynamically -->
                        <div class="text-center py-20 bg-white border border-slate-200 rounded-xl shadow-sm">
                            <div class="animate-spin w-6 h-6 border-2 border-brand-600 border-t-transparent rounded-full mx-auto mb-3"></div>
                            <p class="text-slate-400 font-mono text-[12px]"><?php _e( 'Syncing verified telemetry datastore...', 'top-digital-agencies' ); ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php
get_footer();
?>
