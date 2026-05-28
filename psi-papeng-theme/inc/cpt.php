<?php
/**
 * Custom Post Types
 * @package PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

/* ── Slider ───────────────────────────────────────────────── */
function psi_register_cpt_slider(): void {
    $labels = [
        'name'               => esc_html__( 'Slider', 'psi-papeng' ),
        'singular_name'      => esc_html__( 'Slide', 'psi-papeng' ),
        'menu_name'          => esc_html__( 'Hero Slider', 'psi-papeng' ),
        'add_new_item'       => esc_html__( 'Tambah Slide Baru', 'psi-papeng' ),
        'edit_item'          => esc_html__( 'Edit Slide', 'psi-papeng' ),
        'all_items'          => esc_html__( 'Semua Slide', 'psi-papeng' ),
    ];
    $args = [
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post',
        'supports'           => [ 'title', 'thumbnail' ],
        'menu_icon'          => 'dashicons-format-gallery',
        'menu_position'      => 5,
    ];
    register_post_type( 'psi_slider', $args );
}
add_action( 'init', 'psi_register_cpt_slider' );

/* ── Leadership ───────────────────────────────────────────── */
function psi_register_cpt_leadership(): void {
    $labels = [
        'name'               => esc_html__( 'Pimpinan', 'psi-papeng' ),
        'singular_name'      => esc_html__( 'Pimpinan', 'psi-papeng' ),
        'menu_name'          => esc_html__( 'Pimpinan', 'psi-papeng' ),
        'add_new_item'       => esc_html__( 'Tambah Pimpinan', 'psi-papeng' ),
        'edit_item'          => esc_html__( 'Edit Pimpinan', 'psi-papeng' ),
    ];
    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 6,
        'has_archive'        => false,
        'rewrite'            => [ 'slug' => 'pimpinan' ],
    ];
    register_post_type( 'psi_leadership', $args );
}
add_action( 'init', 'psi_register_cpt_leadership' );

/* ── Divisions / Bidang ───────────────────────────────────── */
function psi_register_cpt_division(): void {
    $labels = [
        'name'               => esc_html__( 'Bidang Organisasi', 'psi-papeng' ),
        'singular_name'      => esc_html__( 'Bidang', 'psi-papeng' ),
        'menu_name'          => esc_html__( 'Bidang Organisasi', 'psi-papeng' ),
        'add_new_item'       => esc_html__( 'Tambah Bidang', 'psi-papeng' ),
        'edit_item'          => esc_html__( 'Edit Bidang', 'psi-papeng' ),
    ];
    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'          => 'dashicons-networking',
        'menu_position'      => 7,
        'has_archive'        => false,
        'rewrite'            => [ 'slug' => 'bidang' ],
    ];
    register_post_type( 'psi_division', $args );
}
add_action( 'init', 'psi_register_cpt_division' );

/* ── DPD ──────────────────────────────────────────────────── */
function psi_register_cpt_dpd(): void {
    $labels = [
        'name'               => esc_html__( 'DPD PSI', 'psi-papeng' ),
        'singular_name'      => esc_html__( 'DPD', 'psi-papeng' ),
        'menu_name'          => esc_html__( 'Data DPD', 'psi-papeng' ),
        'add_new_item'       => esc_html__( 'Tambah DPD', 'psi-papeng' ),
        'edit_item'          => esc_html__( 'Edit DPD', 'psi-papeng' ),
    ];
    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'          => 'dashicons-location',
        'menu_position'      => 8,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'dpd-psi', 'with_front' => false ],
    ];
    register_post_type( 'psi_dpd', $args );
}
add_action( 'init', 'psi_register_cpt_dpd' );

/* ── Video ────────────────────────────────────────────────── */
function psi_register_cpt_video(): void {
    $labels = [
        'name'               => esc_html__( 'Video', 'psi-papeng' ),
        'singular_name'      => esc_html__( 'Video', 'psi-papeng' ),
        'menu_name'          => esc_html__( 'Video Kegiatan', 'psi-papeng' ),
        'add_new_item'       => esc_html__( 'Tambah Video', 'psi-papeng' ),
        'edit_item'          => esc_html__( 'Edit Video', 'psi-papeng' ),
    ];
    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'          => 'dashicons-video-alt3',
        'menu_position'      => 9,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'video' ],
    ];
    register_post_type( 'psi_video', $args );
}
add_action( 'init', 'psi_register_cpt_video' );

/* ── Gallery ──────────────────────────────────────────────── */
function psi_register_cpt_gallery(): void {
    $labels = [
        'name'               => esc_html__( 'Galeri', 'psi-papeng' ),
        'singular_name'      => esc_html__( 'Galeri', 'psi-papeng' ),
        'menu_name'          => esc_html__( 'Galeri Foto', 'psi-papeng' ),
        'add_new_item'       => esc_html__( 'Tambah Galeri', 'psi-papeng' ),
        'edit_item'          => esc_html__( 'Edit Galeri', 'psi-papeng' ),
    ];
    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post',
        'supports'           => [ 'title', 'editor', 'thumbnail' ],
        'menu_icon'          => 'dashicons-format-image',
        'menu_position'      => 10,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'galeri' ],
    ];
    register_post_type( 'psi_gallery', $args );

    /* Gallery Category Taxonomy */
    register_taxonomy( 'psi_gallery_cat', 'psi_gallery', [
        'labels'       => [
            'name'          => esc_html__( 'Kategori Galeri', 'psi-papeng' ),
            'singular_name' => esc_html__( 'Kategori', 'psi-papeng' ),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => [ 'slug' => 'galeri-kategori' ],
    ] );
}
add_action( 'init', 'psi_register_cpt_gallery' );

/* ── Video Category Taxonomy ──────────────────────────────── */
function psi_register_video_taxonomy(): void {
    register_taxonomy( 'psi_video_cat', 'psi_video', [
        'labels'       => [
            'name'          => esc_html__( 'Kategori Video', 'psi-papeng' ),
            'singular_name' => esc_html__( 'Kategori', 'psi-papeng' ),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => [ 'slug' => 'video-kategori' ],
    ] );
}
add_action( 'init', 'psi_register_video_taxonomy' );

/* ── Flush Rewrite Rules on Activation ────────────────────── */
function psi_papeng_rewrite_flush(): void {
    psi_register_cpt_slider();
    psi_register_cpt_leadership();
    psi_register_cpt_division();
    psi_register_cpt_dpd();
    psi_register_cpt_video();
    psi_register_cpt_gallery();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'psi_papeng_rewrite_flush' );
