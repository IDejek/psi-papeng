<?php
/**
 * Template Name: Kontak
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
 $contact_addr = get_theme_mod( 'psi_contact_address', '' );
 $contact_phone = get_theme_mod( 'psi_contact_phone', '' );
 $contact_email = get_theme_mod( 'psi_contact_email', 'info@psipapeng.id' );
 $contact_wa    = get_theme_mod( 'psi_contact_whatsapp', '6282267218125' );
 $contact_map   = get_theme_mod( 'psi_contact_map', '' );
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;"><?php the_title(); ?></h1>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Hubungi Kami</h2>
                <div class="space-y-6">
                    <?php if ( $contact_addr ) : ?>
                                        <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fas fa-map-marker-alt text-red-600"></i></div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Alamat Kantor</h4>
                            <p class="text-gray-600 text-sm leading-relaxed"><?php echo esc_html( $contact_addr ); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $contact_phone ) : ?>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fas fa-phone text-red-600"></i></div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Telepon</h4>
                            <a href="tel:<?php echo esc_attr( $contact_phone ); ?>" class="text-gray-600 text-sm hover:text-red-600 transition-colors"><?php echo esc_html( $contact_phone ); ?></a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ( $contact_email ) : ?>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fas fa-envelope text-red-600"></i></div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Email</h4>
                            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>" class="text-gray-600 text-sm hover:text-red-600 transition-colors"><?php echo esc_html( $contact_email ); ?></a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0"><i class="fab fa-whatsapp text-green-600 text-xl"></i></div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">WhatsApp</h4>
                            <a href="https://wa.me/<?php echo esc_attr( $contact_wa ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 text-sm hover:text-green-600 transition-colors">+62 822 6721 8125</a>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="mt-8 pt-8 border-t border-gray-100">
                    <h4 class="font-semibold text-gray-900 mb-4">Media Sosial</h4>
                    <div class="flex gap-3">
                        <?php
                        $social_links = [
                            'psi_social_facebook'  => [ 'fab fa-facebook-f', 'bg-blue-600 hover:bg-blue-700' ],
                            'psi_social_instagram' => [ 'fab fa-instagram', 'bg-pink-600 hover:bg-pink-700' ],
                            'psi_social_youtube'   => [ 'fab fa-youtube', 'bg-red-600 hover:bg-red-700' ],
                            'psi_social_twitter'   => [ 'fab fa-x-twitter', 'bg-gray-800 hover:bg-black' ],
                            'psi_social_tiktok'    => [ 'fab fa-tiktok', 'bg-gray-800 hover:bg-black' ],
                        ];
                        foreach ( $social_links as $key => $cfg ) :
                            $url = get_theme_mod( $key, '#' );
                            if ( $url && $url !== '#' ) :
                        ?>
                        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg <?php echo esc_attr( $cfg[1] ); ?> text-white flex items-center justify-center transition-all duration-300 hover:-translate-y-1" aria-label="<?php echo esc_attr( str_replace( 'psi_social_', '', $key ) ); ?>">
                            <i class="<?php echo esc_attr( $cfg[0] ); ?>"></i>
                        </a>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>
                <form id="psiContactForm" class="space-y-5">
                    <?php wp_nonce_field( 'psi_contact_nonce', 'psi_contact_nonce_field' ); ?>
                    <div>
                        <label for="psiContactName" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="psiContactName" name="name" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm transition-shadow">
                    </div>
                    <div>
                        <label for="psiContactEmail" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="psiContactEmail" name="email" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm transition-shadow">
                    </div>
                    <div>
                        <label for="psiContactPhone" class="block text-sm font-medium text-gray-700 mb-1.5">No. Telepon</label>
                        <input type="tel" id="psiContactPhone" name="phone" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm transition-shadow">
                    </div>
                    <div>
                        <label for="psiContactSubject" class="block text-sm font-medium text-gray-700 mb-1.5">Subjek <span class="text-red-500">*</span></label>
                        <input type="text" id="psiContactSubject" name="subject" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm transition-shadow">
                    </div>
                    <div>
                        <label for="psiContactMessage" class="block text-sm font-medium text-gray-700 mb-1.5">Pesan <span class="text-red-500">*</span></label>
                        <textarea id="psiContactMessage" name="message" rows="5" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm transition-shadow resize-none"></textarea>
                    </div>
                    <div id="psiContactResult" class="hidden"></div>
                    <button type="submit" class="w-full px-6 py-3.5 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane text-sm"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>

        <!-- Google Map -->
        <?php if ( $contact_map ) : ?>
        <div class="mt-12 rounded-2xl overflow-hidden shadow-lg">
            <iframe src="<?php echo esc_url( $contact_map ); ?>" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Kantor DPW PSI Papua Pegunungan"></iframe>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>
