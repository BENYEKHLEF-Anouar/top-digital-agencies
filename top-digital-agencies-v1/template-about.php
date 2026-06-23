<?php
/**
 * Template Name: About Page Template
 */

get_header();
?>

<div class="page">
    <!-- Hero Header Section (Left-biased) -->
    <section class="relative z-10 bg-white/80 border-b border-slate-200 backdrop-blur-sm">
        <div class="max-w-3xl mx-auto px-5 sm:px-6 lg:px-8 pt-16 pb-12 md:pt-20 md:pb-16 text-left">
            <div class="flex justify-start items-center gap-1.5 text-[11px] font-semibold text-slate-400 mb-4 tracking-wider uppercase font-sans">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cursor-pointer hover:text-slate-900 transition-colors"><?php _e( 'HOME', 'top-digital-agencies' ); ?></a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                <span class="text-slate-900 font-semibold"><?php _e( 'ABOUT', 'top-digital-agencies' ); ?></span>
            </div>
            <h1 class="hero-title text-[2.25rem] md:text-[3.5rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-5 font-display">
                <?php _e( 'About', 'top-digital-agencies' ); ?> <span class="text-brand-600"><?php _e( 'Us', 'top-digital-agencies' ); ?><span class="text-slate-900">.</span></span>
            </h1>
            <p class="text-[16px] md:text-[18px] text-slate-500 leading-relaxed">
                <?php _e( 'Morocco\'s first independent directory built to help businesses find agencies they can actually trust.', 'top-digital-agencies' ); ?>
            </p>
        </div>
    </section>

    <!-- Story & Framework Section -->
    <section class="py-12 md:py-16 bg-white/95 border-b border-slate-200/60 scroll-stage">
        <div class="max-w-3xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6 md:p-8 mb-8 shadow-sm">
                <div class="grid md:grid-cols-12 gap-6 md:gap-10 items-center">
                    <div class="md:col-span-7">
                        <h2 class="text-[1.25rem] font-bold text-slate-900 mb-4 font-display"><?php _e( 'Our Story', 'top-digital-agencies' ); ?></h2>
                        <p class="text-[15px] text-slate-500 leading-relaxed mb-4 font-sans"><?php _e( 'Agence Marketing Digital was founded in 2023 by a group of Moroccan marketers, entrepreneurs, and researchers who were tired of seeing businesses waste money on the wrong agencies.', 'top-digital-agencies' ); ?></p>
                        <p class="text-[15px] text-slate-500 leading-relaxed mb-4 font-sans"><?php _e( 'After interviewing over 200 business owners across Casablanca, Rabat, and Tangier, we discovered a consistent pattern: most companies chose their agency based on a friend\'s recommendation or a slick website, without any way to verify quality, compare options, or understand pricing.', 'top-digital-agencies' ); ?></p>
                        <p class="text-[15px] text-slate-500 leading-relaxed font-sans"><?php _e( 'We built this platform to solve that problem. Our team now includes 6 full-time researchers, 2 editors, and a network of industry advisors who help us maintain rigorous standards.', 'top-digital-agencies' ); ?></p>
                    </div>
                    <div class="md:col-span-5 flex items-center justify-center bg-slate-50 border border-slate-200/80 rounded-xl p-6">
                        <svg class="w-full max-w-[240px] h-auto text-slate-800" viewBox="0 0 240 220" fill="none" stroke="currentColor">
                            <rect x="55" y="35" width="110" height="140" rx="10" stroke="currentColor" stroke-width="2" />
                            <rect x="92" y="27" width="36" height="16" rx="4" fill="#ffffff" stroke="currentColor" stroke-width="2" />
                            <circle cx="80" cy="72" r="7" stroke="#2563eb" stroke-width="2" />
                            <path d="M76.5 72l2.5 2.5 4.5-5.5" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <line x1="96" y1="72" x2="150" y2="72" stroke="#e2e8f0" stroke-width="2" stroke-linecap="round" />
                            <circle cx="80" cy="103" r="7" stroke="#2563eb" stroke-width="2" />
                            <path d="M76.5 103l2.5 2.5 4.5-5.5" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <line x1="96" y1="103" x2="142" y2="103" stroke="#e2e8f0" stroke-width="2" stroke-linecap="round" />
                            <circle cx="80" cy="134" r="7" stroke="#10b981" stroke-width="2" />
                            <path d="M76.5 134l2.5 2.5 4.5-5.5" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <line x1="96" y1="134" x2="134" y2="134" stroke="#e2e8f0" stroke-width="2" stroke-linecap="round" />
                            <circle cx="172" cy="152" r="27" fill="#ffffff" stroke="#2563eb" stroke-width="3" />
                            <line x1="191" y1="171" x2="212" y2="192" stroke="#2563eb" stroke-width="5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Page content from editor if exists -->
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php if ( get_the_content() ) : ?>
                    <div class="bg-white rounded-xl border border-slate-200 p-6 md:p-8 mb-8 shadow-sm prose prose-slate max-w-none text-[14.5px] text-slate-600">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>
            <?php endwhile; endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <div class="w-9 h-9 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center mb-4 border border-brand-100 shadow-sm">
                        <i data-lucide="target" class="w-4.5 h-4.5"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-[15px] mb-2 font-display uppercase tracking-wider"><?php _e( 'Our Mission', 'top-digital-agencies' ); ?></h3>
                    <p class="text-[13.5px] text-slate-500 leading-relaxed font-sans"><?php _e( 'To bring transparency and trust to the Moroccan digital agency market by providing independent, verified information that helps businesses make confident hiring decisions.', 'top-digital-agencies' ); ?></p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <div class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-4 border border-indigo-100 shadow-sm">
                        <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-[15px] mb-2 font-display uppercase tracking-wider"><?php _e( 'Our Vision', 'top-digital-agencies' ); ?></h3>
                    <p class="text-[13.5px] text-slate-500 leading-relaxed font-sans"><?php _e( 'A Moroccan business ecosystem where every agency relationship is built on verified merit, transparent pricing, and measurable outcomes — not sales pitches and empty promises.', 'top-digital-agencies' ); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Differences & Team Section -->
    <section class="py-12 md:py-16 bg-slate-50/50 border-b border-slate-200/60 scroll-stage">
        <div class="max-w-3xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6 md:p-8 mb-8 shadow-sm">
                <div class="grid md:grid-cols-12 gap-8 md:gap-10 items-center">
                    <div class="md:col-span-8">
                        <h2 class="text-[1.25rem] font-bold text-slate-900 mb-6 font-display"><?php _e( 'What Makes Us Different', 'top-digital-agencies' ); ?></h2>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="font-display font-extrabold text-[24px] text-brand-600 leading-none mt-0.5">01</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-[14px] mb-1 font-display uppercase tracking-wider"><?php _e( 'We are not a lead-generation business', 'top-digital-agencies' ); ?></h4>
                                    <p class="text-[13.5px] text-slate-500 leading-relaxed font-sans"><?php _e( 'Unlike most directories, we do not sell your contact information to agencies. We do not charge referral fees. Our revenue comes from transparent listing fees and optional profile upgrades that never affect rankings.', 'top-digital-agencies' ); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 border-t border-slate-100 pt-5">
                                <div class="font-display font-extrabold text-[24px] text-indigo-600 leading-none mt-0.5">02</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-[14px] mb-1 font-display uppercase tracking-wider"><?php _e( 'Every review is verified by a human', 'top-digital-agencies' ); ?></h4>
                                    <p class="text-[13.5px] text-slate-500 leading-relaxed font-sans"><?php _e( 'Our editorial team personally confirms every review through email verification, LinkedIn cross-checking, and direct phone calls for projects over 50,000 MAD.', 'top-digital-agencies' ); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 border-t border-slate-100 pt-5">
                                <div class="font-display font-extrabold text-[24px] text-emerald-600 leading-none mt-0.5">03</div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-[14px] mb-1 font-display uppercase tracking-wider"><?php _e( 'We understand the local market', 'top-digital-agencies' ); ?></h4>
                                    <p class="text-[13.5px] text-slate-500 leading-relaxed font-sans"><?php _e( 'Our research team is based in Morocco. We understand local pricing norms, cultural business practices, and the unique challenges of marketing to Moroccan consumers.', 'top-digital-agencies' ); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-4 flex items-center justify-center bg-slate-50 border border-slate-200/80 rounded-xl p-6">
                        <svg class="w-full max-w-[220px] h-auto text-slate-800" viewBox="0 0 240 220" fill="none" stroke="currentColor">
                            <circle cx="120" cy="38" r="5" fill="#0f172a" stroke="none" />
                            <line x1="120" y1="43" x2="120" y2="150" stroke="#0f172a" stroke-width="2" />
                            <line x1="66" y1="58" x2="174" y2="58" stroke="#0f172a" stroke-width="2" stroke-linecap="round" />
                            <line x1="66" y1="58" x2="52" y2="92" stroke="#94a3b8" stroke-width="1.5" />
                            <line x1="66" y1="58" x2="80" y2="92" stroke="#94a3b8" stroke-width="1.5" />
                            <path d="M44 92a22 9 0 0 0 44 0" stroke="#2563eb" stroke-width="2" stroke-linecap="round" />
                            <line x1="174" y1="58" x2="160" y2="92" stroke="#94a3b8" stroke-width="1.5" />
                            <line x1="174" y1="58" x2="188" y2="92" stroke="#94a3b8" stroke-width="1.5" />
                            <path d="M152 92a22 9 0 0 0 44 0" stroke="#2563eb" stroke-width="2" stroke-linecap="round" />
                            <line x1="120" y1="150" x2="102" y2="162" stroke="#0f172a" stroke-width="2" stroke-linecap="round" />
                            <line x1="120" y1="150" x2="138" y2="162" stroke="#0f172a" stroke-width="2" stroke-linecap="round" />
                            <line x1="94" y1="162" x2="146" y2="162" stroke="#0f172a" stroke-width="2" stroke-linecap="round" />
                            <circle cx="120" cy="108" r="13" fill="#ffffff" stroke="#10b981" stroke-width="2" />
                            <path d="M114 108l4 4 7-8" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 md:p-8 shadow-sm">
                <h2 class="text-[1.25rem] font-bold text-slate-900 mb-2 font-display"><?php _e( 'Our Editorial Team', 'top-digital-agencies' ); ?></h2>
                <p class="text-[14px] text-slate-500 leading-relaxed mb-6 font-sans"><?php _e( 'Our research is conducted by a small team of industry veterans with combined experience across digital marketing, journalism, and business analysis in Morocco.', 'top-digital-agencies' ); ?></p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="text-center bg-slate-50/50 p-4 rounded-xl border border-slate-200/80 card-hover">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=120&h=120&fit=crop&crop=face" alt="Karim El Amrani" class="w-16 h-16 rounded-full object-cover mx-auto mb-3 border border-slate-200">
                        <h4 class="font-bold text-slate-900 text-[14px] font-display">Karim El Amrani</h4>
                        <p class="text-[11.5px] text-slate-500 font-sans"><?php _e( 'Editor-in-Chief', 'top-digital-agencies' ); ?></p>
                        <p class="text-[11px] text-slate-400 mt-1 font-mono"><?php _e( 'Former CMO, 12 years in digital', 'top-digital-agencies' ); ?></p>
                    </div>
                    <div class="text-center bg-slate-50/50 p-4 rounded-xl border border-slate-200/80 card-hover">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=120&h=120&fit=crop&crop=face" alt="Laila Bennani" class="w-16 h-16 rounded-full object-cover mx-auto mb-3 border border-slate-200">
                        <h4 class="font-bold text-slate-900 text-[14px] font-display">Laila Bennani</h4>
                        <p class="text-[11.5px] text-slate-500 font-sans"><?php _e( 'Head of Research', 'top-digital-agencies' ); ?></p>
                        <p class="text-[11px] text-slate-400 mt-1 font-mono"><?php _e( 'Data analyst & UX researcher', 'top-digital-agencies' ); ?></p>
                    </div>
                    <div class="text-center bg-slate-50/50 p-4 rounded-xl border border-slate-200/80 card-hover">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=120&h=120&fit=crop&crop=face" alt="Omar Fassi" class="w-16 h-16 rounded-full object-cover mx-auto mb-3 border border-slate-200">
                        <h4 class="font-bold text-slate-900 text-[14px] font-display">Omar Fassi</h4>
                        <p class="text-[11.5px] text-slate-500 font-sans"><?php _e( 'Senior Reviewer', 'top-digital-agencies' ); ?></p>
                        <p class="text-[11px] text-slate-400 mt-1 font-mono"><?php _e( 'Ex-agency director, SEO expert', 'top-digital-agencies' ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
get_footer();
