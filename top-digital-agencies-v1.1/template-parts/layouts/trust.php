<?php
/**
 * Layout Template Part: Trust Section
 */

$title = get_sub_field( 'title' );
$desc  = get_sub_field( 'description' );
?>

<section class="py-16 md:py-20 bg-slate-50/50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="section-label text-slate-800 mb-2 block"><?php _e( 'Independence', 'top-digital-agencies' ); ?></span>
            <h2 class="text-[1.75rem] md:text-[2.25rem] font-bold text-slate-900 tracking-tight leading-tight mb-5 font-display">
                <?php echo wp_kses_post( $title ); ?>
            </h2>
            <p class="text-[15px] text-slate-500 leading-relaxed mb-6">
                <?php echo wp_kses_post( $desc ); ?>
            </p>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3">
                    <i data-lucide="check" class="w-4 h-4 text-slate-800 flex-shrink-0"></i>
                    <span class="text-[14px] text-slate-700"><?php _e( 'Every review is email-verified and cross-checked', 'top-digital-agencies' ); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="check" class="w-4 h-4 text-slate-800 flex-shrink-0"></i>
                    <span class="text-[14px] text-slate-700"><?php _e( 'Portfolio audits by industry-experienced editors', 'top-digital-agencies' ); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="check" class="w-4 h-4 text-slate-800 flex-shrink-0"></i>
                    <span class="text-[14px] text-slate-700"><?php _e( 'Quarterly re-evaluation of all listed agencies', 'top-digital-agencies' ); ?></span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="check" class="w-4 h-4 text-slate-800 flex-shrink-0"></i>
                    <span class="text-[14px] text-slate-700"><?php _e( 'No referral fees, commissions, or paid placement', 'top-digital-agencies' ); ?></span>
                </div>
            </div>
            
            <a href="<?php echo esc_url( home_url( '/methodology/' ) ); ?>" class="text-[13px] font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1 transition-colors">
                <span><?php _e( 'Read our full methodology', 'top-digital-agencies' ); ?></span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>
</section>
