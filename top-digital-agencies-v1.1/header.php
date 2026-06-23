<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'text-slate-800 antialiased min-h-screen flex flex-col justify-between' ); ?>>

    <!-- Header / Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14">
                
                <!-- Logo Wordmark -->
                <div class="flex items-center gap-2 cursor-pointer font-display" onclick="window.location.href='<?php echo esc_url( home_url( '/' ) ); ?>'">
                    <!-- Logo Symbol of V1 -->
                    <span class="w-[18px] h-[18px] rounded-[5px] bg-brand-600 relative inline-block flex-shrink-0 after:content-[''] after:absolute after:top-[5px] after:right-[5px] after:w-[6px] after:h-[6px] after:rounded-[1.5px] after:bg-white" aria-hidden="true"></span>
                    <!-- Wordmark text -->
                    <div class="flex items-center gap-0.5 sm:gap-1 text-[13px] min-[360px]:text-[14px] sm:text-[16px]">
                        <span class="font-extrabold text-slate-900 tracking-tight"><?php _e( 'Agence', 'top-digital-agencies' ); ?></span>
                        <span class="font-extrabold text-brand-600 tracking-tight"><?php _e( 'Marketing', 'top-digital-agencies' ); ?></span>
                        <span class="font-light text-slate-400 tracking-tight"><?php _e( 'Digital', 'top-digital-agencies' ); ?></span>
                    </div>
                </div>
                
                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-7">
                    <?php
                    if ( has_nav_menu( 'primary' ) ) {
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'container'      => false,
                            'items_wrap'     => '%3$s',
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ) );
                    } else {
                        tda_fallback_navigation_menu();
                    }
                    ?>
                </nav>
                
                <!-- Nav Actions: Search, Translation, Mobile Menu Toggle -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <button onclick="openSearchPalette()" class="nav-search-btn bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg px-2 sm:px-3 py-1.5 text-[12px] text-slate-500 flex items-center gap-1.5 sm:gap-2 transition-all">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span class="hidden sm:inline"><?php _e( 'search', 'top-digital-agencies' ); ?></span>
                        <kbd class="font-mono text-[9px] bg-slate-200 border border-slate-300 rounded px-1.5 py-0.5 ml-1 hidden sm:inline-block">⌘ K</kbd>
                    </button>
                    
                    <!-- Language switch selector -->
                    <div class="bg-slate-100 p-0.5 rounded-lg flex items-center text-[11px] font-semibold">
                        <?php if ( function_exists( 'pll_the_languages' ) ) : ?>
                            <?php
                            $langs = pll_the_languages( array( 'raw' => 1 ) );
                            if ( ! empty( $langs ) ) {
                                foreach ( $langs as $code => $lang ) {
                                    $active = $lang['current_lang'] ? 'active bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900';
                                    echo '<a href="' . esc_url( $lang['url'] ) . '" class="px-2 py-1 rounded transition-colors ' . esc_attr( $active ) . '">' . strtoupper( $code ) . '</a>';
                                }
                            }
                            ?>
                        <?php else : ?>
                            <!-- Fallback if Polylang not active -->
                            <a href="#" class="px-2 py-1 rounded transition-colors active bg-white text-slate-900 shadow-sm">EN</a>
                            <a href="#" class="px-2 py-1 rounded transition-colors text-slate-500 hover:text-slate-900">FR</a>
                        <?php endif; ?>
                    </div>

                    <button onclick="toggleMobileMenu()" class="md:hidden p-1.5 text-slate-600 hover:text-brand-600">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Dropdown Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-slate-200 bg-white">
            <div class="px-5 py-2 space-y-0.5">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ) );
                } else {
                    tda_fallback_navigation_menu();
                }
                ?>
            </div>
        </div>
    </header>

    <main class="flex-grow">
