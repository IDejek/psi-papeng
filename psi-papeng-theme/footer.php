<?php
/**
 * Footer
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
?>

<!-- ═══ FOOTER ═══ -->
<footer class="psi-footer bg-gray-900 text-gray-300 relative overflow-hidden">
    <!-- Decorative Gradient -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-600 via-yellow-500 to-red-600"></div>

    <div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

            <!-- Col 1: About -->
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="brightness-0 invert"><?php the_custom_logo(); ?></div>
                    <?php else : ?>
                        <span class="text-2xl font-black text-white" style="font-family:'Poppins',sans-serif;">PSI</span>
                    <?php endif; ?>
                    <div>
                        <div class="text-white font-bold text-sm leading-tight">DPW PSI</div>
                        <div class="text-gray-400 text-xs">Papua Pegunungan</div>
                    </div>
                </div>
                <p class="text-sm leading-relaxed text-gray-400 mb-5">
                    <?php echo esc_html( get_bloginfo( 'description' ) ?: 'Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Provinsi Papua Pegunungan.' ); ?>
                </p>
                <?php if ( get_theme_mod( 'psi_footer_text' ) ) : ?>
                    <p class="text-xs text-gray-500"><?php echo esc_html( get_theme_mod( 'psi_footer_text' ) ); ?></p>
                <?php endif; ?>
            </div>

            <!-- Col 2: Quick Links -->
            <div>
                <h4 class="text-white font-bold text-lg mb-5 relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-0.5 after:bg-red-600"><?php esc_html_e( 'Tautan Cepat', 'psi-papeng' ); ?></h4>
                <?php if ( is_active_sidebar( 'footer-1' ) ) : dynamic_sidebar( 'footer-1' ); else : ?>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-red-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-red-600"></i>Beranda</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/?page_id=' . psi_get_page_id( 'profil' ) ) ); ?>" class="hover:text-red-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-red-600"></i>Profil</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/dpd-psi' ) ); ?>" class="hover:text-red-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-red-600"></i>DPD PSI</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/video' ) ); ?>" class="hover:text-red-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-red-600"></i>Video</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/galeri' ) ); ?>" class="hover:text-red-400 transition-colors flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-red-600"></i>Galeri</a></li>
                </ul>
                <?php endif; ?>
            </div>

            <!-- Col 3: Contact -->
            <div>
                <h4 class="text-white font-bold text-lg mb-5 relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-0.5 after:bg-red-600"><?php esc_html_e( 'Kontak', 'psi-papeng' ); ?></h4>
                <ul class="space-y-3 text-sm">
                    <?php if ( $addr = get_theme_mod( 'psi_contact_address' ) ) : ?>
                    <li class="flex items-start gap-3"><i class="fas fa-map-marker-alt text-red-500 mt-1 flex-shrink-0"></i><span><?php echo esc_html( $addr ); ?></span></li>
                    <?php endif; ?>
                    <?php if ( $phone = get_theme_mod( 'psi_contact_phone' ) ) : ?>
                    <li class="flex items-center gap-3"><i class="fas fa-phone text-red-500 flex-shrink-0"></i><a href="tel:<?php echo esc_attr( $phone ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $phone ); ?></a></li>
                    <?php endif; ?>
                    <?php if ( $email = get_theme_mod( 'psi_contact_email' ) ) : ?>
                    <li class="flex items-center gap-3"><i class="fas fa-envelope text-red-500 flex-shrink-0"></i><a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-white transition-colors"><?php echo esc_html( $email ); ?></a></li>
                    <?php endif; ?>
                    <?php if ( $wa = get_theme_mod( 'psi_contact_whatsapp' ) ) : ?>
                    <li class="flex items-center gap-3"><i class="fab fa-whatsapp text-green-500 flex-shrink-0"></i><a href="https://wa.me/<?php echo esc_attr( $wa ); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">WhatsApp</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Col 4: Social -->
            <div>
                <h4 class="text-white font-bold text-lg mb-5 relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-0.5 after:bg-red-600"><?php esc_html_e( 'Ikuti Kami', 'psi-papeng' ); ?></h4>
                <div class="flex flex-wrap gap-3 mb-6">
                    <?php
                    $social_icons = [
                        'psi_social_facebook'  => [ 'fab fa-facebook-f', 'bg-blue-600 hover:bg-blue-700' ],
                        'psi_social_instagram' => [ 'fab fa-instagram', 'bg-pink-600 hover:bg-pink-700' ],
                        'psi_social_youtube'   => [ 'fab fa-youtube', 'bg-red-600 hover:bg-red-700' ],
                        'psi_social_twitter'   => [ 'fab fa-x-twitter', 'bg-gray-700 hover:bg-gray-800' ],
                        'psi_social_tiktok'    => [ 'fab fa-tiktok', 'bg-gray-800 hover:bg-black' ],
                    ];
                    foreach ( $social_icons as $key => $cfg ) :
                        $url = get_theme_mod( $key, '#' );
                        if ( $url && $url !== '#' ) :
                    ?>
                        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg <?php echo esc_attr( $cfg[1] ); ?> text-white flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg" aria-label="<?php echo esc_attr( str_replace( 'psi_social_', '', $key ) ); ?>">
                            <i class="<?php echo esc_attr( $cfg[0] ); ?>"></i>
                        </a>
                    <?php endif; endforeach; ?>
                </div>
                <?php if ( is_active_sidebar( 'footer-3' ) ) : dynamic_sidebar( 'footer-3' ); endif; ?>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-5 flex flex-col md:flex-row items-center justify-between gap-3 text-xs text-gray-500">
            <p><?php echo esc_html( get_theme_mod( 'psi_footer_copyright', '© 2026 DPW PSI Papua Pegunungan. All rights reserved.' ) ); ?></p>
            <p class="flex items-center gap-1">Developed by <a href="mailto:tombinawaiqbal@gmail.com" class="text-gray-400 hover:text-white transition-colors font-medium">Iqbal Tombinawa</a></p>
        </div>
    </div>
</footer>

<!-- WhatsApp Float -->
<?php if ( $wa = get_theme_mod( 'psi_contact_whatsapp', '6282267218125' ) ) : ?>
<a href="https://wa.me/<?php echo esc_attr( $wa ); ?>" target="_blank" rel="noopener noreferrer" id="psiWAFloat" class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center text-white text-2xl shadow-2xl hover:bg-green-600 hover:scale-110 transition-all duration-300" aria-label="WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>

<!-- Back to Top -->
<button id="psiBackToTop" class="fixed bottom-6 left-6 z-50 w-12 h-12 bg-red-600 rounded-full flex items-center justify-center text-white shadow-xl hover:bg-red-700 hover:scale-110 transition-all duration-300 opacity-0 invisible" aria-label="Kembali ke atas">
    <i class="fas fa-chevron-up"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>
