<?php
/**
 * Template Name: Rankings Template
 */

get_header();

// Fetch all agencies ordered by rank
$args = array(
    'post_type'      => 'agency',
    'posts_per_page' => -1,
    'meta_key'       => 'agency_rank',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
    'post_status'    => 'publish',
);
$rankings_query = new WP_Query( $args );
?>

<div class="page">
    <section class="relative z-10 bg-white/80 border-b border-slate-200">
        <div class="max-w-3xl mx-auto px-5 sm:px-6 lg:px-8 pt-16 pb-12 md:pt-20 md:pb-16 text-left">
            <!-- Breadcrumbs -->
            <div class="flex justify-start items-center gap-1.5 text-[11px] font-semibold text-slate-400 mb-4 tracking-wider uppercase font-sans">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cursor-pointer hover:text-slate-900 transition-colors"><?php _e( 'HOME', 'top-digital-agencies' ); ?></a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                <span class="text-slate-900 font-semibold uppercase"><?php _e( 'RANKINGS', 'top-digital-agencies' ); ?></span>
            </div>
            
            <span class="section-label text-[10px] bg-slate-100 border border-slate-200 text-slate-600 px-2 py-0.5 rounded font-mono font-bold tracking-wider uppercase inline-block mb-3">
                <?php _e( '2024 Ranking', 'top-digital-agencies' ); ?>
            </span>
            
            <h1 class="hero-title text-[2.25rem] md:text-[3.5rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-5 font-display">
                <?php _e( 'Top Digital Marketing Agencies in Morocco', 'top-digital-agencies' ); ?>
            </h1>
            
            <p class="text-[16px] md:text-[18px] text-slate-500 leading-relaxed max-w-xl">
                <?php _e( 'After evaluating 150+ agencies across Morocco, our independent research team has identified the top performers based on verified client reviews, portfolio quality, market presence, and team expertise.', 'top-digital-agencies' ); ?>
            </p>
            
            <div class="flex flex-wrap items-center gap-4 mt-6 text-[11px] text-slate-400 font-mono">
                <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> <span><?php _e( 'Updated June 2024', 'top-digital-agencies' ); ?></span></span>
                <span>&middot;</span>
                <span class="flex items-center gap-1"><i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i> <span><?php _e( 'Research verified', 'top-digital-agencies' ); ?></span></span>
            </div>
        </div>
    </section>
    
    <section class="py-12 md:py-16 bg-slate-50/50 border-b border-slate-200/60">
        <div class="max-w-3xl mx-auto px-5 sm:px-6 lg:px-8">
            
            <!-- Leaderboard Quick Overview Table -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-10 shadow-sm">
                <div class="px-5 py-3.5 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-slate-900 text-[13px] font-display uppercase tracking-wider text-slate-400 font-mono"><?php _e( 'Quick Overview', 'top-digital-agencies' ); ?></h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-[13px] text-left">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/30">
                                <th class="px-3 py-3 sm:px-5 font-semibold text-slate-400 text-[10px] uppercase tracking-wider font-mono"><?php _e( 'Rank', 'top-digital-agencies' ); ?></th>
                                <th class="px-3 py-3 sm:px-5 font-semibold text-slate-400 text-[10px] uppercase tracking-wider font-mono"><?php _e( 'Agency', 'top-digital-agencies' ); ?></th>
                                <th class="px-3 py-3 sm:px-5 font-semibold text-slate-400 text-[10px] uppercase tracking-wider font-mono"><?php _e( 'City', 'top-digital-agencies' ); ?></th>
                                <th class="px-3 py-3 sm:px-5 font-semibold text-slate-400 text-[10px] uppercase tracking-wider font-mono"><?php _e( 'Rating', 'top-digital-agencies' ); ?></th>
                                <th class="px-3 py-3 sm:px-5 font-semibold text-slate-400 text-[10px] uppercase tracking-wider font-mono"><?php _e( 'Best For', 'top-digital-agencies' ); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            if ( $rankings_query->have_posts() ) :
                                while ( $rankings_query->have_posts() ) : $rankings_query->the_post();
                                    $id = get_the_ID();
                                    $rank = get_field( 'agency_rank', $id );
                                    $rating = get_field( 'rating_value', $id );
                                    
                                    // City
                                    $cities = get_the_terms( $id, 'agency_city' );
                                    $city_name = ( ! empty( $cities ) && ! is_wp_error( $cities ) ) ? $cities[0]->name : '';

                                    // Best for (first taxonomy service name as fallback or description)
                                    $services = get_the_terms( $id, 'agency_service' );
                                    $best_for = ( ! empty( $services ) && ! is_wp_error( $services ) ) ? $services[0]->name : __( 'Growth', 'top-digital-agencies' );
                                    ?>
                                    <tr class="hover:bg-slate-50/50 cursor-pointer" onclick="window.location.href='<?php the_permalink(); ?>'">
                                        <td class="px-3 py-3.5 sm:px-5 font-mono font-bold text-slate-800">#<?php echo esc_html( $rank ); ?></td>
                                        <td class="px-3 py-3.5 sm:px-5 font-semibold text-slate-900"><?php the_title(); ?></td>
                                        <td class="px-3 py-3.5 sm:px-5 text-slate-500 font-mono"><?php echo esc_html( $city_name ); ?></td>
                                        <td class="px-3 py-3.5 sm:px-5 font-mono text-slate-700">
                                            <div class="flex items-center gap-1">
                                                <i data-lucide="star" class="w-3.5 h-3.5 text-amber-400 fill-amber-400"></i>
                                                <span class="font-bold"><?php echo esc_html( $rating ); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3.5 sm:px-5 text-slate-500 font-mono italic"><?php echo esc_html( $best_for ); ?></td>
                                    </tr>
                                    <?php
                                endwhile;
                            else :
                                ?>
                                <tr>
                                    <td colspan="5" class="px-5 py-4 text-center text-slate-400 font-mono text-[12px]"><?php _e( 'No ranked listings loaded in database.', 'top-digital-agencies' ); ?></td>
                                </tr>
                                <?php
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detailed ranking cards list -->
            <div class="space-y-6 mb-12">
                <?php
                if ( $rankings_query->have_posts() ) :
                    $rankings_query->rewind_posts();
                    while ( $rankings_query->have_posts() ) : $rankings_query->the_post();
                        $id = get_the_ID();
                        $logo_text = get_field( 'logo_text', $id );
                        $logo_image = get_field( 'logo_image', $id );
                        $rank = get_field( 'agency_rank', $id );
                        $rating = get_field( 'rating_value', $id );
                        $reviews = get_field( 'review_count', $id );
                        $budget = get_field( 'budget', $id );
                        
                        $cities = get_the_terms( $id, 'agency_city' );
                        $city_name = ( ! empty( $cities ) && ! is_wp_error( $cities ) ) ? $cities[0]->name : '';
                        
                        $services = get_the_terms( $id, 'agency_service' );
                        ?>
                        <div class="card-hover bg-white rounded-xl border border-slate-200 p-5 md:p-6 flex flex-col sm:flex-row gap-5 shadow-sm">
                            <div class="flex-shrink-0 flex items-start gap-4">
                                <span class="font-mono text-[24px] font-extrabold text-slate-300 leading-none">#<?php echo esc_html( $rank ); ?></span>
                                <?php if ( $logo_image ) : ?>
                                    <img src="<?php echo esc_url( $logo_image ); ?>" alt="<?php the_title_attribute(); ?>" class="w-14 h-14 rounded-lg object-cover border border-slate-100 bg-white">
                                <?php else : ?>
                                    <div class="w-14 h-14 rounded-lg flex items-center justify-center bg-brand-600 text-white font-bold text-sm uppercase"><?php echo esc_html( $logo_text ? $logo_text : substr( get_the_title(), 0, 2 ) ); ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                                    <h3 class="font-extrabold text-[16px] text-slate-900 font-display hover:text-brand-600 cursor-pointer transition-colors" onclick="window.location.href='<?php the_permalink(); ?>'"><?php the_title(); ?></h3>
                                    
                                    <div class="flex items-center gap-1">
                                        <span class="flex items-center gap-0.5">
                                            <?php
                                            $floor = floor( floatval( $rating ) );
                                            for ( $i = 1; $i <= 5; $i++ ) {
                                                if ( $i <= $floor ) {
                                                    echo '<i data-lucide="star" class="w-3.5 h-3.5 text-amber-400 fill-amber-400 inline"></i>';
                                                } elseif ( $i - floatval( $rating ) < 1 ) {
                                                    echo '<i data-lucide="star-half" class="w-3.5 h-3.5 text-amber-400 fill-amber-400 inline"></i>';
                                                } else {
                                                    echo '<i data-lucide="star" class="w-3.5 h-3.5 text-slate-200 inline"></i>';
                                                }
                                            }
                                            ?>
                                        </span>
                                        <span class="font-bold text-slate-700 text-[12px] font-mono ml-1"><?php echo esc_html( $rating ); ?></span>
                                        <span class="text-slate-400 text-[11px] font-mono">(<?php echo esc_html( $reviews ); ?>)</span>
                                    </div>
                                </div>
                                
                                <p class="text-[13.5px] text-slate-500 leading-relaxed mb-4"><?php echo esc_html( get_the_excerpt() ); ?></p>
                                
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-mono text-slate-400">
                                    <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> <span><?php echo esc_html( $city_name ); ?></span></span>
                                    <span>&middot;</span>
                                    <span class="flex items-center gap-1"><i data-lucide="wallet" class="w-3.5 h-3.5"></i> <span><?php echo esc_html( $budget ); ?></span></span>
                                    <?php if ( ! empty( $services ) && ! is_wp_error( $services ) ) : ?>
                                        <span>&middot;</span>
                                        <span class="truncate max-w-[200px]"><?php echo esc_html( $services[0]->name ); ?> & more</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>

            <!-- Footer Methodology Box -->
            <div class="card-hover bg-white rounded-xl border border-slate-200 p-6 md:p-8 mb-12 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-slate-50 text-slate-600 rounded-lg flex items-center justify-center flex-shrink-0 border border-slate-200">
                        <i data-lucide="shield-check" class="w-5 h-5 text-emerald-500"></i>
                    </div>
                    <div>
                        <h2 class="text-[1.125rem] font-bold text-slate-900 mb-2 font-display"><?php _e( 'How We Chose These Agencies', 'top-digital-agencies' ); ?></h2>
                        <p class="text-[13.5px] text-slate-500 mb-4 leading-relaxed"><?php _e( 'Our ranking methodology is transparent and independent. We do not accept payment for placement. Agencies are evaluated on four core criteria:', 'top-digital-agencies' ); ?></p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-[13px]">
                            <div class="bg-slate-50/50 rounded-lg p-4 border border-slate-200/60">
                                <div class="font-semibold text-slate-900 text-[13px] mb-1 font-display"><?php _e( '1. Verified Client Reviews', 'top-digital-agencies' ); ?></div>
                                <p class="text-[12px] text-slate-500 leading-relaxed"><?php _e( 'We collect and verify reviews from real clients, weighted by project complexity and budget.', 'top-digital-agencies' ); ?></p>
                            </div>
                            <div class="bg-slate-50/50 rounded-lg p-4 border border-slate-200/60">
                                <div class="font-semibold text-slate-900 text-[13px] mb-1 font-display"><?php _e( '2. Portfolio Quality', 'top-digital-agencies' ); ?></div>
                                <p class="text-[12px] text-slate-500 leading-relaxed"><?php _e( 'Our team evaluates case studies, work samples, and measurable results demonstrated.', 'top-digital-agencies' ); ?></p>
                            </div>
                            <div class="bg-slate-50/50 rounded-lg p-4 border border-slate-200/60">
                                <div class="font-semibold text-slate-900 text-[13px] mb-1 font-display"><?php _e( '3. Market Presence', 'top-digital-agencies' ); ?></div>
                                <p class="text-[12px] text-slate-500 leading-relaxed"><?php _e( 'Years in business, team size, industry recognition, and thought leadership.', 'top-digital-agencies' ); ?></p>
                            </div>
                            <div class="bg-slate-50/50 rounded-lg p-4 border border-slate-200/60">
                                <div class="font-semibold text-slate-900 text-[13px] mb-1 font-display"><?php _e( '4. Service Breadth', 'top-digital-agencies' ); ?></div>
                                <p class="text-[12px] text-slate-500 leading-relaxed"><?php _e( 'Range of services offered, specialization depth, and cross-channel expertise.', 'top-digital-agencies' ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accordeon FAQ -->
            <div>
                <h2 class="text-[1.5rem] font-bold text-slate-900 mb-5 font-display"><?php _e( 'Frequently Asked Questions', 'top-digital-agencies' ); ?></h2>
                <div class="space-y-3">
                    <div class="card-hover bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between p-5 text-left hover:bg-slate-50 transition-colors">
                            <span class="font-semibold text-slate-900 text-[14px]"><?php _e( 'How often are these rankings updated?', 'top-digital-agencies' ); ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transform transition-transform flex-shrink-0"></i>
                        </button>
                        <div class="faq-content px-5">
                            <p class="text-slate-500 pb-5 text-[13px] leading-relaxed font-sans"><?php _e( 'Our rankings are updated quarterly. We continuously collect new reviews, monitor agency performance, and reassess portfolios. Major updates are published in March, June, September, and December each year.', 'top-digital-agencies' ); ?></p>
                        </div>
                    </div>
                    <div class="card-hover bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between p-5 text-left hover:bg-slate-50 transition-colors">
                            <span class="font-semibold text-slate-900 text-[14px]"><?php _e( 'Can agencies pay to be ranked higher?', 'top-digital-agencies' ); ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transform transition-transform flex-shrink-0"></i>
                        </button>
                        <div class="faq-content px-5">
                            <p class="text-slate-500 pb-5 text-[13px] leading-relaxed font-sans"><?php _e( 'No. Our rankings are 100% independent and based solely on merit. Agencies can purchase enhanced profile features, but this never influences their ranking position. We maintain strict editorial independence.', 'top-digital-agencies' ); ?></p>
                        </div>
                    </div>
                    <div class="card-hover bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between p-5 text-left hover:bg-slate-50 transition-colors">
                            <span class="font-semibold text-slate-900 text-[14px]"><?php _e( 'How do you verify client reviews?', 'top-digital-agencies' ); ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transform transition-transform flex-shrink-0"></i>
                        </button>
                        <div class="faq-content px-5">
                            <p class="text-slate-500 pb-5 text-[13px] leading-relaxed font-sans"><?php _e( 'We verify reviews through a multi-step process: email verification of the reviewer, LinkedIn profile confirmation, project documentation review, and direct follow-up calls for high-value projects. Fake reviews are removed.', 'top-digital-agencies' ); ?></p>
                        </div>
                    </div>
                    <div class="card-hover bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between p-5 text-left hover:bg-slate-50 transition-colors">
                            <span class="font-semibold text-slate-900 text-[14px]"><?php _e( 'What budget should I expect for a top-ranked agency?', 'top-digital-agencies' ); ?></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transform transition-transform flex-shrink-0"></i>
                        </button>
                        <div class="faq-content px-5">
                            <p class="text-slate-500 pb-5 text-[13px] leading-relaxed font-sans"><?php _e( 'Top-ranked agencies typically require minimum monthly retainers starting at 15,000-25,000 MAD for ongoing services. Project-based work (like website design) usually starts at 50,000 MAD.', 'top-digital-agencies' ); ?></p>
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
