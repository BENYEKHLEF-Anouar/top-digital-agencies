<?php
/**
 * Layout Template Part: How We Solve It (Editorial Approach)
 */

$title = get_sub_field( 'title' );
?>

<section class="py-16 md:py-20 bg-slate-50/50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="section-label text-slate-800 mb-2 block"><?php _e( 'Our Approach', 'top-digital-agencies' ); ?></span>
            <h2 class="text-[1.75rem] md:text-[2.25rem] font-bold text-slate-900 tracking-tight font-display">
                <?php echo wp_kses_post( $title ); ?>
            </h2>
            <p class="text-[15px] text-slate-500 mt-2 max-w-md mx-auto"><?php _e( 'We do the research so you can focus on the decision.', 'top-digital-agencies' ); ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div class="relative bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-[11px] font-bold mb-4 font-mono">1</div>
                <h3 class="font-bold text-[16px] text-slate-900 mb-2 font-display"><?php _e( 'Deep Research', 'top-digital-agencies' ); ?></h3>
                <p class="text-[13.5px] text-slate-500 leading-relaxed">
                    <?php _e( 'Our editorial team audits portfolios, interviews real clients, and checks business records for every agency before they appear on our platform.', 'top-digital-agencies' ); ?>
                </p>
            </div>
            <div class="relative bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-[11px] font-bold mb-4 font-mono">2</div>
                <h3 class="font-bold text-[16px] text-slate-900 mb-2 font-display"><?php _e( 'Verified Scoring', 'top-digital-agencies' ); ?></h3>
                <p class="text-[13.5px] text-slate-500 leading-relaxed">
                    <?php _e( 'Each agency receives an independent score based on four weighted criteria: reviews, portfolio, market presence, and service breadth.', 'top-digital-agencies' ); ?>
                </p>
            </div>
            <div class="relative bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center text-[11px] font-bold mb-4 font-mono">3</div>
                <h3 class="font-bold text-[16px] text-slate-900 mb-2 font-display"><?php _e( 'Direct Connection', 'top-digital-agencies' ); ?></h3>
                <p class="text-[13.5px] text-slate-500 leading-relaxed">
                    <?php _e( 'Contact agencies directly through verified profiles. No referral fees, no middlemen, no hidden commissions. Just honest information.', 'top-digital-agencies' ); ?>
                </p>
            </div>
        </div>
    </div>
</section>
