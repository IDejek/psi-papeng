<?php
/**
 * Single Post Template — FIXED
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;

/* FIX: Move helper function BEFORE template output */
function psi_reading_time(): string {
    $content = get_post_field( 'post_content', get_the_ID() );
    $words   = str_word_count( strip_tags( $content ) );
    $minutes = ceil( $words / 200 );
    return $minutes . ' ' . esc_html__( 'menit baca', 'psi-papeng' );
}

get_header();
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <article <?php post_class(); ?>>
                    <header class="mb-8">
                        <?php
                        $cats = get_the_category();
                        if ( $cats ) :
                            echo '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" class="inline-block px-3 py-1 bg-red-50 text-red-600 text-xs font-bold uppercase tracking-wide rounded-full mb-4 hover:bg-red-100 transition-colors">' . esc_html( $cats[0]->name ) . '</a>';
                        endif;
                        ?>
                        <h1 class="text-2xl md:text-4xl font-black text-gray-900 leading-tight mb-4" style="font-family:'Poppins',sans-serif;"><?php the_title(); ?></h1>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                            <span><i class="far fa-user mr-1"></i> <?php the_author(); ?></span>
                            <span><i class="far fa-calendar mr-1"></i> <?php echo get_the_date(); ?></span>
                            <span><i class="far fa-clock mr-1"></i> <?php echo psi_reading_time(); ?></span>
                        </div>
                    </header>
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="mb-8 rounded-2xl overflow-hidden shadow-lg">
                        <img src="<?php the_post_thumbnail_url( 'large' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full" loading="lazy">
                    </div>
                    <?php endif; ?>
                    <div class="prose prose-lg prose-gray psi-content max-w-none">
                        <?php the_content(); ?>
                    </div>
                    <?php psi_papeng_share_buttons(); ?>
                    <?php
                    the_tags( '<div class="flex flex-wrap gap-2 mt-6">', '<span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-medium">', '</span>' );
                    ?>
                </article>

                <!-- Related Posts -->
                <?php
                $related = new WP_Query( [
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                    'post__not_in'   => [ get_the_ID() ],
                    'category__in'   => wp_list_pluck( $cats ?: [], 'term_id' ),
                ] );
                if ( $related->have_posts() ) :
                ?>
                <div class="mt-12 pt-10 border-t border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Berita Terkait</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                        <article class="group">
                            <a href="<?php the_permalink(); ?>" class="block aspect-video bg-gray-200 rounded-xl overflow-hidden mb-3">
                                <?php if ( has_post_thumbnail() ) : ?>
                                <img src="<?php the_post_thumbnail_url( 'psi-thumb' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                <?php endif; ?>
                            </a>
                            <h4 class="font-semibold text-gray-900 text-sm leading-snug line-clamp-2">
                                <a href="<?php the_permalink(); ?>" class="hover:text-red-600 transition-colors"><?php the_title(); ?></a>
                            </h4>
                        </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Comments -->
                <?php if ( comments_open() || get_comments_number() ) : comments_template(); endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <?php if ( is_active_sidebar( 'sidebar-main' ) ) : dynamic_sidebar( 'sidebar-main' ); endif; ?>
            </aside>
        </div>
    </div>
</div>
<?php get_footer(); ?>
