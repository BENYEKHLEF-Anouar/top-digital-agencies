    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-12 md:py-16">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
                <div class="col-span-2">
                    <div class="flex items-center gap-2 mb-4 cursor-pointer font-display" onclick="window.location.href='<?php echo esc_url( home_url( '/' ) ); ?>'">
                        <span class="w-[18px] h-[18px] rounded-[5px] bg-brand-600 relative inline-block flex-shrink-0 after:content-[''] after:absolute after:top-[5px] after:right-[5px] after:w-[6px] after:h-[6px] after:rounded-[1.5px] after:bg-white" aria-hidden="true"></span>
                        <div class="flex items-center gap-1 font-display">
                            <span class="font-extrabold text-[16px] text-slate-900 tracking-tight"><?php _e( 'Agence', 'top-digital-agencies' ); ?></span>
                            <span class="font-extrabold text-[16px] text-brand-600 tracking-tight"><?php _e( 'Marketing', 'top-digital-agencies' ); ?></span>
                            <span class="font-light text-[16px] text-slate-400 tracking-tight"><?php _e( 'Digital', 'top-digital-agencies' ); ?></span>
                        </div>
                    </div>
                    <p class="text-[13px] text-slate-500 leading-relaxed max-w-sm">
                        <?php _e( 'Morocco\'s independent directory for digital marketing agencies. Verified reviews, transparent rankings, zero paid placement.', 'top-digital-agencies' ); ?>
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-slate-900 text-[13px] mb-3 font-display"><?php _e( 'Directory', 'top-digital-agencies' ); ?></h4>
                    <ul class="space-y-2 text-[13px]">
                        <li><a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="text-slate-500 hover:text-slate-900 transition-colors"><?php _e( 'All Agencies', 'top-digital-agencies' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/directory/?city=Casablanca' ) ); ?>" class="text-slate-500 hover:text-slate-900 transition-colors"><?php _e( 'By City', 'top-digital-agencies' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/directory/?service=SEO' ) ); ?>" class="text-slate-500 hover:text-slate-900 transition-colors"><?php _e( 'By Service', 'top-digital-agencies' ); ?></a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-slate-900 text-[13px] mb-3 font-display"><?php _e( 'Cities', 'top-digital-agencies' ); ?></h4>
                    <ul class="space-y-2 text-[13px]">
                        <li><a href="<?php echo esc_url( home_url( '/directory/?city=Casablanca' ) ); ?>" class="text-slate-500 hover:text-slate-900 transition-colors">Casablanca</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/directory/?city=Rabat' ) ); ?>" class="text-slate-500 hover:text-slate-900 transition-colors">Rabat</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/directory/?city=Tangier' ) ); ?>" class="text-slate-500 hover:text-slate-900 transition-colors">Tangier</a></li>
                        <li><a href="<?php echo esc_url( home_url( '/directory/?city=Marrakech' ) ); ?>" class="text-slate-500 hover:text-slate-900 transition-colors">Marrakech</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-slate-900 text-[13px] mb-3 font-display"><?php _e( 'Company', 'top-digital-agencies' ); ?></h4>
                    <?php
                    if ( has_nav_menu( 'footer' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'container'      => false,
                            'items_wrap'     => '<ul class="space-y-2 text-[13px]">%3$s</ul>',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ) );
                    } else {
                        // Fallback static structure matching v3_2 Company menu
                        echo '<ul class="space-y-2 text-[13px]">';
                        echo '<li><a href="' . esc_url( home_url( '/about/' ) ) . '" class="text-slate-500 hover:text-slate-900 transition-colors">' . __( 'About', 'top-digital-agencies' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( home_url( '/blog/' ) ) . '" class="text-slate-500 hover:text-slate-900 transition-colors">' . __( 'Blog', 'top-digital-agencies' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( home_url( '/methodology/' ) ) . '" class="text-slate-500 hover:text-slate-900 transition-colors">' . __( 'Methodology', 'top-digital-agencies' ) . '</a></li>';
                        echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '" class="text-slate-500 hover:text-slate-900 transition-colors">' . __( 'Contact', 'top-digital-agencies' ) . '</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </div>
            </div>

            <div class="border-t border-slate-200 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[12px] text-slate-400 font-mono">
                    &copy; <?php echo date( 'Y' ); ?> <?php _e( 'Agence Marketing Digital. Independent directory.', 'top-digital-agencies' ); ?>
                </p>
                <div class="flex items-center gap-4 text-slate-400">
                    <a href="#" class="hover:text-slate-500 transition-colors"><i data-lucide="twitter" class="w-4 h-4"></i></a>
                    <a href="#" class="hover:text-slate-500 transition-colors"><i data-lucide="linkedin" class="w-4 h-4"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ==================== MODALS ==================== -->

    <!-- Global Command Palette Search Overlay -->
    <dialog id="search-modal" class="backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm rounded-xl border border-slate-200 shadow-xl max-w-xl w-[calc(100%-2rem)] mx-4 sm:mx-auto p-0 bg-white overflow-hidden outline-none">
        <div class="p-4 border-b border-slate-150 flex items-center gap-3">
            <i data-lucide="search" class="w-5 h-5 text-slate-400 flex-shrink-0"></i>
            <input type="text" id="search-input" oninput="runPaletteSearch(this.value)" placeholder="<?php esc_attr_e( 'Search agencies, pages, cities...', 'top-digital-agencies' ); ?>" class="w-full text-[15px] outline-none text-slate-700 bg-transparent font-sans">
            <button onclick="closeSearchPalette()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <ul id="search-results" class="max-h-80 overflow-y-auto p-2 space-y-0.5 font-sans">
            <!-- Search results populated dynamically from Localized script variables -->
        </ul>
        <div class="px-4 py-2 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-[11px] text-slate-400 font-mono">
            <span><?php _e( 'Press ESC to close', 'top-digital-agencies' ); ?></span>
            <span><?php _e( 'search', 'top-digital-agencies' ); ?></span>
        </div>
    </dialog>

    <!-- Global Matchmaking Wizard Modal -->
    <dialog id="matching-wizard-modal" class="backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm rounded-xl border border-slate-200 shadow-xl max-w-md w-[calc(100%-2rem)] mx-4 sm:mx-auto p-6 bg-white outline-none">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-extrabold text-[18px] text-slate-900 font-display flex items-center gap-1.5">
                <i data-lucide="sparkles" class="w-4 h-4 text-indigo-600"></i>
                <span><?php _e( 'Find My Match', 'top-digital-agencies' ); ?></span>
            </h3>
            <button onclick="closeWizardModal()" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>

        <!-- Step 1: Select Service -->
        <div id="wiz-step-1" class="space-y-4">
            <p class="text-[13px] text-slate-500 leading-relaxed"><?php _e( 'Which digital service do you require help with?', 'top-digital-agencies' ); ?></p>
            <div class="grid grid-cols-2 gap-2">
                <button onclick="selectWizardService(this, 'SEO')" class="wiz-opt border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-center transition-all text-[13px] font-semibold text-slate-700">SEO</button>
                <button onclick="selectWizardService(this, 'Paid Ads')" class="wiz-opt border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-center transition-all text-[13px] font-semibold text-slate-700"><?php _e( 'Paid Ads', 'top-digital-agencies' ); ?></button>
                <button onclick="selectWizardService(this, 'Social Media')" class="wiz-opt border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-center transition-all text-[13px] font-semibold text-slate-700"><?php _e( 'Social Media', 'top-digital-agencies' ); ?></button>
                <button onclick="selectWizardService(this, 'Web Design')" class="wiz-opt border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-center transition-all text-[13px] font-semibold text-slate-700"><?php _e( 'Web Design', 'top-digital-agencies' ); ?></button>
                <button onclick="selectWizardService(this, 'Branding')" class="wiz-opt border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-center transition-all text-[13px] font-semibold text-slate-700"><?php _e( 'Branding', 'top-digital-agencies' ); ?></button>
                <button onclick="selectWizardService(this, 'Content Marketing')" class="wiz-opt border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-center transition-all text-[13px] font-semibold text-slate-700"><?php _e( 'Content', 'top-digital-agencies' ); ?></button>
            </div>
            <div class="pt-4 flex justify-end">
                <button id="wiz-next-btn" disabled onclick="goWizardStep(2)" class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-semibold px-5 py-2.5 rounded-lg text-[13px] transition-all flex items-center gap-1 font-mono">
                    <span><?php _e( 'Next Step', 'top-digital-agencies' ); ?></span> <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: Select Budget -->
        <div id="wiz-step-2" class="hidden space-y-4">
            <p class="text-[13px] text-slate-500 leading-relaxed"><?php _e( 'What is your target monthly marketing budget?', 'top-digital-agencies' ); ?></p>
            <div class="space-y-2">
                <button onclick="selectWizardBudget(this, 'low')" class="wiz-opt-budget w-full border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-left transition-all text-[13px] font-semibold text-slate-700 flex justify-between items-center">
                    <span><?php _e( 'Under 10,000 MAD / month', 'top-digital-agencies' ); ?></span>
                    <i data-lucide="circle" class="w-4 h-4 text-slate-300"></i>
                </button>
                <button onclick="selectWizardBudget(this, 'mid')" class="wiz-opt-budget w-full border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-left transition-all text-[13px] font-semibold text-slate-700 flex justify-between items-center">
                    <span><?php _e( '10,000 MAD - 20,000 MAD / month', 'top-digital-agencies' ); ?></span>
                    <i data-lucide="circle" class="w-4 h-4 text-slate-300"></i>
                </button>
                <button onclick="selectWizardBudget(this, 'high')" class="wiz-opt-budget w-full border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-left transition-all text-[13px] font-semibold text-slate-700 flex justify-between items-center">
                    <span><?php _e( 'Over 20,000 MAD / month', 'top-digital-agencies' ); ?></span>
                    <i data-lucide="circle" class="w-4 h-4 text-slate-300"></i>
                </button>
            </div>
            <div class="pt-4 flex justify-between">
                <button onclick="goWizardStep(1)" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-semibold px-4 py-2 rounded-lg text-[13px] transition-all flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> <span><?php _e( 'Back', 'top-digital-agencies' ); ?></span>
                </button>
                <button id="wiz-submit-btn" disabled onclick="submitWizard(event)" class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-semibold px-4 py-2 rounded-lg text-[13px] transition-all flex items-center gap-1 font-mono">
                    <span><?php _e( 'Find Matches', 'top-digital-agencies' ); ?></span> <i data-lucide="sparkles" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- Success Screen -->
        <div id="wiz-step-success" class="hidden text-center py-6 space-y-4">
            <div class="w-12 h-12 bg-indigo-50 border border-indigo-100 rounded-full flex items-center justify-center mx-auto text-indigo-600 animate-pulse">
                <i data-lucide="sparkles" class="w-6 h-6"></i>
            </div>
            <h4 class="font-bold text-slate-900 text-[16px] font-display"><?php _e( 'Finding matches...', 'top-digital-agencies' ); ?></h4>
            <p class="text-[13px] text-slate-500 max-w-xs mx-auto"><?php _e( 'We are matching your project with top agencies that fit your criteria.', 'top-digital-agencies' ); ?></p>
        </div>
    </dialog>

    <?php wp_footer(); ?>
</body>
</html>
