<?php
/**
 * The template for displaying single blog posts
 */

get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $post_id = get_the_ID();
        $categories = get_the_category();
        $category_name = ( ! empty( $categories ) ) ? $categories[0]->name : __( 'Guide', 'top-digital-agencies' );
        
        // Retrieve embedded listings relationship
        $featured_listings = get_field( 'embedded_listings', $post_id );
        ?>

        <article class="page" id="page-article">
            <!-- Article Header -->
            <section class="relative z-10 bg-white/80 border-b border-slate-200 backdrop-blur-sm">
                <div class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8 pt-12 pb-10 text-left">
                    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="group inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 hover:text-slate-900 transition-colors uppercase font-mono tracking-wider mb-5">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform"></i>
                        <span><?php _e( 'back to insights', 'top-digital-agencies' ); ?></span>
                    </a>
                    
                    <div class="flex items-center gap-2 text-[10px] uppercase font-bold text-slate-400 font-mono tracking-wider mb-3">
                        <span><?php echo esc_html( $category_name ); ?></span>
                        <span>&middot;</span>
                        <span><?php echo get_the_date( 'd F Y' ); ?></span>
                    </div>

                    <h1 class="hero-title text-[1.75rem] md:text-[2.75rem] font-bold text-slate-900 tracking-tight leading-tight mb-4 font-display">
                        <?php the_title(); ?>
                    </h1>

                    <div class="flex items-center gap-3 pt-2">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200">
                            <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                        </div>
                        <div>
                            <p class="text-[12px] font-bold text-slate-700 leading-none"><?php the_author(); ?></p>
                            <p class="text-[10px] text-slate-400 font-mono mt-1 uppercase"><?php _e( 'Lead Editor', 'top-digital-agencies' ); ?></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Article Body -->
            <section class="py-12 md:py-16 bg-slate-50/50">
                <div class="max-w-4xl mx-auto px-5 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                        
                        <!-- Main Content -->
                        <div class="lg:col-span-8 space-y-6">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="w-full rounded-xl overflow-hidden border border-slate-200 bg-white p-2 shadow-sm mb-6">
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="prose prose-slate max-w-none text-[15px] text-slate-600 leading-relaxed bg-white rounded-xl border border-slate-200 p-6 md:p-8 shadow-sm">
                                <?php the_content(); ?>
                            </div>
                        </div>

                        <!-- Sidebar / Featured Listings Relationship -->
                        <div class="lg:col-span-4 space-y-5">
                            <?php if ( ! empty( $featured_listings ) ) : ?>
                                <div class="card-hover bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                                    <h3 class="font-bold text-slate-900 text-[13px] mb-4 uppercase tracking-wider font-mono text-slate-400"><?php _e( 'Featured Listings', 'top-digital-agencies' ); ?></h3>
                                    <div class="space-y-4">
                                        <?php 
                                        foreach ( $featured_listings as $listing_post ) : 
                                            $l_id = $listing_post->ID;
                                            $logo_image = get_field( 'logo_image', $l_id );
                                            $logo_text = get_field( 'logo_text', $l_id );
                                            $rank = get_field( 'agency_rank', $l_id );
                                            $rating = get_field( 'rating_value', $l_id );
                                            $reviews = get_field( 'review_count', $l_id );
                                            ?>
                                            <div class="border border-slate-100 rounded-lg p-3 hover:bg-slate-50 transition-colors cursor-pointer" onclick="window.location.href='<?php echo esc_url( get_permalink( $l_id ) ); ?>'">
                                                <div class="flex items-center gap-2.5">
                                                    <?php if ( $logo_image ) : ?>
                                                        <img src="<?php echo esc_url( $logo_image ); ?>" alt="<?php echo esc_attr( get_the_title( $l_id ) ); ?>" class="w-8 h-8 rounded object-cover border border-slate-200">
                                                    <?php else : ?>
                                                        <div class="w-8 h-8 rounded flex items-center justify-center bg-brand-600 text-white font-bold text-xs uppercase"><?php echo esc_html( $logo_text ? $logo_text : substr( get_the_title( $l_id ), 0, 2 ) ); ?></div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="min-w-0 flex-1">
                                                        <h4 class="font-bold text-[12px] text-slate-800 truncate leading-snug"><?php echo esc_html( get_the_title( $l_id ) ); ?></h4>
                                                        <p class="text-[10px] text-slate-400 font-mono mt-0.5"><?php _e( 'Rank', 'top-digital-agencies' ); ?> <?php echo esc_html( $rank ); ?></p>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-slate-100/50 text-[10px] font-mono text-slate-400">
                                                    <span class="flex items-center gap-0.5"><i data-lucide="star" class="w-3 h-3 text-amber-400 fill-amber-400 inline"></i> <?php echo esc_html( $rating ); ?> (<?php echo esc_html( $reviews ); ?>)</span>
                                                    <span class="text-brand-600 font-semibold uppercase hover:underline"><?php _e( 'profile', 'top-digital-agencies' ); ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </section>
        </article>

        <?php
    endwhile;
endif;

get_footer();
?>
