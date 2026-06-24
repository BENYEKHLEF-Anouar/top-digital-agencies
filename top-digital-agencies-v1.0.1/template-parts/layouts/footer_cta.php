<?php
/**
 * Layout Template Part: Footer CTA Band
 */

$title = get_sub_field( 'title' );
$desc  = get_sub_field( 'description' );
?>

<section class="py-16 md:py-20 bg-slate-50/50 border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="section-label text-slate-800 mb-2 block">
                <?php _e( 'Matchmaker', 'top-digital-agencies' ); ?>
            </span>
            <h2 class="text-[1.75rem] md:text-[2.25rem] font-bold text-slate-900 tracking-tight leading-tight mb-5 font-display">
                <?php echo wp_kses_post( $title ); ?>
            </h2>
            <p class="text-[15px] text-slate-500 leading-relaxed mb-8">
                <?php echo wp_kses_post( $desc ); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="openWizardModal()" class="w-full sm:w-auto bg-brand-600 hover:bg-brand-700 text-white font-semibold px-7 py-3 rounded-lg text-[14px] transition-all flex items-center justify-center gap-1.5">
                    <span><?php _e( 'Launch Matchmaker', 'top-digital-agencies' ); ?></span>
                </button>
                <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="w-full sm:w-auto bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 font-semibold px-7 py-3 rounded-lg text-[14px] transition-all flex items-center justify-center gap-1.5">
                    <i data-lucide="compass" class="w-4 h-4 text-slate-400"></i>
                    <span><?php _e( 'Explore Directory', 'top-digital-agencies' ); ?></span>
                </a>
            </div>
        </div>
    </div>
</section>
