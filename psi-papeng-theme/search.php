<?php
/**
 * Search Results
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;">
            <?php printf( esc_html__( 'Hasil Pencarian: %s', 'psi-papeng' ), '<span class="text-red-400">' . esc_html( get_search_query() ) . '</span>' ); ?>
        </h1>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-8 max-w-md">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="relative">
                <input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Cari lagi..." class="w-full px-5 py-3 pl-12 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-sm" required>
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </form>
        </div>
        <?php if ( have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while ( have_posts() ) : the_post(); ?>
            <article class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 hover:-translate-y-1 group flex flex-col">
                <a href="<?php the_permalink(); ?>" class="block aspect-video bg-gray-200 overflow-hidden">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <img src="<?php the_post_thumbnail_url( 'psi-thumb' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                    <?php endif; ?>
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <span class="text-xs text-gray-400 mb-2"><?php echo get_post_type_object( get_post_type() )->labels->singular_name ?? ''; ?></span>
                    <h3 class="font-bold text-gray-900 mb-2 leading-snug line-clamp-2 flex-1">
                        <a href="<?php the_permalink(); ?>" class="hover:text-red-600 transition-colors"><?php the_title(); ?></a>
                    </h3>
                    <a href="<?php the_permalink(); ?>" class="text-red-600 text-sm font-semibold">Baca <i class="fas fa-arrow-right text-xs ml-1"></i></a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <?php psi_papeng_pagination(); ?>
        <?php else : ?>
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-search text-5xl mb-4"></i>
            <p class="text-lg">Tidak ada hasil untuk pencarian tersebut.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>
