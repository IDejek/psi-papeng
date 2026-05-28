<?php
/**
 * 404 Not Found
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="min-h-[70vh] flex items-center justify-center bg-white">
    <div class="text-center px-4">
        <div class="text-8xl md:text-9xl font-black text-red-600 mb-4" style="font-family:'Poppins',sans-serif;">404</div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Halaman Tidak Ditemukan</h1>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan.</p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-2 px-7 py-3.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-300 shadow-lg">
            <i class="fas fa-home text-sm"></i> Kembali ke Beranda
        </a>
    </div>
</div>
<?php get_footer(); ?>
