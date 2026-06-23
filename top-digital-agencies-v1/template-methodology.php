<?php
/**
 * Template Name: Methodology Page Template
 */

get_header();
?>

<div class="page">
    <!-- Hero Header Section (Left-biased) -->
    <section class="relative z-10 bg-white/80 border-b border-slate-200 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8 pt-16 pb-12 md:pt-20 md:pb-16 text-left">
            <div class="flex justify-start items-center gap-1.5 text-[12px] text-slate-400 mb-4 font-mono">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cursor-pointer hover:text-slate-900 transition-colors"><?php _e( 'HOME', 'top-digital-agencies' ); ?></a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900 font-semibold uppercase"><?php _e( 'METHODOLOGY', 'top-digital-agencies' ); ?></span>
            </div>
            <h1 class="hero-title text-[2.25rem] md:text-[3.5rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-5 font-display">
                <?php _e( 'Our', 'top-digital-agencies' ); ?> <span class="text-brand-600"><?php _e( 'Methodology', 'top-digital-agencies' ); ?><span class="text-slate-900">.</span></span>
            </h1>
            <p class="text-[16px] md:text-[18px] text-slate-500 leading-relaxed max-w-xl">
                <?php _e( 'How we research, evaluate, and rank digital marketing agencies in Morocco.', 'top-digital-agencies' ); ?>
            </p>
        </div>
    </section>

    <!-- Overview Section (Asymmetric split grid) -->
    <section class="py-16 md:py-20 bg-slate-50/50 border-b border-slate-200/60 scroll-stage">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">
                <div class="lg:col-span-8">
                    <span class="section-label text-slate-850 mb-2 block"><?php _e( 'Our Framework', 'top-digital-agencies' ); ?></span>
                    <h2 class="text-[1.75rem] md:text-[2.25rem] font-bold text-slate-900 tracking-tight leading-tight mb-5 font-display"><?php _e( 'Overview', 'top-digital-agencies' ); ?></h2>
                    <p class="text-[15px] md:text-[16px] text-slate-500 leading-relaxed mb-4">
                        <?php _e( 'Our evaluation process is designed to be comprehensive, consistent, and fair. Every agency in our directory has been through the same research protocol, regardless of whether they applied for listing or were discovered by our research team.', 'top-digital-agencies' ); ?>
                    </p>
                    <p class="text-[15px] md:text-[16px] text-slate-500 leading-relaxed">
                        <?php _e( 'The process takes 2-4 weeks per agency and involves four stages: initial screening, portfolio audit, client verification, and final scoring. Agencies that fail any stage are not listed.', 'top-digital-agencies' ); ?>
                    </p>

                    <!-- Content from page editor if exists -->
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <?php if ( get_the_content() ) : ?>
                            <div class="prose prose-slate max-w-none text-[15px] text-slate-500 mt-6 pt-6 border-t border-slate-200">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; endif; ?>
                </div>

                <div class="lg:col-span-4 lg:pl-10 lg:border-l border-slate-200/80">
                    <span class="section-label text-slate-400 mb-6 block"><?php _e( 'Telemetry Metrics', 'top-digital-agencies' ); ?></span>
                    <div class="space-y-6">
                        <!-- Line 1: Protocol -->
                        <div class="border-b border-slate-200/80 pb-5">
                            <span class="block font-sans text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5"><?php _e( 'PROTOCOL', 'top-digital-agencies' ); ?></span>
                            <div class="flex items-center gap-2 font-display text-[20px] font-bold text-slate-900">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                                <span><?php _e( 'STRICT', 'top-digital-agencies' ); ?></span>
                            </div>
                        </div>

                        <!-- Line 2: Duration -->
                        <div class="border-b border-slate-200/80 pb-5">
                            <span class="block font-sans text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5"><?php _e( 'TIMELINE', 'top-digital-agencies' ); ?></span>
                            <span class="block font-display text-[20px] font-bold text-slate-900"><?php _e( '2-4 WEEKS', 'top-digital-agencies' ); ?></span>
                        </div>

                        <!-- Line 3: Range -->
                        <div class="border-b border-slate-200/80 pb-5">
                            <span class="block font-sans text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5"><?php _e( 'RANGE', 'top-digital-agencies' ); ?></span>
                            <span class="block font-display text-[20px] font-bold text-slate-900"><?php _e( 'MOROCCO', 'top-digital-agencies' ); ?></span>
                        </div>

                        <!-- Line 4: Metric -->
                        <div>
                            <span class="block font-sans text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5"><?php _e( 'METRIC', 'top-digital-agencies' ); ?></span>
                            <span class="block font-display text-[20px] font-bold text-brand-600"><?php _e( 'WEIGHTED', 'top-digital-agencies' ); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4-Stage Interactive Timeline Accordion -->
    <section class="py-16 md:py-24 bg-white/90 border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="space-y-4">
                
                <!-- Stage 1 -->
                <div class="border border-slate-200 rounded-xl bg-white overflow-hidden scroll-stage methodology-stage" id="stage-1">
                    <button onclick="toggleMethodologyStage(1)" class="w-full text-left px-6 py-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-600/20" aria-expanded="true" aria-controls="stage-1-content">
                        <div class="flex items-center gap-4">
                            <span class="text-[13px] font-bold text-slate-400 font-sans">01</span>
                            <span class="font-bold text-[16px] md:text-[18px] text-slate-900 font-display"><?php _e( 'Initial Screening', 'top-digital-agencies' ); ?></span>
                        </div>
                        <span class="accordion-icon font-sans text-[18px] text-slate-400 transition-transform duration-300 transform rotate-45">—</span>
                    </button>
                    <div id="stage-1-content" class="methodology-accordion-content border-t border-slate-100 p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                            <div class="lg:col-span-5">
                                <div class="mb-5 bg-slate-50 border border-slate-200/80 rounded-xl p-4 flex items-center justify-center">
                                    <svg class="w-3/4 max-w-[220px] h-auto text-slate-800" viewBox="0 0 220 120" fill="none" stroke="currentColor">
                                        <rect x="40" y="25" width="140" height="70" rx="4" stroke="#e2e8f0" stroke-width="1.5" />
                                        <rect x="50" y="35" width="120" height="50" rx="2" stroke="#0f172a" stroke-width="2" />
                                        <line x1="65" y1="50" x2="125" y2="50" stroke="#e2e8f0" stroke-width="1.5" />
                                        <line x1="65" y1="60" x2="105" y2="60" stroke="#e2e8f0" stroke-width="1.5" />
                                        <line x1="65" y1="70" x2="90" y2="70" stroke="#e2e8f0" stroke-width="1.5" />
                                        <circle cx="145" cy="60" r="12" stroke="#2563eb" stroke-width="2" />
                                        <path d="M139 60l4 4 8-8" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <p class="text-[14px] text-slate-500 leading-relaxed"><?php _e( 'Before any deep research begins, we verify that an agency meets our minimum eligibility criteria:', 'top-digital-agencies' ); ?></p>
                            </div>
                            <div class="lg:col-span-7 bg-slate-50/50 p-5 rounded-xl border border-slate-200/80 text-[13.5px] text-slate-600 font-sans space-y-3">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Moroccan Presence:', 'top-digital-agencies' ); ?></strong> <?php _e( 'Must have a registered physical office and business record (Registre de Commerce) in Morocco.', 'top-digital-agencies' ); ?></span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Activity Window:', 'top-digital-agencies' ); ?></strong> <?php _e( 'Must have been active for at least 2 full years with a verified business history.', 'top-digital-agencies' ); ?></span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Staff Threshold:', 'top-digital-agencies' ); ?></strong> <?php _e( 'Must employ at least 3 full-time local specialists (not including external freelancers).', 'top-digital-agencies' ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stage 2 -->
                <div class="border border-slate-200 rounded-xl bg-white overflow-hidden scroll-stage methodology-stage" id="stage-2">
                    <button onclick="toggleMethodologyStage(2)" class="w-full text-left px-6 py-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-600/20" aria-expanded="false" aria-controls="stage-2-content">
                        <div class="flex items-center gap-4">
                            <span class="text-[13px] font-bold text-slate-400 font-sans">02</span>
                            <span class="font-bold text-[16px] md:text-[18px] text-slate-900 font-display"><?php _e( 'Portfolio Audit', 'top-digital-agencies' ); ?></span>
                        </div>
                        <span class="accordion-icon font-sans text-[18px] text-slate-400 transition-transform duration-300 transform">+</span>
                    </button>
                    <div id="stage-2-content" class="methodology-accordion-content hidden border-t border-slate-100 p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                            <div class="lg:col-span-5">
                                <div class="mb-5 bg-slate-50 border border-slate-200/80 rounded-xl p-4 flex items-center justify-center">
                                    <svg class="w-3/4 max-w-[220px] h-auto text-slate-800" viewBox="0 0 220 120" fill="none" stroke="currentColor">
                                        <rect x="30" y="20" width="160" height="80" rx="4" stroke="#e2e8f0" stroke-width="1.5" />
                                        <path d="M50 40h120M50 60h80M50 80h100" stroke="#0f172a" stroke-width="2" stroke-linecap="round" />
                                        <circle cx="160" cy="70" r="15" stroke="#2563eb" stroke-width="2" />
                                        <path d="M150 70h20" stroke="#2563eb" stroke-width="2" />
                                    </svg>
                                </div>
                                <p class="text-[14px] text-slate-500 leading-relaxed"><?php _e( 'We perform a deep dive on the agency\'s claims, case studies, and live deliverables:', 'top-digital-agencies' ); ?></p>
                            </div>
                            <div class="lg:col-span-7 bg-slate-50/50 p-5 rounded-xl border border-slate-200/80 text-[13.5px] text-slate-600 font-sans space-y-3">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Code Cleanliness & Performance:', 'top-digital-agencies' ); ?></strong> <?php _e( 'We audit websites built by the agency, verifying speed, mobile responsive compliance, and structural cleanliness.', 'top-digital-agencies' ); ?></span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Case Study Veracity:', 'top-digital-agencies' ); ?></strong> <?php _e( 'We confirm the agency actually did the work claimed and didn\'t just copy assets from international references.', 'top-digital-agencies' ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stage 3 -->
                <div class="border border-slate-200 rounded-xl bg-white overflow-hidden scroll-stage methodology-stage" id="stage-3">
                    <button onclick="toggleMethodologyStage(3)" class="w-full text-left px-6 py-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-600/20" aria-expanded="false" aria-controls="stage-3-content">
                        <div class="flex items-center gap-4">
                            <span class="text-[13px] font-bold text-slate-400 font-sans">03</span>
                            <span class="font-bold text-[16px] md:text-[18px] text-slate-900 font-display"><?php _e( 'Client Verification', 'top-digital-agencies' ); ?></span>
                        </div>
                        <span class="accordion-icon font-sans text-[18px] text-slate-400 transition-transform duration-300 transform">+</span>
                    </button>
                    <div id="stage-3-content" class="methodology-accordion-content hidden border-t border-slate-100 p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                            <div class="lg:col-span-5">
                                <div class="mb-5 bg-slate-50 border border-slate-200/80 rounded-xl p-4 flex items-center justify-center">
                                    <svg class="w-3/4 max-w-[220px] h-auto text-slate-800" viewBox="0 0 220 120" fill="none" stroke="currentColor">
                                        <circle cx="70" cy="50" r="18" stroke="#0f172a" stroke-width="2" />
                                        <circle cx="150" cy="70" r="18" stroke="#2563eb" stroke-width="2" />
                                        <path d="M88 55h44" stroke="#e2e8f0" stroke-width="2" stroke-dasharray="4 4" />
                                    </svg>
                                </div>
                                <p class="text-[14px] text-slate-500 leading-relaxed"><?php _e( 'The core of our directory is trust. We personally speak to clients:', 'top-digital-agencies' ); ?></p>
                            </div>
                            <div class="lg:col-span-7 bg-slate-50/50 p-5 rounded-xl border border-slate-200/80 text-[13.5px] text-slate-600 font-sans space-y-3">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Email & LinkedIn Verification:', 'top-digital-agencies' ); ?></strong> <?php _e( 'Every single review posted must come from a verified corporate email and match a real professional LinkedIn profile.', 'top-digital-agencies' ); ?></span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Phone Audits for Large Projects:', 'top-digital-agencies' ); ?></strong> <?php _e( 'For projects valued above 50,000 MAD, our editors call the client to verify timeline and satisfaction metrics.', 'top-digital-agencies' ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stage 4 -->
                <div class="border border-slate-200 rounded-xl bg-white overflow-hidden scroll-stage methodology-stage" id="stage-4">
                    <button onclick="toggleMethodologyStage(4)" class="w-full text-left px-6 py-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-600/20" aria-expanded="false" aria-controls="stage-4-content">
                        <div class="flex items-center gap-4">
                            <span class="text-[13px] font-bold text-slate-400 font-sans">04</span>
                            <span class="font-bold text-[16px] md:text-[18px] text-slate-900 font-display"><?php _e( 'Final Scoring', 'top-digital-agencies' ); ?></span>
                        </div>
                        <span class="accordion-icon font-sans text-[18px] text-slate-400 transition-transform duration-300 transform">+</span>
                    </button>
                    <div id="stage-4-content" class="methodology-accordion-content hidden border-t border-slate-100 p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                            <div class="lg:col-span-5">
                                <div class="mb-5 bg-slate-50 border border-slate-200/80 rounded-xl p-4 flex items-center justify-center">
                                    <svg class="w-3/4 max-w-[220px] h-auto text-slate-800" viewBox="0 0 220 120" fill="none" stroke="currentColor">
                                        <rect x="40" y="30" width="140" height="60" rx="4" stroke="#0f172a" stroke-width="2" />
                                        <line x1="90" y1="30" x2="90" y2="90" stroke="#e2e8f0" stroke-width="1.5" />
                                        <circle cx="65" cy="60" r="12" fill="#2563eb" stroke="none" />
                                        <path d="M60 60l3 3 5-5" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <span class="font-display font-extrabold text-[16px] text-slate-900" style="position: absolute; right: 65px; top: 48px;">4.9</span>
                                    </svg>
                                </div>
                                <p class="text-[14px] text-slate-500 leading-relaxed"><?php _e( 'We calculate a weighted score based on four verified criteria:', 'top-digital-agencies' ); ?></p>
                            </div>
                            <div class="lg:col-span-7 bg-slate-50/50 p-5 rounded-xl border border-slate-200/80 text-[13.5px] text-slate-600 font-sans space-y-3">
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Client Reviews (40% weight):', 'top-digital-agencies' ); ?></strong> <?php _e( 'Average rating and number of verified client reviews.', 'top-digital-agencies' ); ?></span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Portfolio Quality (30% weight):', 'top-digital-agencies' ); ?></strong> <?php _e( 'Complexity, tech cleanliness, and verified results of client projects.', 'top-digital-agencies' ); ?></span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Market Presence (20% weight):', 'top-digital-agencies' ); ?></strong> <?php _e( 'Years in business, local team size, and reputation in the Moroccan market.', 'top-digital-agencies' ); ?></span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                                    <span><strong><?php _e( 'Service Breadth (10% weight):', 'top-digital-agencies' ); ?></strong> <?php _e( 'Range of capabilities and specialization depth.', 'top-digital-agencies' ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script>
    function toggleMethodologyStage(stageNum) {
        // Toggle the target content
        const targetContent = document.getElementById('stage-' + stageNum + '-content');
        const targetBtn = document.querySelector('#stage-' + stageNum + ' button');
        const targetIcon = targetBtn.querySelector('.accordion-icon');
        
        const isOpen = !targetContent.classList.contains('hidden');
        
        // Close all stages
        document.querySelectorAll('.methodology-accordion-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.querySelectorAll('.methodology-stage button').forEach(btn => {
            btn.setAttribute('aria-expanded', 'false');
            const icon = btn.querySelector('.accordion-icon');
            if (icon) {
                icon.textContent = '+';
                icon.classList.remove('rotate-45');
            }
        });
        
        // Open this one if it was closed
        if (!isOpen) {
            targetContent.classList.remove('hidden');
            targetBtn.setAttribute('aria-expanded', 'true');
            if (targetIcon) {
                targetIcon.textContent = '—';
                targetIcon.classList.add('rotate-45');
            }
        }
    }
</script>

<?php
get_footer();
