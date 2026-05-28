<?php
/**
 * Plugin Initializer — FIXED
 * @package PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Init {

    private static ?self $instance = null;

    public static function get_instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        /* Admin */
        if ( is_admin() ) {
            new PSI_Papeng_Admin();
        }

        /* AJAX Handlers */
        new PSI_Papeng_Ajax();

        /* Register sitemap query var */
        add_filter( 'query_vars', [ $this, 'register_query_vars' ] );

        /* Frontend: Leader data endpoint */
        add_action( 'wp_ajax_psi_get_leader', [ $this, 'ajax_get_leader' ] );
        add_action( 'wp_ajax_nopriv_psi_get_leader', [ $this, 'ajax_get_leader' ] );

        /* Frontend: Contact form handler */
        add_action( 'wp_ajax_psi_contact_form', [ $this, 'ajax_contact_form' ] );
        add_action( 'wp_ajax_nopriv_psi_contact_form', [ $this, 'ajax_contact_form' ] );

        /* Frontend: Member registration */
        add_action( 'wp_ajax_psi_member_register', [ $this, 'ajax_member_register' ] );
        add_action( 'wp_ajax_nopriv_psi_member_register', [ $this, 'ajax_member_register' ] );

        /* XML Sitemap rewrite + output */
        add_action( 'init', [ $this, 'register_sitemap_rewrite' ] );
        add_action( 'template_redirect', [ $this, 'sitemap_output' ] );

        /* Robots.txt — add sitemap reference */
        add_filter( 'robots_txt', [ $this, 'robots_txt_filter' ], 10, 2 );
    }

    /* ── Register Query Vars ───────────────────────────────── */
    public function register_query_vars( array $vars ): array {
        $vars[] = 'psi_sitemap';
        return $vars;
    }

    /* ── AJAX: Get Leader Data ─────────────────────────────── */
    public function ajax_get_leader(): void {
        check_ajax_referer( 'psi_papeng_nonce', 'nonce' );
        $id = absint( $_POST['leader_id'] ?? 0 );
        if ( ! $id ) wp_send_json_error( 'Invalid ID' );

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

    /* ── AJAX: Contact Form ────────────────────────────────── */
    public function ajax_contact_form(): void {
        /* FIX: Nonce action harus cocok dengan wp_create_nonce di functions.php */
        check_ajax_referer( 'psi_papeng_nonce', 'nonce' );

        $name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
        $email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
        $phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
        $subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
        $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

        if ( empty( $name ) || empty( $email ) || empty( $subject ) || empty( $message ) ) {
            wp_send_json_error( 'Semua field wajib harus diisi.' );
        }
        if ( ! is_email( $email ) ) {
            wp_send_json_error( 'Format email tidak valid.' );
        }

        $to = get_theme_mod( 'psi_contact_email', get_option( 'admin_email' ) );
        if ( empty( $to ) ) {
            $to = get_option( 'admin_email' );
        }

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $name . ' <' . $email . '>',
            'Reply-To: ' . $email,
        ];
        $body  = "<h2 style=\"margin:0 0 16px;\">Pesan dari Website PSI Papua Pegunungan</h2>";
        $body .= "<table style=\"border-collapse:collapse;font-family:sans-serif;\">";
        $body .= "<tr><td style=\"padding:8px 16px 8px 0;font-weight:bold;\">Nama</td><td style=\"padding:8px 0;\">" . esc_html( $name ) . "</td></tr>";
        $body .= "<tr><td style=\"padding:8px 16px 8px 0;font-weight:bold;\">Email</td><td style=\"padding:8px 0;\">" . esc_html( $email ) . "</td></tr>";
        if ( $phone ) {
            $body .= "<tr><td style=\"padding:8px 16px 8px 0;font-weight:bold;\">Telepon</td><td style=\"padding:8px 0;\">" . esc_html( $phone ) . "</td></tr>";
        }
        $body .= "<tr><td style=\"padding:8px 16px 8px 0;font-weight:bold;\">Subjek</td><td style=\"padding:8px 0;\">" . esc_html( $subject ) . "</td></tr>";
        $body .= "<tr><td style=\"padding:8px 16px 8px 0;font-weight:bold;vertical-align:top;\">Pesan</td><td style=\"padding:8px 0;\">" . nl2br( esc_html( $message ) ) . "</td></tr>";
        $body .= "</table>";

        $sent = wp_mail( $to, '[Kontak Website] ' . $subject, $body, $headers );

        if ( $sent ) {
            PSI_Papeng_Activator::log( 'contact_sent', 'Pesan kontak dari ' . $name . ' (' . $email . ')' );
            wp_send_json_success( 'Pesan Anda berhasil dikirim. Terima kasih!' );
        } else {
            wp_send_json_error( 'Gagal mengirim pesan. Silakan coba lagi atau hubungi kami via WhatsApp.' );
        }
    }

    /* ── AJAX: Member Registration ─────────────────────────── */
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

        if ( empty( $data['full_name'] ) || empty( $data['email'] ) || empty( $data['phone'] ) || empty( $data['kabupaten'] ) ) {
            wp_send_json_error( 'Nama, Email, Telepon, dan Kabupaten wajib diisi.' );
        }
        if ( ! is_email( $data['email'] ) ) {
            wp_send_json_error( 'Format email tidak valid.' );
        }

        $exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table WHERE email = %s", $data['email'] ) );
        if ( $exists ) {
            wp_send_json_error( 'Email sudah terdaftar.' );
        }

        /* FIX: Use NULL for empty date to avoid strict SQL error */
        $data['status']             = 'pending';
        $data['verification_token'] = wp_generate_password( 32, false );
        $data['registered_at']      = current_time( 'mysql' );
        if ( empty( $data['birth_date'] ) ) {
            $data['birth_date'] = null;
        }

        $inserted = $wpdb->insert( $table, $data );
        if ( ! $inserted ) {
            wp_send_json_error( 'Gagal mendaftar. Silakan coba lagi.' );
        }

        $member_id = $wpdb->insert_id;

        /* Send verification email to member */
        $to      = $data['email'];
        $subject = 'Pendaftaran Anggota PSI Papua Pegunungan';
        $body    = '<div style="font-family:sans-serif;max-width:600px;margin:0 auto;">';
        $body   .= '<div style="background:#D6001C;color:white;padding:24px;border-radius:12px 12px 0 0;text-align:center;">';
        $body   .= '<h1 style="margin:0;font-size:20px;">PSI Papua Pegunungan</h1>';
        $body   .= '</div>';
        $body   .= '<div style="padding:24px;background:white;border:1px solid #e5e7eb;border-top:none;border-radius:0 0 12px 12px;">';
        $body   .= '<h2 style="margin:0 0 12px;color:#111;">Terima kasih telah mendaftar!</h2>';
        $body   .= '<p style="color:#4B5563;line-height:1.6;">Pendaftaran Anda sebagai calon anggota PSI Papua Pegunungan telah kami terima.</p>';
        $body   .= '<p style="color:#4B5563;line-height:1.6;">Status saat ini: <strong style="color:#D6001C;">Menunggu Verifikasi</strong></p>';
        $body   .= '<p style="color:#4B5563;line-height:1.6;">Anda akan diberitahu setelah pendaftaran diverifikasi oleh admin.</p>';
        $body   .= '<hr style="border:none;border-top:1px solid #e5e7eb;margin:16px 0;">';
        $body   .= '<p style="color:#9CA3AF;font-size:13px;margin:0;">Hormat kami,<br>DPW PSI Papua Pegunungan</p>';
        $body   .= '</div></div>';

        wp_mail( $to, $subject, $body, [ 'Content-Type: text/html; charset=UTF-8' ] );

        /* Notify admin */
        $admin_to = get_option( 'admin_email' );
        $admin_body = "Anggota baru mendaftar:\n\n";
        $admin_body .= "Nama: {$data['full_name']}\n";
        $admin_body .= "Email: {$data['email']}\n";
        $admin_body .= "Telepon: {$data['phone']}\n";
        $admin_body .= "Kabupaten: {$data['kabupaten']}\n\n";
        $admin_body .= "Verifikasi di: " . admin_url( 'admin.php?page=psi-members' );
        wp_mail( $admin_to, '[Pendaftaran Baru] ' . $data['full_name'], $admin_body );

        PSI_Papeng_Activator::log( 'member_registered', 'Pendaftaran anggota baru: ' . $data['full_name'] );

        wp_send_json_success( 'Pendaftaran berhasil! Tunggu verifikasi dari admin.' );
    }

    /* ── XML Sitemap ───────────────────────────────────────── */
    public function register_sitemap_rewrite(): void {
        add_rewrite_rule( 'sitemap\.xml$', 'index.php?psi_sitemap=1', 'top' );
    }

    public function sitemap_output(): void {
        if ( ! get_query_var( 'psi_sitemap' ) ) return;

        header( 'Content-Type: application/xml; charset=utf-8' );
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $this->sitemap_url( home_url( '/' ), 'daily', '1.0' );

        $pages = get_posts( [ 'post_type' => 'page', 'posts_per_page' => 50, 'post_status' => 'publish' ] );
        foreach ( $pages as $p ) {
            $this->sitemap_url( get_permalink( $p->ID ), 'weekly', '0.8', $p->post_modified );
        }

        $posts = get_posts( [ 'post_type' => 'post', 'posts_per_page' => 100, 'post_status' => 'publish' ] );
        foreach ( $posts as $p ) {
            $this->sitemap_url( get_permalink( $p->ID ), 'monthly', '0.6', $p->post_modified );
        }

        $cpts = [ 'psi_dpd', 'psi_video', 'psi_gallery', 'psi_leadership', 'psi_division' ];
        foreach ( $cpts as $cpt ) {
            $items = get_posts( [ 'post_type' => $cpt, 'posts_per_page' => 100, 'post_status' => 'publish' ] );
            foreach ( $items as $p ) {
                $this->sitemap_url( get_permalink( $p->ID ), 'monthly', '0.5', $p->post_modified );
            }
        }

        echo '</urlset>';
        exit;
    }

    private function sitemap_url( string $loc, string $freq, string $prio, string $mod = '' ): void {
        echo '<url><loc>' . esc_url( $loc ) . '</loc>';
        if ( $mod ) echo '<lastmod>' . esc_html( $mod ) . '</lastmod>';
        echo '<changefreq>' . esc_html( $freq ) . '</changefreq><priority>' . esc_html( $prio ) . '</priority></url>' . "\n";
    }

    /* ── Robots.txt Filter ─────────────────────────────────── */
    public function robots_txt_filter( string $output, bool $public ): string {
        if ( $public ) {
            $output .= "\nSitemap: " . esc_url( home_url( '/sitemap.xml' ) ) . "\n";
        }
        return $output;
    }
}
