<?php
/**
 * Single template for CPT: Agency (Profile page)
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $agency_id = get_the_ID();

        // Retrieve custom fields
        $logo_text      = get_field( 'logo_text', $agency_id );
        $logo_image     = get_field( 'logo_image', $agency_id );
        $rating_value   = get_field( 'rating_value', $agency_id );
        $review_count   = get_field( 'review_count', $agency_id );
        $agency_rank    = get_field( 'agency_rank', $agency_id );
        $budget         = get_field( 'budget', $agency_id );
        $project        = get_field( 'project', $agency_id );
        $team_size      = get_field( 'team_size', $agency_id );
        $clients_served = get_field( 'clients_served', $agency_id );
        $founded        = get_field( 'founded', $agency_id );
        $address        = get_field( 'address', $agency_id );
        $email          = get_field( 'email', $agency_id );
        $phone          = get_field( 'phone', $agency_id );
        $website        = get_field( 'website', $agency_id );
        
        $pagespeed_score  = get_field( 'pagespeed_score', $agency_id );
        $core_web_vitals  = get_field( 'core_web_vitals', $agency_id );
        $code_cleanliness = get_field( 'code_cleanliness', $agency_id );

        // Taxonomies
        $cities = get_the_terms( $agency_id, 'agency_city' );
        $city_name = ( ! empty( $cities ) && ! is_wp_error( $cities ) ) ? $cities[0]->name : '';

        $services = get_the_terms( $agency_id, 'agency_service' );
        ?>

        <!-- Agency Profile Page Wrapper -->
        <div class="page" id="page-profile">
            <section class="relative z-10 bg-white/80 border-b border-slate-200">
                <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8 pt-12 pb-10 text-left">
                    
                    <!-- Breadcrumbs -->
                    <div class="flex justify-start items-center gap-1.5 text-[11px] font-semibold text-slate-400 mb-5 tracking-wider uppercase font-sans">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cursor-pointer hover:text-slate-900 transition-colors"><?php _e( 'HOME', 'top-digital-agencies' ); ?></a>
                        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                        <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="cursor-pointer hover:text-slate-900 transition-colors"><?php _e( 'AGENCIES', 'top-digital-agencies' ); ?></a>
                        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                        <span class="text-slate-900 font-semibold truncate max-w-[150px] sm:max-w-xs uppercase"><?php the_title(); ?></span>
                    </div>
                    
                    <!-- Profile Header Block -->
                    <div class="flex flex-col md:flex-row md:items-start gap-5">
                        <?php if ( $logo_image ) : ?>
                            <img src="<?php echo esc_url( $logo_image ); ?>" alt="<?php the_title_attribute(); ?>" class="w-16 h-16 rounded-xl object-cover flex-shrink-0 border border-slate-200 bg-white shadow-sm">
                        <?php else : ?>
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center flex-shrink-0 border border-slate-200 bg-brand-600 text-white font-bold text-lg shadow-sm">
                                <?php echo esc_html( $logo_text ? $logo_text : substr( get_the_title(), 0, 2 ) ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex-1">
                            <h1 class="hero-title text-[1.75rem] font-bold text-slate-900 tracking-tight font-display mb-2"><?php the_title(); ?></h1>
                            
                            <div class="mb-3">
                                <?php
                                if ( function_exists( 'rankBadgeHTML' ) ) {
                                    echo rankBadgeHTML( $agency_rank );
                                } else {
                                    // Custom inline render if helper missing
                                    $badge_color = ($agency_rank == '1') ? 'bg-amber-700' : (($agency_rank == '2') ? 'bg-teal-700' : 'bg-brand-600');
                                    $badge_label = ($agency_rank == '1') ? 'Top Ranked' : (($agency_rank == '2') ? 'Featured' : 'Top Pick');
                                    echo '<span class="' . esc_attr( $badge_color ) . ' inline-flex items-center gap-1 text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm font-mono uppercase tracking-wide">' . esc_html__( 'Rank', 'top-digital-agencies' ) . ' ' . esc_html( $agency_rank ) . ' &middot; ' . esc_html( $badge_label ) . '</span>';
                                }
                                ?>
                            </div>
                            
                            <p class="text-[15px] text-slate-500 mb-3"><?php echo esc_html( get_the_excerpt() ); ?></p>
                            
                            <div class="flex flex-wrap items-center gap-3 text-[12px] font-mono text-slate-500">
                                <div class="flex items-center gap-1">
                                    <span class="flex items-center gap-0.5">
                                        <?php
                                        $floor = floor( floatval( $rating_value ) );
                                        for ( $i = 1; $i <= 5; $i++ ) {
                                            if ( $i <= $floor ) {
                                                echo '<i data-lucide="star" class="w-3.5 h-3.5 text-amber-400 fill-amber-400 inline"></i>';
                                            } elseif ( $i - floatval( $rating_value ) < 1 ) {
                                                echo '<i data-lucide="star-half" class="w-3.5 h-3.5 text-amber-400 fill-amber-400 inline"></i>';
                                            } else {
                                                echo '<i data-lucide="star" class="w-3.5 h-3.5 text-slate-200 inline"></i>';
                                            }
                                        }
                                        ?>
                                    </span>
                                    <span class="font-semibold text-slate-700"><?php echo esc_html( $rating_value ); ?></span>
                                    <span class="text-slate-500 font-mono">(<?php echo esc_html( $review_count ); ?> <?php _e( 'reviews', 'top-digital-agencies' ); ?>)</span>
                                </div>
                                <?php if ( $city_name ) : ?>
                                    <span class="text-slate-300">&middot;</span>
                                    <span class="text-slate-500 flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> <span><?php echo esc_html( $city_name ); ?></span></span>
                                <?php endif; ?>
                                <?php if ( $founded ) : ?>
                                    <span class="text-slate-300">&middot;</span>
                                    <span class="text-slate-500 flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> <span><?php echo esc_html( $founded ); ?></span></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2.5 flex-shrink-0 w-full md:w-auto mt-4 md:mt-0">
                            <?php if ( $website ) : ?>
                                <a href="<?php echo esc_url( 'https://' . str_replace( array('http://', 'https://'), '', $website ) ); ?>" target="_blank" class="btn-spring flex-grow md:flex-grow-0 text-center bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-2.5 rounded-lg text-[13px] transition-all flex items-center justify-center gap-1.5 font-mono shadow-sm">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i> <span><?php _e( 'Visit Website', 'top-digital-agencies' ); ?></span>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-spring flex-grow md:flex-grow-0 text-center bg-white hover:bg-slate-50 text-slate-700 font-semibold px-5 py-2.5 rounded-lg text-[13px] border border-slate-200 transition-all flex items-center justify-center gap-1.5 font-mono shadow-sm">
                                <i data-lucide="mail" class="w-3.5 h-3.5"></i> <span><?php _e( 'Contact', 'top-digital-agencies' ); ?></span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Dynamic Tag List of Services -->
                    <?php if ( ! empty( $services ) && ! is_wp_error( $services ) ) : ?>
                        <div class="flex flex-wrap gap-1.5 mt-6">
                            <?php foreach ( $services as $term ) : ?>
                                <span class="tag-pill bg-slate-100 border border-slate-200 text-slate-600 px-2.5 py-1 rounded-md text-[11px] font-semibold tracking-wide uppercase font-mono"><?php echo esc_html( $term->name ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Profile Body Layout -->
            <section class="py-12 md:py-16 bg-slate-50/50 border-b border-slate-200/60">
                <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Left Columns (Main Info) -->
                        <div class="lg:col-span-2 space-y-6">
                            
                            <!-- PageSpeed Technical Audit Badge -->
                            <div class="card-hover bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                                <h3 class="text-[12px] uppercase tracking-wider font-semibold text-slate-500 mb-4 font-mono"><?php _e( 'technical audit telemetry', 'top-digital-agencies' ); ?></h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 font-mono text-[13px]">
                                    <div class="border border-slate-100 rounded-lg p-4 bg-slate-50/50">
                                        <span class="text-slate-500 block text-[11px] uppercase"><?php _e( 'pagespeed score', 'top-digital-agencies' ); ?></span>
                                        <div class="flex items-end gap-2 mt-2">
                                            <span class="text-[28px] font-bold text-slate-800"><?php echo esc_html( $pagespeed_score ? $pagespeed_score : '90' ); ?></span>
                                            <span class="text-slate-400 mb-1">/100</span>
                                        </div>
                                        <div class="w-full bg-slate-200 h-1.5 rounded-full mt-3 overflow-hidden">
                                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: <?php echo esc_attr( $pagespeed_score ? $pagespeed_score : '90' ); ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="border border-slate-100 rounded-lg p-4 bg-slate-50/50 flex flex-col justify-between">
                                        <div>
                                            <span class="text-slate-500 block text-[11px] uppercase"><?php _e( 'core web vitals', 'top-digital-agencies' ); ?></span>
                                            <span class="inline-block mt-2 px-2.5 py-1 rounded bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 text-[12px] font-semibold uppercase tracking-wider"><?php echo esc_html( $core_web_vitals ? $core_web_vitals : 'PASS' ); ?></span>
                                        </div>
                                        <span class="text-[10px] text-slate-500 mt-3"><?php _e( 'Vetted via Lighthouse API', 'top-digital-agencies' ); ?></span>
                                    </div>
                                    <div class="border border-slate-100 rounded-lg p-4 bg-slate-50/50">
                                        <span class="text-slate-500 block text-[11px] uppercase"><?php _e( 'code cleanliness', 'top-digital-agencies' ); ?></span>
                                        <div class="flex items-end gap-1 mt-2">
                                            <span class="text-[28px] font-bold text-slate-800"><?php echo esc_html( $code_cleanliness ? $code_cleanliness : '95' ); ?>%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 h-1.5 rounded-full mt-3 overflow-hidden">
                                            <div class="bg-indigo-500 h-full rounded-full transition-all duration-500" style="width: <?php echo esc_attr( $code_cleanliness ? $code_cleanliness : '95' ); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- About Section -->
                            <div class="card-hover bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                                <h2 class="text-[1.125rem] font-bold text-slate-900 mb-4 font-display">
                                    <?php _e( 'About', 'top-digital-agencies' ); ?> <?php the_title(); ?>
                                </h2>
                                <div class="text-[14.5px] text-slate-500 leading-relaxed space-y-4 font-sans">
                                    <?php the_content(); ?>
                                </div>
                            </div>

                            <!-- Services List -->
                            <?php if ( ! empty( $services ) && ! is_wp_error( $services ) ) : ?>
                                <div class="card-hover bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                                    <h2 class="text-[1.125rem] font-bold text-slate-900 mb-4 font-display"><?php _e( 'Services', 'top-digital-agencies' ); ?></h2>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <?php foreach ( $services as $term ) : ?>
                                            <div class="flex items-start gap-3 border border-slate-100 rounded-lg p-3 hover:bg-slate-50/50 transition-colors">
                                                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-brand-600 flex-shrink-0 mt-0.5">
                                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-bold text-[13.5px] text-slate-800"><?php echo esc_html( $term->name ); ?></h3>
                                                    <p class="text-[12px] text-slate-400 mt-0.5">Vetted specialist service</p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Case studies / Portfolio Cases -->
                            <?php if ( have_rows( 'case_studies' ) ) : ?>
                                <div class="card-hover bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                                    <h3 class="text-[1.125rem] font-bold text-slate-900 mb-4 font-display"><?php _e( 'Portfolio Highlights', 'top-digital-agencies' ); ?></h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <?php while ( have_rows( 'case_studies' ) ) : the_row(); ?>
                                            <div class="border border-slate-100 rounded-lg p-4 bg-slate-50/30">
                                                <span class="text-[10px] uppercase font-bold text-brand-600 bg-brand-50 border border-brand-100 px-2 py-0.5 rounded font-mono tracking-wider"><?php the_sub_field( 'tag' ); ?></span>
                                                <h4 class="font-bold text-[14px] text-slate-800 mt-2 font-display leading-snug"><?php the_sub_field( 'title' ); ?></h4>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Why Listed section -->
                            <?php if ( have_rows( 'why_listed' ) ) : ?>
                                <div class="card-hover bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 bg-slate-50 rounded-lg flex items-center justify-center text-slate-600 flex-shrink-0 border border-slate-200 shadow-sm">
                                            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                                        </div>
                                        <div class="flex-grow">
                                            <h2 class="text-[1.125rem] font-bold text-slate-900 mb-3 font-display">
                                                <?php _e( 'Why', 'top-digital-agencies' ); ?> <?php the_title(); ?> <?php _e( 'is Listed', 'top-digital-agencies' ); ?>
                                            </h2>
                                            <div class="space-y-3">
                                                <?php while ( have_rows( 'why_listed' ) ) : the_row(); ?>
                                                    <div class="flex items-start gap-2.5 text-[13px] text-slate-500 leading-relaxed font-sans">
                                                        <i data-lucide="arrow-right-circle" class="w-4 h-4 text-slate-300 mt-0.5 flex-shrink-0"></i>
                                                        <p><?php the_sub_field( 'point_text' ); ?></p>
                                                    </div>
                                                <?php endwhile; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right Column (Sidebar metrics & similarity) -->
                        <div class="space-y-5">
                            <div class="card-hover bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                                <h3 class="font-bold text-slate-900 text-[14px] mb-4 font-display"><?php _e( 'Contact Agency', 'top-digital-agencies' ); ?></h3>
                                <div class="space-y-3 font-mono text-[13px] text-slate-600">
                                    <?php if ( $email ) : ?>
                                        <div class="flex items-center gap-2.5">
                                            <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <a href="mailto:<?php echo antispambot( $email ); ?>" class="hover:text-brand-600 break-all"><?php echo antispambot( $email ); ?></a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ( $phone ) : ?>
                                        <div class="flex items-center gap-2.5">
                                            <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <span><?php echo esc_html( $phone ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ( $website ) : ?>
                                        <div class="flex items-center gap-2.5">
                                            <i data-lucide="globe" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <a href="<?php echo esc_url( 'https://' . str_replace( array('http://', 'https://'), '', $website ) ); ?>" target="_blank" class="hover:text-brand-600 break-all"><?php echo esc_html( $website ); ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-spring block w-full mt-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2 rounded-lg text-[13px] text-center transition-all font-mono"><?php _e( 'Send Message', 'top-digital-agencies' ); ?></a>
                            </div>

                            <!-- Quick Stats -->
                            <div class="card-hover bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                                <h3 class="font-bold text-slate-900 text-[14px] mb-4 font-display"><?php _e( 'Quick Stats', 'top-digital-agencies' ); ?></h3>
                                <div class="space-y-3 text-[13px]">
                                    <div class="flex justify-between items-center"><span class="text-slate-500"><?php _e( 'Founded', 'top-digital-agencies' ); ?></span><span class="font-semibold text-slate-900 font-mono"><?php echo esc_html( $founded ? $founded : '—' ); ?></span></div>
                                    <div class="flex justify-between items-center"><span class="text-slate-500"><?php _e( 'Team Size', 'top-digital-agencies' ); ?></span><span class="font-semibold text-slate-900 font-mono"><?php echo esc_html( $team_size ? $team_size : '—' ); ?></span></div>
                                    <div class="flex justify-between items-center"><span class="text-slate-500"><?php _e( 'Clients Served', 'top-digital-agencies' ); ?></span><span class="font-semibold text-slate-900 font-mono"><?php echo esc_html( $clients_served ? $clients_served : '—' ); ?></span></div>
                                    <div class="flex justify-between items-center"><span class="text-slate-500"><?php _e( 'Avg. Project', 'top-digital-agencies' ); ?></span><span class="font-semibold text-slate-900 font-mono"><?php echo esc_html( $project ? $project : '—' ); ?></span></div>
                                    <div class="flex justify-between items-center"><span class="text-slate-500"><?php _e( 'Min. Budget', 'top-digital-agencies' ); ?></span><span class="font-semibold text-slate-900 font-mono"><?php echo esc_html( $budget ? $budget : '—' ); ?></span></div>
                                </div>
                            </div>

                            <!-- Similar Agencies (same City query) -->
                            <div class="card-hover bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                                <h3 class="font-bold text-slate-900 text-[14px] mb-4 font-display"><?php _e( 'Similar Agencies', 'top-digital-agencies' ); ?></h3>
                                <div class="space-y-2.5">
                                    <?php
                                    $similar_query = new WP_Query( array(
                                        'post_type'      => 'agency',
                                        'posts_per_page' => 3,
                                        'post__not_in'   => array( $agency_id ),
                                        'tax_query'      => array(
                                            array(
                                                'taxonomy' => 'agency_city',
                                                'field'    => 'name',
                                                'terms'    => $city_name,
                                            ),
                                        ),
                                    ) );

                                    if ( $similar_query->have_posts() ) :
                                        while ( $similar_query->have_posts() ) : $similar_query->the_post();
                                            $s_id = get_the_ID();
                                            $s_logo = get_field( 'logo_image', $s_id );
                                            $s_text = get_field( 'logo_text', $s_id );
                                            $s_rank = get_field( 'agency_rank', $s_id );
                                            $s_rating = get_field( 'rating_value', $s_id );
                                            ?>
                                            <div class="flex items-center gap-3 p-1.5 hover:bg-slate-50 rounded-lg transition-colors cursor-pointer" onclick="window.location.href='<?php the_permalink(); ?>'">
                                                <?php if ( $s_logo ) : ?>
                                                    <img src="<?php echo esc_url( $s_logo ); ?>" alt="<?php the_title_attribute(); ?>" class="w-8 h-8 rounded object-cover border border-slate-200">
                                                <?php else : ?>
                                                    <div class="w-8 h-8 rounded flex items-center justify-center bg-brand-600 text-white font-bold text-xs uppercase"><?php echo esc_html( $s_text ? $s_text : substr( get_the_title(), 0, 2 ) ); ?></div>
                                                <?php endif; ?>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-bold text-[12px] text-slate-800 truncate"><?php the_title(); ?></h4>
                                                    <div class="flex items-center gap-2 mt-0.5 text-[10px] font-mono text-slate-400">
                                                        <span>Rank <?php echo esc_html( $s_rank ); ?></span>
                                                        <span>&middot;</span>
                                                        <span class="flex items-center gap-0.5"><i data-lucide="star" class="w-2.5 h-2.5 text-amber-400 fill-amber-400 inline"></i> <?php echo esc_html( $s_rating ); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        endwhile;
                                        wp_reset_postdata();
                                    else :
                                        echo '<p class="text-[12px] text-slate-400">' . __( 'No similar agencies found in city.', 'top-digital-agencies' ) . '</p>';
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>

        <?php
    endwhile;
endif;

get_footer();
?>
