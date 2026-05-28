<?php
/**
 * Theme Customizer
 * @package PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

function psi_papeng_customize_register( $wp_customize ): void {

    /* ── Panel: PSI Settings ───────────────────────────────── */
    $wp_customize->add_panel( 'psi_panel', [
        'title'    => esc_html__( 'Pengaturan PSI Papua Pegunungan', 'psi-papeng' ),
        'priority' => 30,
    ] );

    /* ── Section: Welcome / Sambutan ───────────────────────── */
    $wp_customize->add_section( 'psi_welcome', [
        'title'    => esc_html__( 'Sambutan Ketua', 'psi-papeng' ),
        'panel'    => 'psi_panel',
        'priority' => 10,
    ] );
    $wp_customize->add_setting( 'psi_welcome_text', [ 'default' => '', 'sanitize_callback' => 'wp_kses_post' ] );
    $wp_customize->add_control( 'psi_welcome_text', [
        'label'   => esc_html__( 'Teks Sambutan', 'psi-papeng' ),
        'section' => 'psi_welcome',
        'type'    => 'textarea',
    ] );
    $wp_customize->add_setting( 'psi_welcome_image', [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'psi_welcome_image', [
        'label'   => esc_html__( 'Foto Ketua', 'psi-papeng' ),
        'section' => 'psi_welcome',
    ] ) );

    /* ── Section: Contact Info ─────────────────────────────── */
    $wp_customize->add_section( 'psi_contact', [
        'title'    => esc_html__( 'Informasi Kontak', 'psi-papeng' ),
        'panel'    => 'psi_panel',
        'priority' => 20,
    ] );
    $fields = [
        'psi_contact_address'  => [ 'label' => 'Alamat Kantor', 'type' => 'textarea' ],
        'psi_contact_phone'    => [ 'label' => 'Telepon', 'type' => 'text' ],
        'psi_contact_email'    => [ 'label' => 'Email Resmi', 'type' => 'email' ],
        'psi_contact_whatsapp' => [ 'label' => 'WhatsApp (contoh: 6282267218125)', 'type' => 'text' ],
        'psi_contact_map'      => [ 'label' => 'Google Maps Embed URL', 'type' => 'url' ],
    ];
    foreach ( $fields as $id => $cfg ) {
        $wp_customize->add_setting( $id, [ 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $id, [
            'label'   => esc_html( $cfg['label'], 'psi-papeng' ),
            'section' => 'psi_contact',
            'type'    => $cfg['type'],
        ] );
    }

    /* ── Section: Social Media ─────────────────────────────── */
    $wp_customize->add_section( 'psi_social', [
        'title'    => esc_html__( 'Media Sosial', 'psi-papeng' ),
        'panel'    => 'psi_panel',
        'priority' => 30,
    ] );
    $socials = [
        'psi_social_facebook'  => 'Facebook URL',
        'psi_social_instagram' => 'Instagram URL',
        'psi_social_youtube'   => 'YouTube URL',
        'psi_social_twitter'   => 'Twitter/X URL',
        'psi_social_tiktok'    => 'TikTok URL',
    ];
    foreach ( $socials as $id => $label ) {
        $wp_customize->add_setting( $id, [ 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp_customize->add_control( $id, [
            'label'   => esc_html( $label, 'psi-papeng' ),
            'section' => 'psi_social',
            'type'    => 'url',
        ] );
    }

    /* ── Section: Footer ───────────────────────────────────── */
    $wp_customize->add_section( 'psi_footer', [
        'title'    => esc_html__( 'Footer', 'psi-papeng' ),
        'panel'    => 'psi_panel',
        'priority' => 40,
    ] );
    $wp_customize->add_setting( 'psi_footer_text', [ 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field' ] );
    $wp_customize->add_control( 'psi_footer_text', [
        'label'   => esc_html__( 'Teks Footer Tambahan', 'psi-papeng' ),
        'section' => 'psi_footer',
        'type'    => 'textarea',
    ] );
    $wp_customize->add_setting( 'psi_footer_copyright', [ 'default' => '© 2026 DPW PSI Papua Pegunungan. All rights reserved.', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'psi_footer_copyright', [
        'label'   => esc_html__( 'Teks Copyright', 'psi-papeng' ),
        'section' => 'psi_footer',
        'type'    => 'text',
    ] );

    /* ── Section: SEO ──────────────────────────────────────── */
    $wp_customize->add_section( 'psi_seo', [
        'title'    => esc_html__( 'SEO Dasar', 'psi-papeng' ),
        'panel'    => 'psi_panel',
        'priority' => 50,
    ] );
    $wp_customize->add_setting( 'psi_seo_title', [ 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'psi_seo_title', [
        'label'   => esc_html__( 'Site Title Override (kosongkan = default)', 'psi-papeng' ),
        'section' => 'psi_seo',
        'type'    => 'text',
    ] );
    $wp_customize->add_setting( 'psi_seo_description', [ 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'psi_seo_description', [
        'label'   => esc_html__( 'Meta Description Global', 'psi-papeng' ),
        'section' => 'psi_seo',
        'type'    => 'textarea',
    ] );
    $wp_customize->add_setting( 'psi_seo_robots', [ 'default' => 'index, follow', 'sanitize_callback' => 'sanitize_text_field' ] );
    $wp_customize->add_control( 'psi_seo_robots', [
        'label'   => esc_html__( 'Robots Meta', 'psi-papeng' ),
        'section' => 'psi_seo',
        'type'    => 'text',
    ] );

    /* ── Section: Membership ───────────────────────────────── */
    $wp_customize->add_section( 'psi_membership', [
        'title'    => esc_html__( 'Keanggotaan', 'psi-papeng' ),
        'panel'    => 'psi_panel',
        'priority' => 60,
    ] );
    $wp_customize->add_setting( 'psi_member_url', [ 'default' => 'https://psi.id/menjadi-anggota', 'sanitize_callback' => 'esc_url_raw' ] );
    $wp_customize->add_control( 'psi_member_url', [
        'label'   => esc_html__( 'URL Pendaftaran Anggota', 'psi-papeng' ),
        'section' => 'psi_membership',
        'type'    => 'url',
    ] );

    /* ── Document Title Override ───────────────────────────── */
    if ( get_theme_mod( 'psi_seo_title' ) ) {
        add_filter( 'document_title_parts', function( $title ) {
            $title['title'] = esc_html( get_theme_mod( 'psi_seo_title' ) );
            return $title;
        } );
    }
    /* Meta Description */
    add_action( 'wp_head', function() {
        $desc = get_theme_mod( 'psi_seo_description', '' );
        if ( $desc ) {
            echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
        }
        $robots = get_theme_mod( 'psi_seo_robots', 'index, follow' );
        echo '<meta name="robots" content="' . esc_attr( $robots ) . '">' . "\n";
    } );
}
add_action( 'customize_register', 'psi_papeng_customize_register' );
