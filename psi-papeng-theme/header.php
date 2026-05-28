<?php
/**
 * Header
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php psi_papeng_preload(); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ═══ TOPBAR ═══ -->
<div class="psi-topbar bg-black text-white text-xs md:text-sm" id="psiTopbar">
    <div class="max-w-7xl mx-auto px-4 py-1.5 flex flex-wrap items-center justify-between">
        <div class="flex items-center gap-4 md:gap-6 flex-wrap">
            <span class="flex items-center gap-1.5">
                <i class="fas fa-clock text-yellow-400 text-xs"></i>
                <span class="text-gray-400">WIT:</span>
                <span id="witClock" class="font-mono font-semibold text-yellow-400">--:--:--</span>
            </span>
            <span class="flex items-center gap-1.5">
                <i class="fas fa-clock text-green-400 text-xs"></i>
                <span class="text-gray-400">WIB:</span>
                <span id="wibClock" class="font-mono font-semibold text-green-400">--:--:--</span>
            </span>
        </div>
        <div class="flex items-center gap-3 mt-1 md:mt-0">
            <?php
            $socials = [
                'psi_social_facebook'  => 'fab fa-facebook-f',
                'psi_social_instagram' => 'fab fa-instagram',
                'psi_social_youtube'   => 'fab fa-youtube',
                'psi_social_twitter'   => 'fab fa-x-twitter',
                'psi_social_tiktok'    => 'fab fa-tiktok',
            ];
            foreach ( $socials as $key => $icon ) :
                $url = get_theme_mod( $key, '#' );
                if ( $url && $url !== '#' ) :
            ?>
                <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition-colors" aria-label="<?php echo esc_attr( str_replace( 'psi_social_', '', $key ) ); ?>">
                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                </a>
            <?php endif; endforeach; ?>
        </div>
    </div>
</div>

<!-- ═══ NAVBAR ═══ -->
<header class="psi-navbar sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-lg border-b border-gray-100 transition-all duration-300" id="psiNavbar">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 flex-shrink-0">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <span class="text-xl md:text-2xl font-black" style="font-family:'Poppins',sans-serif; color:#D6001C;">PSI</span>
                    <span class="hidden sm:inline text-xs md:text-sm font-medium text-gray-600 leading-tight">Papua<br>Pegunungan</span>
                <?php endif; ?>
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden lg:flex items-center gap-1" aria-label="Navigasi Utama">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( [
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'depth'          => 3,
                        'walker'         => new PSI_Nav_Walker(),
                    ] );
                } else {
                    echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="psi-nav-link">Beranda</a>';
                    echo '<a href="' . esc_url( home_url( '/?page_id=' . psi_get_page_id( 'profil' ) ) ) . '" class="psi-nav-link">Profil</a>';
                    echo '<a href="' . esc_url( home_url( '/dpd-psi' ) ) . '" class="psi-nav-link">DPD</a>';
                    echo '<a href="' . esc_url( home_url( '/video' ) ) . '" class="psi-nav-link">Video</a>';
                    echo '<a href="' . esc_url( home_url( '/galeri' ) ) . '" class="psi-nav-link">Galeri</a>';
                    echo '<a href="' . esc_url( home_url( '/?page_id=' . psi_get_page_id( 'kontak' ) ) ) . '" class="psi-nav-link">Kontak</a>';
                }
                ?>
            </nav>

            <!-- CTA + Mobile Toggle -->
            <div class="flex items-center gap-3">
                <a href="<?php echo esc_url( get_theme_mod( 'psi_member_url', 'https://psi.id/menjadi-anggota' ) ); ?>"
                   class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                    <i class="fas fa-user-plus text-xs"></i> Daftar Anggota
                </a>
                <button class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors" id="psiMobileToggle" aria-label="Toggle Menu" aria-expanded="false">
                    <i class="fas fa-bars text-xl text-gray-700" id="psiMobileIcon"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="lg:hidden hidden bg-white border-t border-gray-100 shadow-xl" id="psiMobileMenu">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            <?php
            if ( has_nav_menu( 'mobile' ) ) {
                wp_nav_menu( [
                    'theme_location' => 'mobile',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'depth'          => 2,
                ] );
            } elseif ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'depth'          => 2,
                ] );
            } else {
                $fallback_links = [
                    [ 'url' => home_url( '/' ), 'text' => 'Beranda' ],
                    [ 'url' => home_url( '/?page_id=' . psi_get_page_id( 'profil' ) ), 'text' => 'Profil' ],
                    [ 'url' => home_url( '/dpd-psi' ), 'text' => 'DPD' ],
                    [ 'url' => home_url( '/video' ), 'text' => 'Video' ],
                    [ 'url' => home_url( '/galeri' ), 'text' => 'Galeri' ],
                    [ 'url' => home_url( '/?page_id=' . psi_get_page_id( 'kontak' ) ), 'text' => 'Kontak' ],
                ];
                foreach ( $fallback_links as $link ) {
                    echo '<a href="' . esc_url( $link['url'] ) . '" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 font-medium transition-colors">' . esc_html( $link['text'] ) . '</a>';
                }
            }
            ?>
            <a href="<?php echo esc_url( get_theme_mod( 'psi_member_url', 'https://psi.id/menjadi-anggota' ) ); ?>"
               class="block text-center mt-4 px-5 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-user-plus mr-2"></i>Daftar Anggota
            </a>
        </div>
    </div>
</header>

<?php
/* Helper: Get page ID by slug */
function psi_get_page_id( $slug ): int {
    $page = get_page_by_path( $slug );
    return $page ? $page->ID : 0;
}

/* Custom Nav Walker for premium styling */
class PSI_Nav_Walker extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void {
        $classes = empty( $item->classes ) ? [] : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $atts = [];
        $atts['href']   = ! empty( $item->url ) ? $item->url : '';
        $atts['class']  = 'psi-nav-link relative px-3 py-2 text-sm font-medium text-gray-700 hover:text-red-600 transition-colors duration-200';
        if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current_page_item', $classes, true ) ) {
            $atts['class'] .= ' text-red-600';
        }
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
            }
        }
        $output .= '<a' . $attributes . '>';
        $output .= esc_html( apply_filters( 'the_title', $item->title, $item->ID ) );
        $output .= '</a>';
    }
    function start_lvl( &$output, $depth = 0, $args = null ): void {
        $output .= '<div class="absolute top-full left-0 mt-0 w-56 bg-white rounded-xl shadow-2xl border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 translate-y-2 z-50">';
    }
    function end_lvl( &$output, $depth = 0, $args = null ): void {
        $output .= '</div>';
    }
}
?>
