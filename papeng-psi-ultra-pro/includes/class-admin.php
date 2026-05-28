<?php
/**
 * Admin Dashboard
 * @package PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menus' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_filter( 'set-screen-option', [ $this, 'set_screen_option' ], 10, 3 );
    }

    /* ── Register Admin Menus ──────────────────────────────── */
    public function register_menus(): void {

        /* Main Dashboard */
        $hook = add_menu_page(
            esc_html__( 'PSI Dashboard', 'psi-papeng' ),
            esc_html__( 'PSI Premium', 'psi-papeng' ),
            'manage_options',
            'psi-dashboard',
            [ $this, 'page_dashboard' ],
            'dashicons-shield-alt',
            2
        );

        /* Members */
        add_submenu_page( 'psi-dashboard', esc_html__( 'Data Anggota', 'psi-papeng' ), esc_html__( 'Data Anggota', 'psi-papeng' ), 'manage_options', 'psi-members', [ $this, 'page_members' ] );

        /* Add Member */
        add_submenu_page( 'psi-dashboard', esc_html__( 'Tambah Anggota', 'psi-papeng' ), esc_html__( 'Tambah Anggota', 'psi-papeng' ), 'manage_options', 'psi-add-member', [ $this, 'page_add_member' ] );

        /* Member Statistics */
        add_submenu_page( 'psi-dashboard', esc_html__( 'Statistik Anggota', 'psi-papeng' ), esc_html__( 'Statistik Anggota', 'psi-papeng' ), 'manage_options', 'psi-stats', [ $this, 'page_stats' ] );

        /* Activity Logs */
        add_submenu_page( 'psi-dashboard', esc_html__( 'Log Aktivitas', 'psi-papeng' ), esc_html__( 'Log Aktivitas', 'psi-papeng' ), 'manage_options', 'psi-logs', [ $this, 'page_logs' ] );

        /* Settings */
        add_submenu_page( 'psi-dashboard', esc_html__( 'Pengaturan', 'psi-papeng' ), esc_html__( 'Pengaturan', 'psi-papeng' ), 'manage_options', 'psi-settings', [ $this, 'page_settings' ] );

        add_action( "load-$hook", [ $this, 'dashboard_screen_option' ] );
    }

    /* ── Enqueue Admin Assets ──────────────────────────────── */
    public function enqueue_assets( $hook ): void {
        if ( strpos( $hook, 'psi-' ) === false ) return;

        wp_enqueue_style( 'psi-admin-dashboard', PSI_PLUGIN_URI . 'assets/css/admin-dashboard.css', [], PSI_PLUGIN_VERSION );
        wp_enqueue_script( 'psi-admin-dashboard', PSI_PLUGIN_URI . 'assets/js/admin-dashboard.js', [ 'jquery' ], PSI_PLUGIN_VERSION, true );
        wp_localize_script( 'psi-admin-dashboard', 'psiAdmin', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'psi_admin_nonce' ),
            'i18n'    => [
                'confirm_delete'  => esc_html__( 'Yakin ingin menghapus anggota ini?', 'psi-papeng' ),
                'confirm_verify'  => esc_html__( 'Yakin ingin memverifikasi anggota ini?', 'psi-papeng' ),
                'success'         => esc_html__( 'Berhasil!', 'psi-papeng' ),
                'error'           => esc_html__( 'Terjadi kesalahan.', 'psi-papeng' ),
            ],
        ] );
    }

    /* ── Screen Option ─────────────────────────────────────── */
    public function dashboard_screen_option(): void {
        $option = 'per_page';
        $args   = [
            'label'   => esc_html__( 'Jumlah per halaman', 'psi-papeng' ),
            'default' => 20,
            'option'  => 'psi_members_per_page',
        ];
        add_screen_option( $option, $args );
    }

    public function set_screen_option( $status, $option, $value ) {
        if ( $option === 'psi_members_per_page' ) return intval( $value );
        return $status;
    }

    /* ═══════════════════════════════════════════════════════
       PAGE: DASHBOARD
       ═══════════════════════════════════════════════════════ */
    public function page_dashboard(): void {
        global $wpdb;
        $members_table = $wpdb->prefix . 'psi_members';
        $logs_table    = $wpdb->prefix . 'psi_activity_logs';

        $total_members    = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $members_table" );
        $pending_members  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $members_table WHERE status = 'pending'" );
        $verified_members = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $members_table WHERE status = 'verified'" );
        $total_logs       = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $logs_table" );
        $total_posts      = wp_count_posts()->publish ?? 0;
        $total_dpd        = wp_count_posts( 'psi_dpd' )->publish ?? 0;
        $total_videos     = wp_count_posts( 'psi_video' )->publish ?? 0;

        /* Kabupaten stats */
        $kab_stats = $wpdb->get_results( "SELECT kabupaten, COUNT(*) as cnt FROM $members_table GROUP BY kabupaten ORDER BY cnt DESC LIMIT 8" );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header">
                <div class="psi-admin-brand">
                    <div class="psi-admin-logo">
                        <span class="psi-logo-text">PSI</span>
                        <span class="psi-logo-sub">Premium</span>
                    </div>
                    <div>
                        <h1><?php esc_html_e( 'Dashboard PSI Papua Pegunungan', 'psi-papeng' ); ?></h1>
                        <p class="text-sm text-gray-400"><?php esc_html_e( 'Panel administrasi premium', 'psi-papeng' ); ?></p>
                    </div>
                </div>
                <div class="psi-admin-version">v<?php echo esc_html( PSI_PLUGIN_VERSION ); ?></div>
            </div>

            <!-- Stats Cards -->
            <div class="psi-stats-grid">
                <div class="psi-stat-card psi-stat-red">
                    <div class="psi-stat-icon"><i class="fas fa-users"></i></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $total_members ) ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Total Anggota', 'psi-papeng' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-yellow">
                    <div class="psi-stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $pending_members ) ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Menunggu Verifikasi', 'psi-papeng' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-green">
                    <div class="psi-stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $verified_members ) ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Terverifikasi', 'psi-papeng' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-blue">
                    <div class="psi-stat-icon"><i class="fas fa-newspaper"></i></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $total_posts ) ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Berita', 'psi-papeng' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-purple">
                    <div class="psi-stat-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $total_dpd ) ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'DPD', 'psi-papeng' ); ?></span>
                    </div>
                </div>
                <div class="psi-stat-card psi-stat-gray">
                    <div class="psi-stat-icon"><i class="fas fa-video"></i></div>
                    <div class="psi-stat-info">
                        <span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $total_videos ) ); ?></span>
                        <span class="psi-stat-label"><?php esc_html_e( 'Video', 'psi-papeng' ); ?></span>
                    </div>
                </div>
            </div>

            <!-- Kabupaten Stats -->
            <?php if ( $kab_stats ) : ?>
            <div class="psi-admin-card mt-6">
                <div class="psi-card-header">
                    <h2><i class="fas fa-chart-bar mr-2"></i><?php esc_html_e( 'Statistik per Kabupaten', 'psi-papeng' ); ?></h2>
                </div>
                <div class="psi-card-body">
                    <div class="psi-chart-bars">
                        <?php
                        $max_cnt = max( array_column( $kab_stats, 'cnt' ) ) ?: 1;
                        $colors  = [ '#D6001C', '#D4AF37', '#2563EB', '#059669', '#7C3AED', '#DB2777', '#EA580C', '#4B5563' ];
                        $ci = 0;
                        foreach ( $kab_stats as $ks ) :
                            $pct = round( ( $ks->cnt / $max_cnt ) * 100 );
                            $color = $colors[ $ci % count( $colors ) ];
                            $ci++;
                        ?>
                        <div class="psi-chart-row">
                            <span class="psi-chart-label"><?php echo esc_html( $ks->kabupaten ?: 'Tidak ditentukan' ); ?></span>
                            <div class="psi-chart-bar-track">
                                <div class="psi-chart-bar-fill" style="width:<?php echo esc_attr( $pct ); ?>%;background:<?php echo esc_attr( $color ); ?>;"></div>
                            </div>
                            <span class="psi-chart-value"><?php echo esc_html( $ks->cnt ); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Recent Activity -->
            <?php
            $recent_logs = $wpdb->get_results( "SELECT * FROM $logs_table ORDER BY created_at DESC LIMIT 10" );
            if ( $recent_logs ) :
            ?>
            <div class="psi-admin-card mt-6">
                <div class="psi-card-header">
                    <h2><i class="fas fa-history mr-2"></i><?php esc_html_e( 'Aktivitas Terbaru', 'psi-papeng' ); ?></h2>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-logs' ) ); ?>" class="psi-btn-sm"><?php esc_html_e( 'Lihat Semua', 'psi-papeng' ); ?></a>
                </div>
                <div class="psi-card-body p-0">
                    <table class="psi-table">
                        <thead><tr><th><?php esc_html_e( 'Aksi', 'psi-papeng' ); ?></th><th><?php esc_html_e( 'Deskripsi', 'psi-papeng' ); ?></th><th><?php esc_html_e( 'IP', 'psi-papeng' ); ?></th><th><?php esc_html_e( 'Waktu', 'psi-papeng' ); ?></th></tr></thead>
                        <tbody>
                            <?php foreach ( $recent_logs as $log ) : ?>
                            <tr>
                                <td><span class="psi-badge"><?php echo esc_html( $log->action ); ?></span></td>
                                <td><?php echo esc_html( $log->description ); ?></td>
                                <td class="text-xs text-gray-400"><?php echo esc_html( $log->ip_address ); ?></td>
                                <td class="text-xs text-gray-400"><?php echo esc_html( $log->created_at ); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════════
       PAGE: MEMBERS LIST
       ═══════════════════════════════════════════════════════ */
    public function page_members(): void {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_members';

        /* Handle actions */
        if ( isset( $_GET['action'] ) && isset( $_GET['member_id'] ) && wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'psi_member_action' ) ) {
            $id     = absint( $_GET['member_id'] );
            $action = sanitize_key( $_GET['action'] );

            if ( $action === 'verify' ) {
                $wpdb->update( $table, [ 'status' => 'verified', 'verified_at' => current_time( 'mysql' ) ], [ 'id' => $id ] );
                PSI_Papeng_Activator::log( 'member_verified', 'Anggota ID ' . $id . ' diverifikasi' );

                /* Send email */
                $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
                if ( $member ) {
                    wp_mail( $member->email, 'Pendaftaran Diverifikasi - PSI Papua Pegunungan', '<p>Selamat! Pendaftaran Anda sebagai anggota PSI Papua Pegunungan telah diverifikasi.</p><p>Selamat bergabung!</p>', [ 'Content-Type: text/html; charset=UTF-8' ] );
                }
                wp_safe_redirect( admin_url( 'admin.php?page=psi-members&msg=verified' ) );
                exit;
            }
            if ( $action === 'reject' ) {
                $wpdb->update( $table, [ 'status' => 'rejected' ], [ 'id' => $id ] );
                PSI_Papeng_Activator::log( 'member_rejected', 'Anggota ID ' . $id . ' ditolak' );
                wp_safe_redirect( admin_url( 'admin.php?page=psi-members&msg=rejected' ) );
                exit;
            }
            if ( $action === 'delete' ) {
                $wpdb->delete( $table, [ 'id' => $id ] );
                PSI_Papeng_Activator::log( 'member_deleted', 'Anggota ID ' . $id . ' dihapus' );
                wp_safe_redirect( admin_url( 'admin.php?page=psi-members&msg=deleted' ) );
                exit;
            }
        }

        /* Search & Filter */
        $where  = '1=1';
        $search = sanitize_text_field( wp_unslash( $_GET['s'] ?? '' ) );
        $filter = sanitize_key( $_GET['filter'] ?? '' );
        if ( $search ) {
            $like = '%' . $wpdb->esc_like( $search ) . '%';
            $where .= $wpdb->prepare( " AND (full_name LIKE %s OR email LIKE %s OR phone LIKE %s OR kabupaten LIKE %s)", $like, $like, $like, $like );
        }
        if ( $filter ) {
            $where .= $wpdb->prepare( " AND status = %s", $filter );
        }

        $per_page = get_user_meta( get_current_user_id(), 'psi_members_per_page', true ) ?: 20;
        $paged    = max( 1, intval( $_GET['paged'] ?? 1 ) );
        $offset   = ( $paged - 1 ) * $per_page;

        $total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE $where" );
        $members = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE $where ORDER BY registered_at DESC LIMIT %d OFFSET %d", $per_page, $offset ) );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header">
                <h1><?php esc_html_e( 'Data Anggota', 'psi-papeng' ); ?></h1>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-add-member' ) ); ?>" class="psi-btn psi-btn-red"><i class="fas fa-plus mr-2"></i><?php esc_html_e( 'Tambah Anggota', 'psi-papeng' ); ?></a>
            </div>

            <?php if ( isset( $_GET['msg'] ) ) : ?>
            <div class="psi-notice psi-notice-success">
                <?php
                $msgs = [
                    'verified' => esc_html__( 'Anggota berhasil diverifikasi.', 'psi-papeng' ),
                    'rejected' => esc_html__( 'Anggota ditolak.', 'psi-papeng' ),
                    'deleted'  => esc_html__( 'Anggota berhasil dihapus.', 'psi-papeng' ),
                    'added'    => esc_html__( 'Anggota berhasil ditambahkan.', 'psi-papeng' ),
                ];
                echo esc_html( $msgs[ sanitize_key( $_GET['msg'] ) ] ?? '' );
                ?>
            </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="psi-admin-card">
                <div class="psi-card-header">
                    <form method="get" class="flex items-center gap-3 flex-wrap">
                        <input type="hidden" name="page" value="psi-members">
                        <div class="relative">
                            <input type="text" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Cari nama, email, telepon...', 'psi-papeng' ); ?>" class="psi-input" style="min-width:280px;">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        </div>
                        <select name="filter" class="psi-select">
                            <option value=""><?php esc_html_e( 'Semua Status', 'psi-papeng' ); ?></option>
                            <option value="pending" <?php selected( $filter, 'pending' ); ?>><?php esc_html_e( 'Menunggu', 'psi-papeng' ); ?></option>
                            <option value="verified" <?php selected( $filter, 'verified' ); ?>><?php esc_html_e( 'Terverifikasi', 'psi-papeng' ); ?></option>
                            <option value="rejected" <?php selected( $filter, 'rejected' ); ?>><?php esc_html_e( 'Ditolak', 'psi-papeng' ); ?></option>
                        </select>
                        <button type="submit" class="psi-btn psi-btn-gray"><?php esc_html_e( 'Filter', 'psi-papeng' ); ?></button>
                        <?php if ( $search || $filter ) : ?>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-members' ) ); ?>" class="psi-btn psi-btn-ghost"><?php esc_html_e( 'Reset', 'psi-papeng' ); ?></a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="psi-card-body p-0">
                    <?php if ( $members ) : ?>
                    <div class="psi-table-responsive">
                        <table class="psi-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php esc_html_e( 'Nama', 'psi-papeng' ); ?></th>
                                    <th><?php esc_html_e( 'Email', 'psi-papeng' ); ?></th>
                                    <th><?php esc_html_e( 'Telepon', 'psi-papeng' ); ?></th>
                                    <th><?php esc_html_e( 'Kabupaten', 'psi-papeng' ); ?></th>
                                    <th><?php esc_html_e( 'Status', 'psi-papeng' ); ?></th>
                                    <th><?php esc_html_e( 'Terdaftar', 'psi-papeng' ); ?></th>
                                    <th><?php esc_html_e( 'Aksi', 'psi-papeng' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $members as $i => $m ) :
                                    $status_class = 'psi-status-' . $m->status;
                                    $status_label = [ 'pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak' ][ $m->status ] ?? $m->status;
                                    $verify_url = wp_nonce_url( admin_url( 'admin.php?page=psi-members&action=verify&member_id=' . $m->id ), 'psi_member_action' );
                                    $reject_url = wp_nonce_url( admin_url( 'admin.php?page=psi-members&action=reject&member_id=' . $m->id ), 'psi_member_action' );
                                    $delete_url = wp_nonce_url( admin_url( 'admin.php?page=psi-members&action=delete&member_id=' . $m->id ), 'psi_member_action' );
                                ?>
                                <tr>
                                    <td class="text-gray-400 text-sm"><?php echo esc_html( $offset + $i + 1 ); ?></td>
                                    <td class="font-semibold"><?php echo esc_html( $m->full_name ); ?></td>
                                    <td class="text-sm"><?php echo esc_html( $m->email ); ?></td>
                                    <td class="text-sm"><?php echo esc_html( $m->phone ); ?></td>
                                    <td class="text-sm"><?php echo esc_html( $m->kabupaten ); ?></td>
                                    <td><span class="psi-badge <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status_label ); ?></span></td>
                                    <td class="text-xs text-gray-400"><?php echo esc_html( $m->registered_at ); ?></td>
                                    <td>
                                        <div class="psi-actions">
                                            <?php if ( $m->status === 'pending' ) : ?>
                                            <a href="<?php echo esc_url( $verify_url ); ?>" class="psi-action-btn psi-action-green" title="Verifikasi"><i class="fas fa-check"></i></a>
                                            <a href="<?php echo esc_url( $reject_url ); ?>" class="psi-action-btn psi-action-yellow" title="Tolak"><i class="fas fa-times"></i></a>
                                            <?php endif; ?>
                                            <a href="<?php echo esc_url( $delete_url ); ?>" class="psi-action-btn psi-action-red" title="Hapus" onclick="return confirm('<?php esc_attr_e( 'Yakin ingin menghapus?', 'psi-papeng' ); ?>')"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="psi-pagination-wrap">
                        <span class="text-sm text-gray-500"><?php printf( esc_html__( 'Menampilkan %d–%d dari %d', 'psi-papeng' ), $offset + 1, min( $offset + $per_page, $total ), $total ); ?></span>
                        <?php
                        $total_pages = ceil( $total / $per_page );
                        if ( $total_pages > 1 ) :
                            echo '<div class="psi-page-btns">';
                            for ( $p = 1; $p <= $total_pages; $p++ ) {
                                $active = $p === $paged ? ' psi-page-active' : '';
                                echo '<a href="' . esc_url( add_query_arg( 'paged', $p, admin_url( 'admin.php?page=psi-members' . ( $search ? '&s=' . urlencode( $search ) : '' ) . ( $filter ? '&filter=' . $filter : '' ) ) ) ) . '" class="psi-page-btn' . $active . '">' . $p . '</a>';
                            }
                            echo '</div>';
                        endif;
                        ?>
                    </div>
                    <?php else : ?>
                    <div class="psi-empty-state">
                        <i class="fas fa-users text-4xl text-gray-300 mb-3"></i>
                        <p><?php esc_html_e( 'Belum ada data anggota.', 'psi-papeng' ); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════════
       PAGE: ADD MEMBER
       ═══════════════════════════════════════════════════════ */
    public function page_add_member(): void {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_members';

        /* Handle POST */
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['_wpnonce'] ?? '', 'psi_add_member' ) ) {
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
                'status'     => sanitize_key( $_POST['status'] ?? 'pending' ),
            ];
            if ( empty( $data['full_name'] ) || empty( $data['email'] ) ) {
                $error = esc_html__( 'Nama dan Email wajib diisi.', 'psi-papeng' );
            } elseif ( ! is_email( $data['email'] ) ) {
                $error = esc_html__( 'Format email tidak valid.', 'psi-papeng' );
            } else {
                $exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table WHERE email = %s", $data['email'] ) );
                if ( $exists ) {
                    $error = esc_html__( 'Email sudah terdaftar.', 'psi-papeng' );
                } else {
                    $data['registered_at']      = current_time( 'mysql' );
                    $data['verification_token'] = wp_generate_password( 32, false );
                    if ( $data['status'] === 'verified' ) $data['verified_at'] = current_time( 'mysql' );
                    $wpdb->insert( $table, $data );
                    PSI_Papeng_Activator::log( 'member_added', 'Anggota ditambahkan: ' . $data['full_name'] );
                    wp_safe_redirect( admin_url( 'admin.php?page=psi-members&msg=added' ) );
                    exit;
                }
            }
        }

        /* Kabupaten list */
        $kab_list = $wpdb->get_col( "SELECT DISTINCT kabupaten FROM {$wpdb->prefix}psi_members WHERE kabupaten != '' ORDER BY kabupaten" );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header">
                <h1><?php esc_html_e( 'Tambah Anggota', 'psi-papeng' ); ?></h1>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-members' ) ); ?>" class="psi-btn psi-btn-ghost"><i class="fas fa-arrow-left mr-2"></i><?php esc_html_e( 'Kembali', 'psi-papeng' ); ?></a>
            </div>

            <?php if ( ! empty( $error ) ) : ?>
            <div class="psi-notice psi-notice-error"><?php echo esc_html( $error ); ?></div>
            <?php endif; ?>

            <div class="psi-admin-card" style="max-width:800px;">
                <form method="post">
                    <?php wp_nonce_field( 'psi_add_member', '_wpnonce' ); ?>
                    <div class="psi-form-grid">
                        <div class="psi-form-group">
                            <label for="full_name"><?php esc_html_e( 'Nama Lengkap', 'psi-papeng' ); ?> <span class="psi-required">*</span></label>
                            <input type="text" id="full_name" name="full_name" required class="psi-input" value="<?php echo esc_attr( $_POST['full_name'] ?? '' ); ?>">
                        </div>
                        <div class="psi-form-group">
                            <label for="email"><?php esc_html_e( 'Email', 'psi-papeng' ); ?> <span class="psi-required">*</span></label>
                            <input type="email" id="email" name="email" required class="psi-input" value="<?php echo esc_attr( $_POST['email'] ?? '' ); ?>">
                        </div>
                        <div class="psi-form-group">
                            <label for="phone"><?php esc_html_e( 'No. Telepon', 'psi-papeng' ); ?> <span class="psi-required">*</span></label>
                            <input type="tel" id="phone" name="phone" required class="psi-input" value="<?php echo esc_attr( $_POST['phone'] ?? '' ); ?>">
                        </div>
                        <div class="psi-form-group">
                            <label for="kabupaten"><?php esc_html_e( 'Kabupaten', 'psi-papeng' ); ?> <span class="psi-required">*</span></label>
                            <input type="text" id="kabupaten" name="kabupaten" required class="psi-input" list="psiKabList" value="<?php echo esc_attr( $_POST['kabupaten'] ?? '' ); ?>">
                            <datalist id="psiKabList">
                                <?php foreach ( $kab_list as $k ) : ?><option value="<?php echo esc_attr( $k ); ?>"><?php endforeach; ?>
                            </datalist>
                        </div>
                        <div class="psi-form-group">
                            <label for="nik"><?php esc_html_e( 'NIK', 'psi-papeng' ); ?></label>
                            <input type="text" id="nik" name="nik" class="psi-input" value="<?php echo esc_attr( $_POST['nik'] ?? '' ); ?>" maxlength="16">
                        </div>
                        <div class="psi-form-group">
                            <label for="birth_date"><?php esc_html_e( 'Tanggal Lahir', 'psi-papeng' ); ?></label>
                            <input type="date" id="birth_date" name="birth_date" class="psi-input" value="<?php echo esc_attr( $_POST['birth_date'] ?? '' ); ?>">
                        </div>
                        <div class="psi-form-group">
                            <label for="gender"><?php esc_html_e( 'Jenis Kelamin', 'psi-papeng' ); ?></label>
                            <select id="gender" name="gender" class="psi-select">
                                <option value=""><?php esc_html_e( 'Pilih', 'psi-papeng' ); ?></option>
                                <option value="L" <?php selected( $_POST['gender'] ?? '', 'L' ); ?>><?php esc_html_e( 'Laki-laki', 'psi-papeng' ); ?></option>
                                <option value="P" <?php selected( $_POST['gender'] ?? '', 'P' ); ?>><?php esc_html_e( 'Perempuan', 'psi-papeng' ); ?></option>
                            </select>
                        </div>
                        <div class="psi-form-group">
                            <label for="occupation"><?php esc_html_e( 'Pekerjaan', 'psi-papeng' ); ?></label>
                            <input type="text" id="occupation" name="occupation" class="psi-input" value="<?php echo esc_attr( $_POST['occupation'] ?? '' ); ?>">
                        </div>
                        <div class="psi-form-group psi-form-full">
                            <label for="address"><?php esc_html_e( 'Alamat', 'psi-papeng' ); ?></label>
                            <textarea id="address" name="address" rows="3" class="psi-input"><?php echo esc_textarea( $_POST['address'] ?? '' ); ?></textarea>
                        </div>
                        <div class="psi-form-group">
                            <label for="status"><?php esc_html_e( 'Status', 'psi-papeng' ); ?></label>
                            <select id="status" name="status" class="psi-select">
                                <option value="pending" <?php selected( $_POST['status'] ?? '', 'pending' ); ?>><?php esc_html_e( 'Menunggu Verifikasi', 'psi-papeng' ); ?></option>
                                <option value="verified" <?php selected( $_POST['status'] ?? '', 'verified' ); ?>><?php esc_html_e( 'Terverifikasi', 'psi-papeng' ); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="psi-form-actions">
                        <button type="submit" class="psi-btn psi-btn-red"><i class="fas fa-save mr-2"></i><?php esc_html_e( 'Simpan Anggota', 'psi-papeng' ); ?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════════
       PAGE: STATISTICS
       ═══════════════════════════════════════════════════════ */
    public function page_stats(): void {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_members';

        $total      = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
        $verified   = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'verified'" );
        $pending    = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'pending'" );
        $rejected   = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE status = 'rejected'" );
        $male       = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE gender = 'L'" );
        $female     = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE gender = 'P'" );
        $kab_stats  = $wpdb->get_results( "SELECT kabupaten, COUNT(*) as cnt FROM $table GROUP BY kabupaten ORDER BY cnt DESC" );

        /* Monthly registration trend */
        $monthly = $wpdb->get_results( "SELECT DATE_FORMAT(registered_at, '%Y-%m') as month, COUNT(*) as cnt FROM $table GROUP BY month ORDER BY month DESC LIMIT 12" );
        $monthly = array_reverse( $monthly );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header">
                <h1><?php esc_html_e( 'Statistik Anggota', 'psi-papeng' ); ?></h1>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-members' ) ); ?>" class="psi-btn psi-btn-ghost"><i class="fas fa-users mr-2"></i><?php esc_html_e( 'Lihat Data', 'psi-papeng' ); ?></a>
            </div>

            <!-- Summary Cards -->
            <div class="psi-stats-grid">
                <div class="psi-stat-card psi-stat-red">
                    <div class="psi-stat-icon"><i class="fas fa-users"></i></div>
                    <div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $total ) ); ?></span><span class="psi-stat-label"><?php esc_html_e( 'Total', 'psi-papeng' ); ?></span></div>
                </div>
                <div class="psi-stat-card psi-stat-green">
                    <div class="psi-stat-icon"><i class="fas fa-check"></i></div>
                    <div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $verified ) ); ?></span><span class="psi-stat-label"><?php esc_html_e( 'Terverifikasi', 'psi-papeng' ); ?></span></div>
                </div>
                <div class="psi-stat-card psi-stat-yellow">
                    <div class="psi-stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $pending ) ); ?></span><span class="psi-stat-label"><?php esc_html_e( 'Menunggu', 'psi-papeng' ); ?></span></div>
                </div>
                <div class="psi-stat-card psi-stat-gray">
                    <div class="psi-stat-icon"><i class="fas fa-times-circle"></i></div>
                    <div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $rejected ) ); ?></span><span class="psi-stat-label"><?php esc_html_e( 'Ditolak', 'psi-papeng' ); ?></span></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <!-- Gender -->
                <div class="psi-admin-card">
                    <div class="psi-card-header"><h2><i class="fas fa-venus-mars mr-2"></i><?php esc_html_e( 'Berdasarkan Gender', 'psi-papeng' ); ?></h2></div>
                    <div class="psi-card-body">
                        <div class="psi-chart-bars">
                            <?php
                            $gender_max = max( $male, $female, 1 );
                            ?>
                            <div class="psi-chart-row">
                                <span class="psi-chart-label"><?php esc_html_e( 'Laki-laki', 'psi-papeng' ); ?></span>
                                <div class="psi-chart-bar-track"><div class="psi-chart-bar-fill" style="width:<?php echo esc_attr( round( ( $male / $gender_max ) * 100 ) ); ?>%;background:#2563EB;"></div></div>
                                <span class="psi-chart-value"><?php echo esc_html( $male ); ?></span>
                            </div>
                            <div class="psi-chart-row">
                                <span class="psi-chart-label"><?php esc_html_e( 'Perempuan', 'psi-papeng' ); ?></span>
                                <div class="psi-chart-bar-track"><div class="psi-chart-bar-fill" style="width:<?php echo esc_attr( round( ( $female / $gender_max ) * 100 ) ); ?>%;background:#DB2777;"></div></div>
                                <span class="psi-chart-value"><?php echo esc_html( $female ); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Trend -->
                <div class="psi-admin-card">
                    <div class="psi-card-header"><h2><i class="fas fa-chart-line mr-2"></i><?php esc_html_e( 'Tren Pendaftaran Bulanan', 'psi-papeng' ); ?></h2></div>
                    <div class="psi-card-body">
                        <div class="psi-chart-bars">
                            <?php
                            $month_max = max( array_column( $monthly, 'cnt' ) ) ?: 1;
                            foreach ( $monthly as $m ) :
                            ?>
                            <div class="psi-chart-row">
                                <span class="psi-chart-label"><?php echo esc_html( $m->month ); ?></span>
                                <div class="psi-chart-bar-track"><div class="psi-chart-bar-fill" style="width:<?php echo esc_attr( round( ( $m->cnt / $month_max ) * 100 ) ); ?>%;background:#D6001C;"></div></div>
                                <span class="psi-chart-value"><?php echo esc_html( $m->cnt ); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Per Kabupaten -->
            <?php if ( $kab_stats ) : ?>
            <div class="psi-admin-card mt-6">
                <div class="psi-card-header"><h2><i class="fas fa-map mr-2"></i><?php esc_html_e( 'Per Kabupaten', 'psi-papeng' ); ?></h2></div>
                <div class="psi-card-body p-0">
                    <table class="psi-table">
                        <thead><tr><th>#</th><th><?php esc_html_e( 'Kabupaten', 'psi-papeng' ); ?></th><th><?php esc_html_e( 'Jumlah', 'psi-papeng' ); ?></th><th><?php esc_html_e( 'Persentase', 'psi-papeng' ); ?></th></tr></thead>
                        <tbody>
                            <?php foreach ( $kab_stats as $i => $ks ) :
                                $pct = $total > 0 ? round( ( $ks->cnt / $total ) * 100, 1 ) : 0;
                            ?>
                            <tr>
                                <td><?php echo esc_html( $i + 1 ); ?></td>
                                <td class="font-semibold"><?php echo esc_html( $ks->kabupaten ?: 'Tidak ditentukan' ); ?></td>
                                <td><?php echo esc_html( $ks->cnt ); ?></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-red-600 rounded-full" style="width:<?php echo esc_attr( $pct ); ?>%;"></div></div>
                                        <span class="text-sm text-gray-500 w-12 text-right"><?php echo esc_html( $pct ); ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Export -->
            <div class="psi-admin-card mt-6">
                <div class="psi-card-header"><h2><i class="fas fa-download mr-2"></i><?php esc_html_e( 'Ekspor Data', 'psi-papeng' ); ?></h2></div>
                <div class="psi-card-body">
                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php?action=psi_export_members&nonce=' . wp_create_nonce( 'psi_admin_nonce' ) ) ); ?>">
                        <button type="submit" class="psi-btn psi-btn-red"><i class="fas fa-file-csv mr-2"></i><?php esc_html_e( 'Ekspor CSV', 'psi-papeng' ); ?></button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════════
       PAGE: ACTIVITY LOGS
       ═══════════════════════════════════════════════════════ */
    public function page_logs(): void {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_activity_logs';

        $per_page = 30;
        $paged    = max( 1, intval( $_GET['paged'] ?? 1 ) );
        $offset   = ( $paged - 1 ) * $per_page;
        $total    = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
        $logs     = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table ORDER BY created_at DESC LIMIT %d OFFSET %d", $per_page, $offset ) );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header">
                <h1><?php esc_html_e( 'Log Aktivitas', 'psi-papeng' ); ?></h1>
                <?php if ( current_user_can( 'manage_options' ) ) : ?>
                <form method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php?action=psi_clear_logs&nonce=' . wp_create_nonce( 'psi_admin_nonce' ) ) ); ?>" onsubmit="return confirm('<?php esc_attr_e( 'Yakin ingin menghapus semua log?', 'psi-papeng' ); ?>')">
                    <button type="submit" class="psi-btn psi-btn-ghost psi-text-red"><i class="fas fa-trash mr-2"></i><?php esc_html_e( 'Hapus Semua Log', 'psi-papeng' ); ?></button>
                </form>
                <?php endif; ?>
            </div>

            <div class="psi-admin-card">
                <div class="psi-card-body p-0">
                    <?php if ( $logs ) : ?>
                    <table class="psi-table">
                        <thead><tr><th>#</th><th><?php esc_html_e( 'User ID', 'psi-papeng' ); ?></th><th><?php esc_html_e( 'Aksi', 'psi-papeng' ); ?></th><th><?php esc_html_e( 'Deskripsi', 'psi-papeng' ); ?></th><th><?php esc_html_e( 'IP Address', 'psi-papeng' ); ?></th><th><?php esc_html_e( 'Waktu', 'psi-papeng' ); ?></th></tr></thead>
                        <tbody>
                            <?php foreach ( $logs as $i => $log ) : ?>
                            <tr>
                                <td class="text-gray-400 text-sm"><?php echo esc_html( $offset + $i + 1 ); ?></td>
                                <td><?php echo esc_html( $log->user_id ); ?></td>
                                <td><span class="psi-badge"><?php echo esc_html( $log->action ); ?></span></td>
                                <td><?php echo esc_html( $log->description ); ?></td>
                                <td class="text-xs text-gray-400"><?php echo esc_html( $log->ip_address ); ?></td>
                                <td class="text-xs text-gray-400"><?php echo esc_html( $log->created_at ); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                    $total_pages = ceil( $total / $per_page );
                    if ( $total_pages > 1 ) :
                        echo '<div class="psi-pagination-wrap"><div class="psi-page-btns">';
                        for ( $p = 1; $p <= $total_pages; $p++ ) {
                            echo '<a href="' . esc_url( add_query_arg( 'paged', $p ) ) . '" class="psi-page-btn' . ( $p === $paged ? ' psi-page-active' : '' ) . '">' . $p . '</a>';
                        }
                        echo '</div></div>';
                    endif;
                    ?>
                    <?php else : ?>
                    <div class="psi-empty-state"><i class="fas fa-history text-4xl text-gray-300 mb-3"></i><p><?php esc_html_e( 'Belum ada log aktivitas.', 'psi-papeng' ); ?></p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /* ═══════════════════════════════════════════════════════
       PAGE: SETTINGS
       ═══════════════════════════════════════════════════════ */
    public function page_settings(): void {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['_wpnonce'] ?? '', 'psi_settings' ) ) {
            $settings = [
                'psi_smtp_host'     => sanitize_text_field( wp_unslash( $_POST['smtp_host'] ?? '' ) ),
                'psi_smtp_port'     => absint( $_POST['smtp_port'] ?? 587 ),
                'psi_smtp_user'     => sanitize_text_field( wp_unslash( $_POST['smtp_user'] ?? '' ) ),
                'psi_smtp_pass'     => sanitize_text_field( wp_unslash( $_POST['smtp_pass'] ?? '' ) ),
                'psi_smtp_from'     => sanitize_email( wp_unslash( $_POST['smtp_from'] ?? '' ) ),
                'psi_smtp_from_name'=> sanitize_text_field( wp_unslash( $_POST['smtp_from_name'] ?? '' ) ),
                'psi_smtp_encryption'=> sanitize_key( $_POST['smtp_encryption'] ?? 'tls' ),
                'psi_wa_number'     => sanitize_text_field( wp_unslash( $_POST['wa_number'] ?? '' ) ),
                'psi_wa_enabled'    => isset( $_POST['wa_enabled'] ) ? '1' : '0',
                'psi_member_redirect' => esc_url_raw( wp_unslash( $_POST['member_redirect'] ?? 'https://psi.id/menjadi-anggota' ) ),
            ];
            foreach ( $settings as $key => $value ) {
                update_option( $key, $value );
            }
            PSI_Papeng_Activator::log( 'settings_updated', 'Pengaturan plugin diperbarui' );
            echo '<div class="psi-notice psi-notice-success">' . esc_html__( 'Pengaturan berhasil disimpan.', 'psi-papeng' ) . '</div>';
        }

        $smtp_host      = get_option( 'psi_smtp_host', '' );
        $smtp_port      = get_option( 'psi_smtp_port', 587 );
        $smtp_user      = get_option( 'psi_smtp_user', '' );
        $smtp_pass      = get_option( 'psi_smtp_pass', '' );
        $smtp_from      = get_option( 'psi_smtp_from', '' );
        $smtp_from_name = get_option( 'psi_smtp_from_name', '' );
        $smtp_encryption= get_option( 'psi_smtp_encryption', 'tls' );
        $wa_number      = get_option( 'psi_wa_number', '6282267218125' );
        $wa_enabled     = get_option( 'psi_wa_enabled', '1' );
        $member_redirect= get_option( 'psi_member_redirect', 'https://psi.id/menjadi-anggota' );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header">
                <h1><?php esc_html_e( 'Pengaturan Plugin', 'psi-papeng' ); ?></h1>
            </div>

            <form method="post">
                <?php wp_nonce_field( 'psi_settings', '_wpnonce' ); ?>

                <!-- SMTP Settings -->
                <div class="psi-admin-card mb-6">
                    <div class="psi-card-header">
                        <h2><i class="fas fa-envelope mr-2"></i><?php esc_html_e( 'Pengaturan SMTP Email', 'psi-papeng' ); ?></h2>
                    </div>
                    <div class="psi-card-body">
                        <p class="text-sm text-gray-500 mb-6"><?php esc_html_e( 'Konfigurasi SMTP untuk pengiriman email notifikasi. Biarkan kosong jika menggunakan pengaturan default WordPress.', 'psi-papeng' ); ?></p>
                        <div class="psi-form-grid">
                            <div class="psi-form-group">
                                <label for="smtp_host"><?php esc_html_e( 'SMTP Host', 'psi-papeng' ); ?></label>
                                <input type="text" id="smtp_host" name="smtp_host" class="psi-input" value="<?php echo esc_attr( $smtp_host ); ?>" placeholder="smtp.gmail.com">
                            </div>
                            <div class="psi-form-group">
                                <label for="smtp_port"><?php esc_html_e( 'SMTP Port', 'psi-papeng' ); ?></label>
                                <input type="number" id="smtp_port" name="smtp_port" class="psi-input" value="<?php echo esc_attr( $smtp_port ); ?>">
                            </div>
                            <div class="psi-form-group">
                                <label for="smtp_user"><?php esc_html_e( 'SMTP Username', 'psi-papeng' ); ?></label>
                                <input type="text" id="smtp_user" name="smtp_user" class="psi-input" value="<?php echo esc_attr( $smtp_user ); ?>">
                            </div>
                            <div class="psi-form-group">
                                <label for="smtp_pass"><?php esc_html_e( 'SMTP Password', 'psi-papeng' ); ?></label>
                                <input type="password" id="smtp_pass" name="smtp_pass" class="psi-input" value="<?php echo esc_attr( $smtp_pass ); ?>">
                            </div>
                            <div class="psi-form-group">
                                <label for="smtp_from"><?php esc_html_e( 'From Email', 'psi-papeng' ); ?></label>
                                <input type="email" id="smtp_from" name="smtp_from" class="psi-input" value="<?php echo esc_attr( $smtp_from ); ?>">
                            </div>
                            <div class="psi-form-group">
                                <label for="smtp_from_name"><?php esc_html_e( 'From Name', 'psi-papeng' ); ?></label>
                                <input type="text" id="smtp_from_name" name="smtp_from_name" class="psi-input" value="<?php echo esc_attr( $smtp_from_name ); ?>" placeholder="DPW PSI Papua Pegunungan">
                            </div>
                            <div class="psi-form-group">
                                <label for="smtp_encryption"><?php esc_html_e( 'Enkripsi', 'psi-papeng' ); ?></label>
                                <select id="smtp_encryption" name="smtp_encryption" class="psi-select">
                                    <option value="tls" <?php selected( $smtp_encryption, 'tls' ); ?>>TLS</option>
                                    <option value="ssl" <?php selected( $smtp_encryption, 'ssl' ); ?>>SSL</option>
                                    <option value="none" <?php selected( $smtp_encryption, 'none' ); ?>><?php esc_html_e( 'Tanpa Enkripsi', 'psi-papeng' ); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp Settings -->
                <div class="psi-admin-card mb-6">
                    <div class="psi-card-header">
                        <h2><i class="fab fa-whatsapp mr-2"></i><?php esc_html_e( 'Pengaturan WhatsApp', 'psi-papeng' ); ?></h2>
                    </div>
                    <div class="psi-card-body">
                        <div class="psi-form-grid">
                            <div class="psi-form-group">
                                <label><?php esc_html_e( 'Tombol WhatsApp Mengambang', 'psi-papeng' ); ?></label>
                                <label class="psi-toggle">
                                    <input type="checkbox" name="wa_enabled" value="1" <?php checked( $wa_enabled, '1' ); ?>>
                                    <span class="psi-toggle-slider"></span>
                                    <span class="psi-toggle-label"><?php esc_html_e( 'Aktifkan', 'psi-papeng' ); ?></span>
                                </label>
                            </div>
                            <div class="psi-form-group">
                                <label for="wa_number"><?php esc_html_e( 'Nomor WhatsApp', 'psi-papeng' ); ?></label>
                                <input type="text" id="wa_number" name="wa_number" class="psi-input" value="<?php echo esc_attr( $wa_number ); ?>" placeholder="6282267218125">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Membership Settings -->
                <div class="psi-admin-card mb-6">
                    <div class="psi-card-header">
                        <h2><i class="fas fa-user-plus mr-2"></i><?php esc_html_e( 'Pengaturan Keanggotaan', 'psi-papeng' ); ?></h2>
                    </div>
                    <div class="psi-card-body">
                        <div class="psi-form-group" style="max-width:500px;">
                            <label for="member_redirect"><?php esc_html_e( 'URL Redirect Pendaftaran', 'psi-papeng' ); ?></label>
                            <input type="url" id="member_redirect" name="member_redirect" class="psi-input" value="<?php echo esc_url( $member_redirect ); ?>">
                            <p class="text-xs text-gray-400 mt-1"><?php esc_html_e( 'URL yang dituju ketika pengguna klik "Daftar Anggota"', 'psi-papeng' ); ?></p>
                        </div>
                    </div>
                </div>

                <div class="psi-form-actions">
                    <button type="submit" class="psi-btn psi-btn-red"><i class="fas fa-save mr-2"></i><?php esc_html_e( 'Simpan Pengaturan', 'psi-papeng' ); ?></button>
                </div>
            </form>
        </div>
        <?php
    }
}
