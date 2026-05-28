<?php
defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Init {

    private static $instance = null;

    public static function get_instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        if ( is_admin() ) {
            new PSI_Papeng_Admin();
        }

        new PSI_Papeng_Ajax();

        add_filter( 'query_vars', [ $this, 'register_query_vars' ] );

        add_action( 'wp_ajax_psi_get_leader', [ $this, 'ajax_get_leader' ] );
        add_action( 'wp_ajax_nopriv_psi_get_leader', [ $this, 'ajax_get_leader' ] );
        add_action( 'wp_ajax_psi_contact_form', [ $this, 'ajax_contact_form' ] );
        add_action( 'wp_ajax_nopriv_psi_contact_form', [ $this, 'ajax_contact_form' ] );
        add_action( 'wp_ajax_psi_member_register', [ $this, 'ajax_member_register' ] );
        add_action( 'wp_ajax_nopriv_psi_member_register', [ $this, 'ajax_member_register' ] );

        add_action( 'init', [ $this, 'register_sitemap_rewrite' ] );
        add_action( 'template_redirect', [ $this, 'sitemap_output' ] );
        add_filter( 'robots_txt', [ $this, 'robots_txt_filter' ], 10, 2 );
    }

    public function register_query_vars( $vars ) {
        $vars[] = 'psi_sitemap';
        return $vars;
    }

    public function ajax_get_leader(): void {
        check_ajax_referer( 'psi_papeng_nonce', 'nonce' );
        $id = absint( $_POST['leader_id'] ?? 0 );
        if ( ! $id ) wp_send_json_error( 'Invalid' );
        $post = get_post( $id );
        if ( ! $post || $post->post_type !== 'psi_leadership' ) wp_send_json_error( 'Not found' );
        wp_send_json_success( [
            'name'      => esc_html( $post->post_title ),
            'position'  => esc_html( get_post_meta( $id, '_psi_lead_position', true ) ),
            'excerpt'   => esc_html( wp_trim_words( $post->post_excerpt ?: $post->post_content, 30 ) ),
            'thumb'     => esc_url( get_the_post_thumbnail_url( $id, 'psi-leader' ) ?: '' ),
            'permalink' => esc_url( get_permalink( $id ) ),
        ] );
    }

    public function ajax_contact_form(): void {
        check_ajax_referer( 'psi_papeng_nonce', 'nonce' );
        $name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
        $email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
        $phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
        $subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
        $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
        if ( empty( $name ) || empty( $email ) || empty( $subject ) || empty( $message ) ) wp_send_json_error( 'Semua field wajib diisi.' );
        if ( ! is_email( $email ) ) wp_send_json_error( 'Email tidak valid.' );
        $to = get_theme_mod( 'psi_contact_email', get_option( 'admin_email' ) );
        if ( empty( $to ) ) $to = get_option( 'admin_email' );
        $body = "Nama: " . $name . "\nEmail: " . $email . "\nTelepon: " . $phone . "\nSubjek: " . $subject . "\n\nPesan:\n" . $message;
        if ( wp_mail( $to, '[Kontak] ' . $subject, $body ) ) {
            PSI_Papeng_Activator::log( 'contact_sent', 'Dari ' . $name );
            wp_send_json_success( 'Pesan berhasil dikirim.' );
        } else {
            wp_send_json_error( 'Gagal mengirim pesan.' );
        }
    }

    public function ajax_member_register(): void {
        check_ajax_referer( 'psi_papeng_nonce', 'nonce' );
        global $wpdb;
        $table = $wpdb->prefix . 'psi_members';
        $data = [
            'full_name'  => sanitize_text_field( wp_unslash( $_POST['full_name'] ?? '' ) ),
            'email'      => sanitize_email( wp_unslash( $_POST['email'] ?? '' ) ),
            'phone'      => sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) ),
            'kabupaten'  => sanitize_text_field( wp_unslash( $_POST['kabupaten'] ?? '' ) ),
            'nik'        => sanitize_text_field( wp_unslash( $_POST['nik'] ?? '' ) ),
            'birth_date' => sanitize_text_field( wp_unslash( $_POST['birth_date'] ?? '' ) ),
            'gender'     => sanitize_key( $_POST['gender'] ?? '' ),
            'occupation' => sanitize_text_field( wp_unslash( $_POST['occupation'] ?? '' ) ),
            'address'    => sanitize_textarea_field( wp_unslash( $_POST['address'] ?? '' ) ),
        ];
        if ( empty( $data['full_name'] ) || empty( $data['email'] ) || empty( $data['phone'] ) || empty( $data['kabupaten'] ) ) wp_send_json_error( 'Field wajib belum lengkap.' );
        if ( ! is_email( $data['email'] ) ) wp_send_json_error( 'Email tidak valid.' );
        $exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table WHERE email = %s", $data['email'] ) );
        if ( $exists ) wp_send_json_error( 'Email sudah terdaftar.' );
        $data['status'] = 'pending';
        $data['verification_token'] = wp_generate_password( 32, false );
        $data['registered_at'] = current_time( 'mysql' );
        if ( empty( $data['birth_date'] ) ) $data['birth_date'] = null;
        if ( ! $wpdb->insert( $table, $data ) ) wp_send_json_error( 'Gagal mendaftar.' );
        PSI_Papeng_Activator::log( 'member_registered', $data['full_name'] );
        wp_send_json_success( 'Pendaftaran berhasil!' );
    }

    public function register_sitemap_rewrite(): void {
        add_rewrite_rule( 'sitemap\.xml$', 'index.php?psi_sitemap=1', 'top' );
    }

    public function sitemap_output(): void {
        if ( ! get_query_var( 'psi_sitemap' ) ) return;
        header( 'Content-Type: application/xml; charset=utf-8' );
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        echo '<url><loc>' . esc_url( home_url( '/' ) ) . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>' . "\n";
        $pts = get_posts( [ 'post_type' => 'page', 'posts_per_page' => 50, 'post_status' => 'publish' ] );
        foreach ( $pts as $p ) echo '<url><loc>' . esc_url( get_permalink( $p ) ) . '</loc><lastmod>' . esc_html( $p->post_modified ) . '</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>' . "\n";
        $ps = get_posts( [ 'post_type' => 'post', 'posts_per_page' => 100, 'post_status' => 'publish' ] );
        foreach ( $ps as $p ) echo '<url><loc>' . esc_url( get_permalink( $p ) ) . '</loc><lastmod>' . esc_html( $p->post_modified ) . '</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>' . "\n";
        echo '</urlset>';
        exit;
    }

    public function robots_txt_filter( $output, $public ) {
        if ( $public ) $output .= "\nSitemap: " . esc_url( home_url( '/sitemap.xml' ) ) . "\n";
        return $output;
    }
}
