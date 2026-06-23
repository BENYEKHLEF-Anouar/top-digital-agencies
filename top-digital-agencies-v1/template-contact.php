<?php
/**
 * Template Name: Contact Page Template
 */

get_header();
?>

<div class="page">
    <!-- Hero Header Section (Left-biased) -->
    <section class="relative z-10 bg-white/80 border-b border-slate-200 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8 pt-16 pb-12 md:pt-20 md:pb-16 text-left">
            <div class="flex justify-start items-center gap-1.5 text-[11px] font-semibold text-slate-400 mb-4 tracking-wider uppercase font-sans">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="cursor-pointer hover:text-slate-900 transition-colors"><?php _e( 'HOME', 'top-digital-agencies' ); ?></a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
                <span class="text-slate-900 font-semibold"><?php _e( 'CONTACT', 'top-digital-agencies' ); ?></span>
            </div>
            <h1 class="hero-title text-[2.25rem] md:text-[3.5rem] font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-5 font-display">
                <?php _e( 'Contact', 'top-digital-agencies' ); ?> <span class="text-brand-600"><?php _e( 'Us', 'top-digital-agencies' ); ?><span class="text-slate-900">.</span></span>
            </h1>
            <p class="text-[16px] md:text-[18px] text-slate-500 leading-relaxed max-w-xl">
                <?php _e( 'Questions about listings, rankings, or partnerships? We read every message and typically respond within 24 hours.', 'top-digital-agencies' ); ?>
            </p>
        </div>
    </section>
    
    <section class="py-12 md:py-16 bg-slate-50/50 border-b border-slate-200/60 scroll-stage">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                <!-- Form block -->
                <div class="lg:col-span-8 bg-white rounded-xl border border-slate-200 p-6 md:p-8 shadow-sm">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <?php if ( get_the_content() ) : ?>
                            <!-- Render the dynamic form / content from the editor (e.g. shortcodes) -->
                            <div class="prose prose-slate max-w-none text-[14.5px]">
                                <?php the_content(); ?>
                            </div>
                        <?php else : ?>
                            <!-- Default gorgeous HTML Form -->
                            <h2 class="text-[1.25rem] font-bold text-slate-900 mb-6 font-display"><?php _e( 'Send a Message', 'top-digital-agencies' ); ?></h2>
                            
                            <form id="contact-form" onsubmit="handleContactSubmit(event)" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="firstName" class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wider"><?php _e( 'First Name', 'top-digital-agencies' ); ?></label>
                                        <input type="text" id="firstName" required placeholder="<?php esc_attr_e( 'Your first name', 'top-digital-agencies' ); ?>" class="w-full bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-500/10 rounded-lg px-3.5 py-2.5 text-[13px] outline-none transition-all">
                                    </div>
                                    <div>
                                        <label for="lastName" class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wider"><?php _e( 'Last Name', 'top-digital-agencies' ); ?></label>
                                        <input type="text" id="lastName" required placeholder="<?php esc_attr_e( 'Your last name', 'top-digital-agencies' ); ?>" class="w-full bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-500/10 rounded-lg px-3.5 py-2.5 text-[13px] outline-none transition-all">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wider"><?php _e( 'Email', 'top-digital-agencies' ); ?></label>
                                    <input type="email" id="email" required placeholder="you@company.com" class="w-full bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-500/10 rounded-lg px-3.5 py-2.5 text-[13px] outline-none transition-all">
                                </div>
                                
                                <div>
                                    <label for="subject" class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wider"><?php _e( 'Subject', 'top-digital-agencies' ); ?></label>
                                    <select id="subject" class="w-full bg-slate-50 border border-slate-200 focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-500/10 rounded-lg px-3.5 py-2.5 text-[13px] outline-none transition-all">
                                        <option value="general"><?php _e( 'General Inquiry', 'top-digital-agencies' ); ?></option>
                                        <option value="list"><?php _e( 'Get My Agency Listed', 'top-digital-agencies' ); ?></option>
                                        <option value="report"><?php _e( 'Report an Issue', 'top-digital-agencies' ); ?></option>
                                        <option value="partner"><?php _e( 'Partnership Opportunity', 'top-digital-agencies' ); ?></option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="message" class="block text-[11px] font-semibold text-slate-500 mb-1.5 uppercase tracking-wider"><?php _e( 'Message', 'top-digital-agencies' ); ?></label>
                                    <textarea id="message" required rows="5" placeholder="<?php esc_attr_e( 'How can we help you?', 'top-digital-agencies' ); ?>" class="w-full bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-500/10 rounded-lg px-3.5 py-2.5 text-[13px] outline-none transition-all resize-none"></textarea>
                                </div>
                                
                                <div class="pt-2">
                                    <button type="submit" id="contact-submit-btn" class="btn-spring bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-lg text-[13px] flex items-center gap-1.5 shadow-sm"><?php _e( 'Send Message', 'top-digital-agencies' ); ?></button>
                                </div>
                            </form>

                            <div id="contact-success-msg" class="hidden mt-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-lg p-4 text-[13.5px]">
                                <?php _e( 'Thank you! Your message has been sent successfully. We will get back to you shortly.', 'top-digital-agencies' ); ?>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; endif; ?>
                </div>
                
                <!-- Side Info block -->
                <div class="lg:col-span-4 lg:pl-10 lg:border-l border-slate-200/80 space-y-8">
                    <!-- Contact Info -->
                    <div>
                        <span class="block font-sans text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-4"><?php _e( 'Contact Info', 'top-digital-agencies' ); ?></span>
                        <div class="space-y-6 text-[13.5px] text-slate-500 leading-relaxed">
                            <div class="flex items-start gap-3 border-b border-slate-200/60 pb-5">
                                <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <h4 class="font-semibold text-slate-800 text-[13px] mb-0.5"><?php _e( 'Main Office', 'top-digital-agencies' ); ?></h4>
                                    <p class="text-slate-500">8 rue de la Paix, 75002 Paris, France</p>
                                    <p class="text-slate-500"><?php _e( 'Casablanca, Morocco', 'top-digital-agencies' ); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 pb-2">
                                <i data-lucide="mail" class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <h4 class="font-semibold text-slate-800 text-[13px] mb-0.5"><?php _e( 'Email', 'top-digital-agencies' ); ?></h4>
                                    <a href="mailto:contact@agencemarketingdigital.com" class="text-brand-600 font-medium break-all hover:text-brand-700 transition-colors">contact@agencemarketingdigital.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Independence block -->
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 shadow-sm">
                        <div class="flex items-center gap-2.5 mb-2.5">
                            <div class="w-7 h-7 rounded-lg bg-white border border-indigo-200/60 text-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 text-[13px] font-display uppercase tracking-wider"><?php _e( 'Independence', 'top-digital-agencies' ); ?></h3>
                        </div>
                        <p class="text-[12.5px] text-slate-500 leading-relaxed"><?php _e( 'We do not accept paid placements or sponsored rankings. Agencies that want to be listed go through our standard review process.', 'top-digital-agencies' ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function handleContactSubmit(event) {
        event.preventDefault();
        
        // Hide form and show success message
        document.getElementById('contact-form').classList.add('hidden');
        document.getElementById('contact-success-msg').classList.remove('hidden');
        
        // Note: In real setup, user will hook this form to an action/API or use plugins like WPForms.
    }
</script>

<?php
get_footer();
