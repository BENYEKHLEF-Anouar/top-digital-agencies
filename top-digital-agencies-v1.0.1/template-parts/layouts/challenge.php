<?php
/**
 * Layout Template Part: Challenge Section
 */

$heading     = get_sub_field( 'heading' );
$description = get_sub_field( 'description' );
$quote_text  = get_sub_field( 'quote_text' );
$quote_author= get_sub_field( 'quote_author' );
$quote_role  = get_sub_field( 'quote_role' );
?>

<section class="py-16 md:py-20 bg-white/70 border-b border-slate-200 backdrop-blur-sm">
    <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <!-- Text Content -->
            <div class="lg:col-span-5">
                <span class="section-label text-slate-800 mb-3 block"><?php _e( 'The Challenge', 'top-digital-agencies' ); ?></span>
                <h2 class="text-[1.75rem] md:text-[2.25rem] font-bold text-slate-900 tracking-tight leading-tight mb-5">
                    <?php echo wp_kses_post( $heading ); ?>
                </h2>
                <p class="text-[15px] text-slate-500 leading-relaxed mb-6">
                    <?php echo wp_kses_post( $description ); ?>
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="x" class="w-3.5 h-3.5 text-slate-800"></i>
                        </div>
                        <div>
                            <p class="text-[14px] font-semibold text-slate-900"><?php _e( 'No independent comparison', 'top-digital-agencies' ); ?></p>
                            <p class="text-[13px] text-slate-500 mt-0.5"><?php _e( 'Every agency claims "#1 in Morocco" with no proof.', 'top-digital-agencies' ); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="x" class="w-3.5 h-3.5 text-slate-800"></i>
                        </div>
                        <div>
                            <p class="text-[14px] font-semibold text-slate-900"><?php _e( 'Reviews you can\'t trust', 'top-digital-agencies' ); ?></p>
                            <p class="text-[13px] text-slate-500 mt-0.5"><?php _e( 'Testimonials are hand-picked by agencies themselves.', 'top-digital-agencies' ); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="x" class="w-3.5 h-3.5 text-slate-800"></i>
                        </div>
                        <div>
                            <p class="text-[14px] font-semibold text-slate-900"><?php _e( 'Local context missing', 'top-digital-agencies' ); ?></p>
                            <p class="text-[13px] text-slate-500 mt-0.5"><?php _e( 'Global directories don\'t understand the Moroccan market.', 'top-digital-agencies' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quote Block -->
            <div class="lg:col-span-7">
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-100/50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative">
                        <div style="font-family: Georgia, serif; font-size: 4rem; line-height: 1; color: #cbd5e1; position: absolute; top: -1rem; left: -0.5rem;">"</div>
                        <blockquote class="text-[17px] md:text-[19px] text-slate-700 font-medium leading-relaxed pl-6 mb-4 font-display">
                            <?php echo wp_kses_post( $quote_text ); ?>
                        </blockquote>
                        <div class="flex items-center gap-3 pl-6">
                            <?php 
                            $author_img = get_sub_field( 'quote_author_image' );
                            if ( $author_img ) :
                                ?>
                                <img src="<?php echo esc_url( $author_img ); ?>" alt="<?php echo esc_attr( $quote_author ); ?>" class="w-10 h-10 rounded-full object-cover">
                                <?php
                            else :
                                ?>
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center border border-slate-300">
                                    <i data-lucide="user" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <?php
                            endif;
                            ?>
                            <div>
                                <p class="text-[13px] font-semibold text-slate-900"><?php echo esc_html( $quote_author ); ?></p>
                                <p class="text-[12px] text-slate-500"><?php echo esc_html( $quote_role ); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-slate-200 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-semibold text-slate-800 uppercase tracking-wider"><?php _e( 'Verified review', 'top-digital-agencies' ); ?></span>
                            <i data-lucide="badge-check" class="w-3.5 h-3.5 text-slate-500"></i>
                        </div>
                        <span class="text-[12px] text-slate-400"><?php _e( 'Project: SEO & Paid Ads', 'top-digital-agencies' ); ?></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
