<?php
/**
 * Single Leadership / Ketua DPW Profile
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $position = get_post_meta( get_the_ID(), '_psi_lead_position', true );
 $fb       = get_post_meta( get_the_ID(), '_psi_lead_facebook', true );
 $ig       = get_post_meta( get_the_ID(), '_psi_lead_instagram', true );
 $tw       = get_post_meta( get_the_ID(), '_psi_lead_twitter', true );
 $thumb    = get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?: '';
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;"><?php the_title(); ?></h1>
        <?php if ( $position ) : ?>
        <p class="text-red-300 text-lg mt-2 font-semibold"><?php echo esc_html( $position ); ?></p>
        <?php endif; ?>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Photo -->
            <div class="md:col-span-1">
                <div class="bg-gray-50 rounded-2xl overflow-hidden shadow-lg">
                    <div class="aspect-[3/4] bg-gradient-to-br from-gray-200 to-gray-300">
                        <?php if ( $thumb ) : ?>
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-full h-full object-cover" loading="lazy">
                        <?php else : ?>
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-user-tie text-6xl text-gray-300"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <?php if ( $fb || $ig || $tw ) : ?>
                        <div class="flex justify-center gap-3">
                            <?php if ( $fb ) : ?><a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm hover:bg-blue-700 transition-colors"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                            <?php if ( $ig ) : ?><a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-pink-600 text-white flex items-center justify-center text-sm hover:bg-pink-700 transition-colors"><i class="fab fa-instagram"></i></a><?php endif; ?>
                            <?php if ( $tw ) : ?><a href="<?php echo esc_url( $tw ); ?>" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-gray-800 text-white flex items-center justify-center text-sm hover:bg-black transition-colors"><i class="fab fa-x-twitter"></i></a><?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Bio -->
            <div class="md:col-span-2">
                <div class="prose prose-lg prose-gray psi-content max-w-none">
                    <?php the_content(); ?>
                </div>
                <?php
                /* Gallery from content */
                $gallery_ids = get_post_gallery( get_the_ID(), false );
                if ( $gallery_ids ) :
                    $ids = explode( ',', $gallery_ids['ids'] );
                    if ( $ids ) :
                ?>
                <div class="mt-10">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Galeri Foto</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <?php foreach ( array_slice( $ids, 0, 6 ) as $aid ) :
                            $img_url = wp_get_attachment_image_url( $aid, 'psi-gallery' );
                            if ( $img_url ) :
                        ?>
                        <div class="aspect-square rounded-xl overflow-hidden">
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" loading="lazy">
                        </div>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
                <?php endif; endif; ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
