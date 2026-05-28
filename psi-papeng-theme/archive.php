<?php
/**
 * Archive Template
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $post_type = get_post_type();
 $title     = get_the_archive_title();
 $desc      = get_the_archive_description();
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;"><?php echo esc_html( $title ); ?></h1>
        <?php if ( $desc ) : ?><p class="text-gray-300 mt-2 max-w-2xl"><?php echo wp_kses_post( $desc ); ?></p><?php endif; ?>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <?php if ( have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while ( have_posts() ) : the_post(); ?>
            <article class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 hover:-translate-y-1 group flex flex-col">
                <a href="<?php the_permalink(); ?>" class="block aspect-video bg-gray-200 overflow-hidden">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <img src="<?php the_post_thumbnail_url( 'psi-thumb' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                    <?php else : ?>
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200"><i class="fas fa-file-alt text-3xl text-gray-300"></i></div>
                    <?php endif; ?>
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <span class="text-xs text-gray-400 mb-2"><i class="far fa-calendar mr-1"></i> <?php echo get_the_date(); ?></span>
                    <h3 class="font-bold text-gray-900 mb-2 leading-snug line-clamp-2 flex-1">
                        <a href="<?php the_permalink(); ?>" class="hover:text-red-600 transition-colors"><?php the_title(); ?></a>
                    </h3>
                    <a href="<?php the_permalink(); ?>" class="text-red-600 text-sm font-semibold hover:text-red-700">Baca Selengkapnya <i class="fas fa-arrow-right text-xs ml-1"></i></a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <?php psi_papeng_pagination(); ?>
        <?php else : ?>
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-folder-open text-5xl mb-4"></i>
            <p class="text-lg">Belum ada konten.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>
