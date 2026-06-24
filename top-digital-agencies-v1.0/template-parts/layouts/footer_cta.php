<?php
/**
 * Layout Template Part: Footer CTA Band
 */

$title = get_sub_field( 'title' );
$desc  = get_sub_field( 'description' );
?>

<section class="py-16 bg-slate-900 text-white relative overflow-hidden">
    <!-- Subtle dot pattern matching charter -->
    <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:20px_20px] opacity-60"></div>
    
    <div class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="text-[10px] bg-white/10 border border-white/20 text-white px-2.5 py-1 rounded-md font-mono font-bold tracking-wider uppercase inline-block mb-3">
            <?php _e( 'Matchmaker', 'top-digital-agencies' ); ?>
        </span>
        <h2 class="text-[1.75rem] md:text-[2.5rem] font-bold tracking-tight mb-4 font-display leading-tight">
            <?php echo wp_kses_post( $title ); ?>
        </h2>
        <p class="text-[14px] md:text-[16px] text-slate-400 mb-8 max-w-xl mx-auto leading-relaxed">
            <?php echo wp_kses_post( $desc ); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
            <button onclick="openWizardModal()" class="w-full sm:w-auto bg-brand-600 hover:bg-brand-700 text-white font-semibold px-7 py-3 rounded-lg text-[14px] transition-all flex items-center justify-center gap-2 shadow-md">
                <i data-lucide="sparkles" class="w-4 h-4"></i> <?php _e( 'Launch Matchmaker', 'top-digital-agencies' ); ?>
            </button>
            <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="w-full sm:w-auto text-center bg-white/10 hover:bg-white/15 text-white border border-white/10 font-semibold px-7 py-3 rounded-lg text-[14px] transition-all">
                <?php _e( 'Explore Directory', 'top-digital-agencies' ); ?>
            </a>
        </div>
    </div>
</section>
