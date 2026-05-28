<?php
/**
 * Single DPD Template
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $ketua   = get_post_meta( get_the_ID(), '_psi_dpd_ketua', true );
 $phone   = get_post_meta( get_the_ID(), '_psi_dpd_phone', true );
 $email   = get_post_meta( get_the_ID(), '_psi_dpd_email', true );
 $address = get_post_meta( get_the_ID(), '_psi_dpd_address', true );
 $members = get_post_meta( get_the_ID(), '_psi_dpd_members', true );
 $thumb   = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: '';
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;"><?php the_title(); ?></h1>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="md:col-span-1">
                <div class="bg-gray-50 rounded-2xl overflow-hidden">
                    <div class="aspect-[4/5] bg-gradient-to-br from-gray-200 to-gray-300">
                        <?php if ( $thumb ) : ?>
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-full h-full object-cover" loading="lazy">
                        <?php else : ?>
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-building text-5xl text-gray-300"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6 text-center">
                        <?php if ( $ketua ) : ?>
                        <h3 class="font-bold text-gray-900 mb-1"><?php echo esc_html( $ketua ); ?></h3>
                        <p class="text-red-600 text-sm font-semibold">Ketua DPD</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="md:col-span-2">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi DPD</h2>
                <div class="space-y-5 mb-8">
                    <?php if ( $ketua ) : ?>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <i class="fas fa-user-tie text-red-600 w-6 text-center"></i>
                        <div><span class="text-xs text-gray-500 block">Ketua DPD</span><span class="font-semibold text-gray-900"><?php echo esc_html( $ketua ); ?></span></div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $members ) : ?>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <i class="fas fa-users text-red-600 w-6 text-center"></i>
                        <div><span class="text-xs text-gray-500 block">Jumlah Anggota</span><span class="font-semibold text-gray-900"><?php echo esc_html( $members ); ?></span></div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $phone ) : ?>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <i class="fas fa-phone text-red-600 w-6 text-center"></i>
                        <div><span class="text-xs text-gray-500 block">Telepon</span><a href="tel:<?php echo esc_attr( $phone ); ?>" class="font-semibold text-gray-900 hover:text-red-600"><?php echo esc_html( $phone ); ?></a></div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $email ) : ?>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <i class="fas fa-envelope text-red-600 w-6 text-center"></i>
                        <div><span class="text-xs text-gray-500 block">Email</span><a href="mailto:<?php echo esc_attr( $email ); ?>" class="font-semibold text-gray-900 hover:text-red-600"><?php echo esc_html( $email ); ?></a></div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $address ) : ?>
                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                        <i class="fas fa-map-marker-alt text-red-600 w-6 text-center mt-0.5"></i>
                        <div><span class="text-xs text-gray-500 block">Alamat</span><span class="font-semibold text-gray-900"><?php echo esc_html( $address ); ?></span></div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if ( get_the_content() ) : ?>
                <div class="prose prose-gray psi-content">
                    <?php the_content(); ?>
                </div>
                <?php endif; ?>
                <div class="mt-8">
                    <a href="<?php echo esc_url( home_url( '/dpd-psi' ) ); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-gray-200 text-gray-600 font-medium rounded-lg hover:border-red-300 hover:text-red-600 transition-all">
                        <i class="fas fa-arrow-left text-sm"></i> Kembali ke Daftar DPD
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
