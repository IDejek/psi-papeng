<?php
defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Activator {

    public static function activate(): void {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $sql1 = "CREATE TABLE {$wpdb->prefix}psi_members (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            full_name varchar(200) NOT NULL,
            email varchar(200) NOT NULL,
            phone varchar(30) DEFAULT '',
            kabupaten varchar(200) DEFAULT '',
            address text DEFAULT NULL,
            nik varchar(50) DEFAULT '',
            birth_date date DEFAULT NULL,
            gender varchar(20) DEFAULT '',
            occupation varchar(200) DEFAULT '',
            photo varchar(500) DEFAULT '',
            status varchar(20) NOT NULL DEFAULT 'pending',
            verification_token varchar(100) DEFAULT '',
            registered_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            verified_at datetime DEFAULT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY email (email),
            KEY status (status),
            KEY kabupaten (kabupaten)
        ) $charset;";

        $sql2 = "CREATE TABLE {$wpdb->prefix}psi_activity_logs (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED DEFAULT 0,
            action varchar(100) NOT NULL,
            description text DEFAULT NULL,
            ip_address varchar(45) DEFAULT '',
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY action (action)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql1 );
        dbDelta( $sql2 );

        add_option( 'psi_plugin_version', PSI_PLUGIN_VERSION );
        add_option( 'psi_member_redirect_url', 'https://psi.id/menjadi-anggota' );

        self::log( 'plugin_activated', 'Plugin PSI Papeng Premium diaktifkan' );
        flush_rewrite_rules();
    }

    public static function deactivate(): void {
        flush_rewrite_rules();
    }

    public static function log( $action, $description = '', $user_id = 0 ): void {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'psi_activity_logs',
            [
                'user_id'     => $user_id ? (int) $user_id : get_current_user_id(),
                'action'      => sanitize_key( $action ),
                'description' => sanitize_text_field( $description ),
                'ip_address'  => sanitize_text_field( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '' ),
            ],
            [ '%d', '%s', '%s', '%s' ]
        );
    }
}
