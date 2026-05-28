<?php
/**
 * Template Name: Galeri
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $gallery_cats = get_terms( [ 'taxonomy' => 'psi_gallery_cat', 'hide_empty' => true ] );
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;"><?php the_title(); ?></h1>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <?php if ( $gallery_cats && ! is_wp_error( $gallery_cats ) ) : ?>
        <div class="flex flex-wrap gap-2 mb-8">
            <button class="psi-gallery-filter active px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white transition-all" data-cat="0">Semua</button>
            <?php foreach ( $gallery_cats as $cat ) : ?>
            <button class="psi-gallery-filter px-4 py-2 text-sm font-medium rounded-lg bg-white text-gray-600 border border-gray-200 hover:border-red-300 hover:text-red-600 transition-all" data-cat="<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php
        $paged   = get_query_var( 'paged' ) ?: 1;
        $galleries = new WP_Query( [
            'post_type'      => 'psi_gallery',
            'posts_per_page' => 24,
            'paged'          => $paged,
        ] );
        if ( $galleries->have_posts() ) :
        ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="psiGalleryGrid">
            <?php $gi = 0; while ( $galleries->have_posts() ) : $galleries->the_post(); $gi++;
                $cats = wp_get_post_terms( get_the_ID(), 'psi_gallery_cat' );
                $cat_ids = wp_list_pluck( $cats, 'term_id' );
                $full = get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?: '';
                $thumb = get_the_post_thumbnail_url( get_the_ID(), 'psi-gallery' ) ?: '';
            ?>
            <div class="psi-gallery-item rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 cursor-pointer aspect-square" data-cats="<?php echo esc_attr( implode( ',', $cat_ids ) ); ?>" data-full="<?php echo esc_url( $full ); ?>" data-title="<?php echo esc_attr( get_the_title() ); ?>">
                <?php if ( $thumb ) : ?>
                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" loading="lazy">
                <?php else : ?>
                <div class="w-full h-full flex items-center justify-center bg-gray-200"><i class="fas fa-image text-3xl text-gray-300"></i></div>
                <?php endif; ?>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php psi_papeng_pagination(); ?>
        <?php else : ?>
        <div class="text-center py-20 text-gray-400"><i class="fas fa-images text-5xl mb-4"></i><p>Belum ada galeri.</p></div>
        <?php endif; ?>
    </div>
</div>

<!-- Lightbox -->
<div class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/95 p-4" id="psiLightbox">
    <button class="absolute top-4 right-4 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-colors text-xl" id="psiLightboxClose" aria-label="Tutup"><i class="fas fa-times"></i></button>
    <button class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-colors" id="psiLightboxPrev" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
    <button class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-colors" id="psiLightboxNext" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>
    <img src="" alt="" class="max-w-full max-h-[85vh] object-contain rounded-lg" id="psiLightboxImg">
    <p class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white text-sm font-medium" id="psiLightboxTitle"></p>
</div>

<?php get_footer(); ?>
