<?php
/**
 * Fallback Index
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="py-16 md:py-24 bg-white min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4"><a href="<?php the_permalink(); ?>" class="hover:text-red-600 transition-colors"><?php the_title(); ?></a></h2>
                    <div class="text-sm text-gray-400 mb-4"><i class="far fa-calendar mr-1"></i> <?php echo get_the_date(); ?></div>
                    <div class="prose prose-gray psi-content"><?php the_excerpt(); ?></div>
                    <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-2 text-red-600 font-semibold text-sm mt-4 hover:text-red-700">Baca Selengkapnya <i class="fas fa-arrow-right text-xs"></i></a>
                </article>
            <?php endwhile; ?>
            <?php psi_papeng_pagination(); ?>
        <?php else : ?>
            <div class="text-center py-20 text-gray-400">
                <i class="fas fa-folder-open text-5xl mb-4"></i>
                <p>Belum ada konten.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>
