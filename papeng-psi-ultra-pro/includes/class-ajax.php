<?php
defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Ajax {

    public function __construct() {
        add_action( 'wp_ajax_psi_export_members', [ $this, 'export_members' ] );
        add_action( 'wp_ajax_psi_clear_logs', [ $this, 'clear_logs' ] );
        add_action( 'phpmailer_init', [ $this, 'smtp_config' ], 999 );
    }

    public function export_members(): void {
        check_ajax_referer( 'psi_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );
        global $wpdb;
        $members = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}psi_members ORDER BY registered_at DESC" );
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=psi-anggota-' . date( 'Y-m-d' ) . '.csv' );
        header( 'Pragma: no-cache' );
        $output = fopen( 'php://output', 'w' );
        fprintf( $output, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );
        fputcsv( $output, [ 'ID', 'Nama', 'Email', 'Telepon', 'Kabupaten', 'NIK', 'Status', 'Terdaftar' ] );
        foreach ( $members as $m ) fputcsv( $output, [ $m->id, $m->full_name, $m->email, $m->phone, $m->kabupaten, $m->nik, $m->status, $m->registered_at ] );
        fclose( $output );
        exit;
    }

    public function clear_logs(): void {
        check_ajax_referer( 'psi_admin_nonce', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );
        global $wpdb;
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}psi_activity_logs" );
        wp_safe_redirect( admin_url( 'admin.php?page=psi-logs' ) );
        exit;
    }

    public function smtp_config( $phpmailer ): void {
        $host = get_option( 'psi_smtp_host', '' );
        if ( empty( $host ) ) return;
        $phpmailer->isSMTP();
        $phpmailer->Host = $host;
        $phpmailer->Port = absint( get_option( 'psi_smtp_port', 587 ) );
        $phpmailer->Username = get_option( 'psi_smtp_user', '' );
        $phpmailer->Password = get_option( 'psi_smtp_pass', '' );
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = get_option( 'psi_smtp_encryption', 'tls' );
        $from = get_option( 'psi_smtp_from', '' );
        if ( $from ) {
            $phpmailer->From = $from;
            $phpmailer->FromName = get_option( 'psi_smtp_from_name', get_bloginfo( 'name' ) );
        }
    }
}
