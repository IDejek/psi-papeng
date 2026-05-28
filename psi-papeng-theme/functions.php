<?php
/**
 * PSI Papua Pegunungan Theme Functions
 *
 * @package PSI_Papeng
 * @version 1.0.0
 * @author Iqbal Tombinawa <tombinawaiqbal@gmail.com>
 */

defined( 'ABSPATH' ) || exit;

define( 'PSI_PAPENG_VERSION', '1.0.0' );
define( 'PSI_PAPENG_DIR', get_template_directory() );
define( 'PSI_PAPENG_URI', get_template_directory_uri() );

/* ── Theme Setup ──────────────────────────────────────────── */
add_action( 'after_setup_theme', 'psi_papeng_setup' );
function psi_papeng_setup(): void {
    load_theme_textdomain( 'psi-papeng', PSI_PAPENG_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );

    add_image_size( 'psi-hero', 1920, 800, true );
    add_image_size( 'psi-leader', 400, 500, true );
    add_image_size( 'psi-dpd', 500, 600, true );
    add_image_size( 'psi-gallery', 600, 400, true );
    add_image_size( 'psi-thumb', 400, 250, true );

    register_nav_menus( [
        'primary'   => esc_html__( 'Primary Menu', 'psi-papeng' ),
        'footer'    => esc_html__( 'Footer Menu', 'psi-papeng' ),
        'mobile'    => esc_html__( 'Mobile Menu', 'psi-papeng' ),
    ] );
}

/* ── Enqueue Scripts & Styles ─────────────────────────────── */
add_action( 'wp_enqueue_scripts', 'psi_papeng_enqueue' );
function psi_papeng_enqueue(): void {
    /* Tailwind CSS CDN */
    wp_enqueue_style( 'tailwindcss', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css', [], null );

    /* Google Fonts – Inter */
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap', [], null );

    /* Theme custom CSS */
    wp_enqueue_style( 'psi-papeng-custom', PSI_PAPENG_URI . '/assets/css/custom.css', [ 'tailwindcss' ], PSI_PAPENG_VERSION );

    /* Main JS */
    wp_enqueue_script( 'psi-papeng-main', PSI_PAPENG_URI . '/assets/js/main.js', [], PSI_PAPENG_VERSION, true );

    /* Localize AJAX */
    wp_localize_script( 'psi-papeng-main', 'psiAjax', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'psi_papeng_nonce' ),
    ] );

    /* Comment reply */
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

/* ── Admin Enqueue ────────────────────────────────────────── */
add_action( 'admin_enqueue_scripts', 'psi_papeng_admin_enqueue' );
function psi_papeng_admin_enqueue( $hook ): void {
    if ( in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        wp_enqueue_style( 'psi-papeng-admin', PSI_PAPENG_URI . '/assets/css/admin.css', [], PSI_PAPENG_VERSION );
        wp_enqueue_script( 'psi-papeng-admin-js', PSI_PAPENG_URI . '/assets/js/admin.js', [ 'jquery' ], PSI_PAPENG_VERSION, true );
    }
}

/* ── Widget Areas ─────────────────────────────────────────── */
add_action( 'widgets_init', 'psi_papeng_widgets_init' );
function psi_papeng_widgets_init(): void {
    register_sidebar( [
        'name'          => esc_html__( 'Sidebar Utama', 'psi-papeng' ),
        'id'            => 'sidebar-main',
        'description'   => esc_html__( 'Sidebar utama untuk halaman berita', 'psi-papeng' ),
        'before_widget' => '<div id="%1$s" class="psi-widget mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="psi-widget-title text-lg font-bold mb-3 pb-2 border-b-2 border-red-600">',
        'after_title'   => '</h4>',
    ] );
    register_sidebar( [
        'name'          => esc_html__( 'Footer Kolom 1', 'psi-papeng' ),
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="psi-footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="text-white font-bold text-lg mb-4">',
        'after_title'   => '</h4>',
    ] );
    register_sidebar( [
        'name'          => esc_html__( 'Footer Kolom 2', 'psi-papeng' ),
        'id'            => 'footer-2',
        'before_widget' => '<div id="%1$s" class="psi-footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="text-white font-bold text-lg mb-4">',
        'after_title'   => '</h4>',
    ] );
    register_sidebar( [
        'name'          => esc_html__( 'Footer Kolom 3', 'psi-papeng' ),
        'id'            => 'footer-3',
        'before_widget' => '<div id="%1$s" class="psi-footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="text-white font-bold text-lg mb-4">',
        'after_title'   => '</h4>',
    ] );
}

/* ── Custom Post Types ────────────────────────────────────── */
require_once PSI_PAPENG_DIR . '/inc/cpt.php';

/* ── Meta Boxes ───────────────────────────────────────────── */
require_once PSI_PAPENG_DIR . '/inc/metaboxes.php';

/* ── Customizer ───────────────────────────────────────────── */
require_once PSI_PAPENG_DIR . '/inc/customizer.php';

/* ── Breadcrumb ───────────────────────────────────────────── */
function psi_papeng_breadcrumb(): void {
    if ( is_front_page() ) return;
    echo '<nav aria-label="Breadcrumb" class="psi-breadcrumb mb-6">';
    echo '<ol class="flex flex-wrap items-center text-sm text-gray-500">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="hover:text-red-600 transition-colors">' . esc_html__( 'Beranda', 'psi-papeng' ) . '</a></li>';
    if ( is_category() || is_single() ) {
        echo '<li class="mx-2">/</li>';
        $cats = get_the_category();
        if ( $cats ) {
            echo '<li><a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" class="hover:text-red-600 transition-colors">' . esc_html( $cats[0]->name ) . '</a></li>';
        }
        if ( is_single() ) {
            echo '<li class="mx-2">/</li>';
            echo '<li class="text-gray-800 font-medium">' . esc_html( get_the_title() ) . '</li>';
        }
    } elseif ( is_page() ) {
        echo '<li class="mx-2">/</li>';
        echo '<li class="text-gray-800 font-medium">' . esc_html( get_the_title() ) . '</li>';
    } elseif ( is_search() ) {
        echo '<li class="mx-2">/</li>';
        echo '<li class="text-gray-800 font-medium">' . esc_html__( 'Hasil Pencarian', 'psi-papeng' ) . '</li>';
    } elseif ( is_404() ) {
        echo '<li class="mx-2">/</li>';
        echo '<li class="text-gray-800 font-medium">' . esc_html__( 'Tidak Ditemukan', 'psi-papeng' ) . '</li>';
    }
    echo '</ol></nav>';
}

/* ── Excerpt Length ───────────────────────────────────────── */
add_filter( 'excerpt_length', 'psi_papeng_excerpt_length' );
function psi_papeng_excerpt_length( $length ): int {
    return 20;
}
add_filter( 'excerpt_more', 'psi_papeng_excerpt_more' );
function psi_papeng_excerpt_more( $more ): string {
    return '...';
}

/* ── Schema.org JSON-LD ───────────────────────────────────── */
add_action( 'wp_head', 'psi_papeng_schema_org', 99 );
function psi_papeng_schema_org(): void {
    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => 'DPW PSI Papua Pegunungan',
        'url'      => esc_url( home_url( '/' ) ),
        'logo'     => esc_url( get_custom_logo_url() ?: PSI_PAPENG_URI . '/assets/img/logo.png' ),
        'address'  => [
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Papua Pegunungan',
            'addressCountry'  => 'ID',
        ],
        'sameAs' => [
            esc_url( get_theme_mod( 'psi_social_facebook', '#' ) ),
            esc_url( get_theme_mod( 'psi_social_instagram', '#' ) ),
            esc_url( get_theme_mod( 'psi_social_youtube', '#' ) ),
        ],
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

    /* Article schema for single posts */
    if ( is_single() ) {
        $article = [
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            'headline'         => esc_html( get_the_title() ),
            'datePublished'    => esc_html( get_the_date( 'c' ) ),
            'dateModified'     => esc_html( get_the_modified_date( 'c' ) ),
            'author'           => [ '@type' => 'Person', 'name' => esc_html( get_the_author() ) ],
            'publisher'        => [ '@type' => 'Organization', 'name' => 'DPW PSI Papua Pegunungan' ],
            'mainEntityOfPage' => esc_url( get_permalink() ),
        ];
        if ( has_post_thumbnail() ) {
            $article['image'] = esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) );
        }
        echo '<script type="application/ld+json">' . wp_json_encode( $article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}

/* ── Open Graph & Twitter Cards ───────────────────────────── */
add_action( 'wp_head', 'psi_papeng_og_tags' );
function psi_papeng_og_tags(): void {
    if ( is_singular() ) {
        global $post;
        $thumb = has_post_thumbnail( $post->ID ) ? get_the_post_thumbnail_url( $post->ID, 'large' ) : '';
        echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( wp_strip_all_tags( get_the_excerpt() ) ) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
        if ( $thumb ) {
            echo '<meta property="og:image" content="' . esc_url( $thumb ) . '">' . "\n";
        }
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";
    } else {
        $site_name = get_bloginfo( 'name' );
        $desc      = get_bloginfo( 'description' );
        echo '<meta property="og:title" content="' . esc_attr( $site_name ) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '">' . "\n";
    }
}

/* ── Reading Progress Bar ─────────────────────────────────── */
add_action( 'wp_body_open', 'psi_papeng_reading_progress' );
function psi_papeng_reading_progress(): void {
    if ( ! is_single() ) return;
    echo '<div id="psi-reading-progress" class="fixed top-0 left-0 h-1 bg-red-600 z-[9999]" style="width:0%;transition:width .1s linear;"></div>';
}

/* ── Body Classes ─────────────────────────────────────────── */
add_filter( 'body_class', 'psi_papeng_body_classes' );
function psi_papeng_body_classes( $classes ): array {
    $classes[] = 'font-sans antialiased';
    if ( is_front_page() ) $classes[] = 'psi-home';
    if ( is_singular() )   $classes[] = 'psi-single';
    return $classes;
}

/* ── Pingback Header ──────────────────────────────────────── */
add_action( 'wp_head', 'psi_papeng_pingback' );
function psi_papeng_pingback(): void {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}

/* ── Disable Emoji Scripts (Performance) ──────────────────── */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* ── Custom Login Logo ────────────────────────────────────── */
add_action( 'login_enqueue_scripts', 'psi_papeng_login_logo' );
function psi_papeng_login_logo(): void {
    $logo_id = get_theme_mod( 'custom_logo' );
    if ( $logo_id ) {
        $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
        if ( $logo_url ) {
            echo '<style type="text/css">#login h1 a{background-image:url(' . esc_url( $logo_url ) . ');background-size:contain;width:100%;height:80px;}</style>';
        }
    }
}
add_filter( 'login_headerurl', function (): string { return home_url( '/' ); } );
add_filter( 'login_headertext', function (): string { return get_bloginfo( 'name' ); } );

/* ── Social Share Buttons ─────────────────────────────────── */
function psi_papeng_share_buttons(): void {
    $url   = esc_url( get_permalink() );
    $title = esc_attr( get_the_title() );
    echo '<div class="psi-share flex items-center gap-3 my-4">';
    echo '<span class="text-sm font-semibold text-gray-600">' . esc_html__( 'Bagikan:', 'psi-papeng' ) . '</span>';
    echo '<a href="https://www.facebook.com/sharer.php?u=' . $url . '" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs hover:bg-blue-700 transition-colors" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>';
    echo '<a href="https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title . '" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-sky-500 text-white flex items-center justify-center text-xs hover:bg-sky-600 transition-colors" aria-label="Twitter"><i class="fab fa-twitter"></i></a>';
    echo '<a href="https://wa.me/?text=' . $title . '%20' . $url . '" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-xs hover:bg-green-600 transition-colors" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>';
    echo '</div>';
}

/* ── Pagination ───────────────────────────────────────────── */
function psi_papeng_pagination(): void {
    the_posts_pagination( [
        'mid_size'  => 2,
        'prev_text' => '<i class="fas fa-chevron-left"></i>',
        'next_text' => '<i class="fas fa-chevron-right"></i>',
        'class'     => 'psi-pagination flex items-center gap-2 justify-center mt-8',
    ] );
}
