<?php
/**
 * AJAX Handlers for Admin Operations
 * @package PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Ajax {

    public function __construct() {
        /* Export Members CSV */
        add_action( 'wp_ajax_psi_export_members', [ $this, 'export_members' ] );
        /* Clear Logs */
        add_action( 'wp_ajax_psi_clear_logs', [ $this, 'clear_logs' ] );
        /* SMTP Configuration */
        add_action( 'phpmailer_init', [ $this, 'smtp_config' ], 999 );
    }

    /* ── Export Members to CSV ─────────────────────────────── */
    public function export_members(): void {
        check_ajax_referer( 'psi_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

        global $wpdb;
        $table   = $wpdb->prefix . 'psi_members';
        $members = $wpdb->get_results( "SELECT * FROM $table ORDER BY registered_at DESC" );

        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=psi-anggota-' . date( 'Y-m-d' ) . '.csv' );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );

        $output = fopen( 'php://output', 'w' );
        /* BOM for Excel UTF-8 */
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

        /* Header */
        fputcsv( $output, [ 'ID', 'Nama Lengkap', 'Email', 'Telepon', 'Kabupaten', 'NIK', 'Tanggal Lahir', 'Jenis Kelamin', 'Pekerjaan', 'Alamat', 'Status', 'Terdaftar', 'Diverifikasi' ] );

        /* Rows */
        foreach ( $members as $m ) {
            fputcsv( $output, [
                $m->id,
                $m->full_name,
                $m->email,
                $m->phone,
                $m->kabupaten,
                $m->nik,
                $m->birth_date,
                $m->gender,
                $m->occupation,
                $m->address,
                $m->status,
                $m->registered_at,
                $m->verified_at,
            ] );
        }
        fclose( $output );
        exit;
    }

    /* ── Clear Activity Logs ───────────────────────────────── */
    public function clear_logs(): void {
        check_ajax_referer( 'psi_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );

        global $wpdb;
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}psi_activity_logs" );
        PSI_Papeng_Activator::log( 'logs_cleared', 'Semua log aktivitas dihapus' );
        wp_safe_redirect( admin_url( 'admin.php?page=psi-logs' ) );
        exit;
    }

    /* ── SMTP Configuration ────────────────────────────────── */
    public function smtp_config( PHPMailer\PHPMailer $phpmailer ): void {
        $host = get_option( 'psi_smtp_host', '' );
        if ( empty( $host ) ) return;

        $phpmailer->isSMTP();
        $phpmailer->Host       = $host;
        $phpmailer->Port       = absint( get_option( 'psi_smtp_port', 587 ) );
        $phpmailer->Username   = get_option( 'psi_smtp_user', '' );
        $phpmailer->Password   = get_option( 'psi_smtp_pass', '' );
        $phpmailer->SMTPAuth   = true;
        $phpmailer->SMTPSecure = get_option( 'psi_smtp_encryption', 'tls' );

        $from = get_option( 'psi_smtp_from', '' );
        if ( $from ) {
            $phpmailer->From = $from;
            $phpmailer->FromName = get_option( 'psi_smtp_from_name', get_bloginfo( 'name' ) );
        }
    }
}
