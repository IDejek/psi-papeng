<?php
defined( 'ABSPATH' ) || exit;

class PSI_Papeng_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menus' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_filter( 'set-screen-option', [ $this, 'set_screen_option' ], 10, 3 );
    }

    public function register_menus(): void {
        add_menu_page( 'PSI Dashboard', 'PSI Premium', 'manage_options', 'psi-dashboard', [ $this, 'page_dashboard' ], 'dashicons-shield-alt', 2 );
        add_submenu_page( 'psi-dashboard', 'Data Anggota', 'Data Anggota', 'manage_options', 'psi-members', [ $this, 'page_members' ] );
        add_submenu_page( 'psi-dashboard', 'Tambah Anggota', 'Tambah Anggota', 'manage_options', 'psi-add-member', [ $this, 'page_add_member' ] );
        add_submenu_page( 'psi-dashboard', 'Statistik Anggota', 'Statistik Anggota', 'manage_options', 'psi-stats', [ $this, 'page_stats' ] );
        add_submenu_page( 'psi-dashboard', 'Log Aktivitas', 'Log Aktivitas', 'manage_options', 'psi-logs', [ $this, 'page_logs' ] );
        add_submenu_page( 'psi-dashboard', 'Pengaturan', 'Pengaturan', 'manage_options', 'psi-settings', [ $this, 'page_settings' ] );
    }

    public function enqueue_assets( $hook ): void {
        if ( strpos( $hook, 'psi-' ) === false ) return;
        wp_enqueue_style( 'psi-admin-dash', PSI_PLUGIN_URI . 'assets/css/admin-dashboard.css', [], PSI_PLUGIN_VERSION );
        wp_enqueue_script( 'psi-admin-dash', PSI_PLUGIN_URI . 'assets/js/admin-dashboard.js', [ 'jquery' ], PSI_PLUGIN_VERSION, true );
    }

    public function set_screen_option( $status, $option, $value ) {
        if ( $option === 'psi_members_per_page' ) return intval( $value );
        return $status;
    }

    /* ═══ DASHBOARD ═══ */
    public function page_dashboard(): void {
        global $wpdb;
        $mt = $wpdb->prefix . 'psi_members';
        $lt = $wpdb->prefix . 'psi_activity_logs';
        $total    = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $mt" );
        $pending  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $mt WHERE status='pending'" );
        $verified = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $mt WHERE status='verified'" );
        $posts    = wp_count_posts()->publish ?? 0;
        $dpd      = wp_count_posts( 'psi_dpd' )->publish ?? 0;
        $videos   = wp_count_posts( 'psi_video' )->publish ?? 0;
        $kab      = $wpdb->get_results( "SELECT kabupaten, COUNT(*) as cnt FROM $mt WHERE kabupaten!='' GROUP BY kabupaten ORDER BY cnt DESC LIMIT 8" );
        $logs     = $wpdb->get_results( "SELECT * FROM $lt ORDER BY created_at DESC LIMIT 5" );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header"><h1>Dashboard PSI Papua Pegunungan</h1><div class="psi-admin-version">v<?php echo esc_html( PSI_PLUGIN_VERSION ); ?></div></div>
            <div class="psi-stats-grid">
                <div class="psi-stat-card psi-stat-red"><div class="psi-stat-icon"><i class="fas fa-users"></i></div><div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $total ) ); ?></span><span class="psi-stat-label">Total Anggota</span></div></div>
                <div class="psi-stat-card psi-stat-yellow"><div class="psi-stat-icon"><i class="fas fa-clock"></i></div><div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $pending ) ); ?></span><span class="psi-stat-label">Menunggu Verifikasi</span></div></div>
                <div class="psi-stat-card psi-stat-green"><div class="psi-stat-icon"><i class="fas fa-check-circle"></i></div><div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $verified ) ); ?></span><span class="psi-stat-label">Terverifikasi</span></div></div>
                <div class="psi-stat-card psi-stat-blue"><div class="psi-stat-icon"><i class="fas fa-newspaper"></i></div><div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $posts ) ); ?></span><span class="psi-stat-label">Berita</span></div></div>
                <div class="psi-stat-card psi-stat-purple"><div class="psi-stat-icon"><i class="fas fa-map-marker-alt"></i></div><div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $dpd ) ); ?></span><span class="psi-stat-label">DPD</span></div></div>
                <div class="psi-stat-card psi-stat-gray"><div class="psi-stat-icon"><i class="fas fa-video"></i></div><div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $videos ) ); ?></span><span class="psi-stat-label">Video</span></div></div>
            </div>
            <?php if ( $kab ) : ?>
            <div class="psi-admin-card mt-6">
                <div class="psi-card-header"><h2><i class="fas fa-chart-bar mr-2"></i>Statistik per Kabupaten</h2></div>
                <div class="psi-card-body"><div class="psi-chart-bars">
                    <?php $mx = max( array_column( $kab, 'cnt' ) ) ?: 1; $colors = ['#D6001C','#D4AF37','#2563EB','#059669','#7C3AED','#DB2777','#EA580C','#4B5563']; $ci = 0;
                    foreach ( $kab as $k ) : $pct = round( ( $k->cnt / $mx ) * 100 ); ?>
                    <div class="psi-chart-row"><span class="psi-chart-label"><?php echo esc_html( $k->kabupaten ); ?></span><div class="psi-chart-bar-track"><div class="psi-chart-bar-fill" style="width:<?php echo esc_attr( $pct ); ?>%;background:<?php echo esc_attr( $colors[ $ci % 8 ] ); ?>"></div></div><span class="psi-chart-value"><?php echo esc_html( $k->cnt ); ?></span></div>
                    <?php $ci++; endforeach; ?>
                </div></div>
            </div>
            <?php endif; ?>
            <?php if ( $logs ) : ?>
            <div class="psi-admin-card mt-6">
                <div class="psi-card-header"><h2><i class="fas fa-history mr-2"></i>Aktivitas Terbaru</h2><a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-logs' ) ); ?>" class="psi-btn-sm">Lihat Semua</a></div>
                <div class="psi-card-body p-0"><table class="psi-table"><thead><tr><th>Aksi</th><th>Deskripsi</th><th>Waktu</th></tr></thead><tbody>
                    <?php foreach ( $logs as $l ) : ?><tr><td><span class="psi-badge"><?php echo esc_html( $l->action ); ?></span></td><td><?php echo esc_html( $l->description ); ?></td><td class="text-xs text-gray-400"><?php echo esc_html( $l->created_at ); ?></td></tr><?php endforeach; ?>
                </tbody></table></div>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /* ═══ MEMBERS ═══ */
    public function page_members(): void {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_members';

        if ( isset( $_GET['action'], $_GET['member_id'], $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'psi_member_action' ) ) {
            $id  = absint( $_GET['member_id'] );
            $act = sanitize_key( $_GET['action'] );
            if ( $act === 'verify' ) {
                $wpdb->update( $table, [ 'status' => 'verified', 'verified_at' => current_time( 'mysql' ) ], [ 'id' => $id ] );
                PSI_Papeng_Activator::log( 'member_verified', 'ID ' . $id );
                wp_safe_redirect( admin_url( 'admin.php?page=psi-members&msg=verified' ) ); exit;
            }
            if ( $act === 'delete' ) {
                $wpdb->delete( $table, [ 'id' => $id ] );
                PSI_Papeng_Activator::log( 'member_deleted', 'ID ' . $id );
                wp_safe_redirect( admin_url( 'admin.php?page=psi-members&msg=deleted' ) ); exit;
            }
        }

        $where = '1=1';
        $search = sanitize_text_field( wp_unslash( $_GET['s'] ?? '' ) );
        $filter = sanitize_key( $_GET['filter'] ?? '' );
        if ( $search ) { $like = '%' . $wpdb->esc_like( $search ) . '%'; $where .= $wpdb->prepare( " AND (full_name LIKE %s OR email LIKE %s OR kabupaten LIKE %s)", $like, $like, $like ); }
        if ( $filter ) $where .= $wpdb->prepare( " AND status = %s", $filter );

        $per_page = 20;
        $paged    = max( 1, intval( $_GET['paged'] ?? 1 ) );
        $offset   = ( $paged - 1 ) * $per_page;
        $total    = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE $where" );
        $members  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE $where ORDER BY registered_at DESC LIMIT %d OFFSET %d", $per_page, $offset ) );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header"><h1>Data Anggota</h1><a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-add-member' ) ); ?>" class="psi-btn psi-btn-red"><i class="fas fa-plus mr-2"></i>Tambah Anggota</a></div>
            <?php if ( isset( $_GET['msg'] ) ) : ?><div class="psi-notice psi-notice-success"><?php echo esc_html( [ 'verified' => 'Diverifikasi.', 'deleted' => 'Dihapus.', 'added' => 'Ditambahkan.' ][ sanitize_key( $_GET['msg'] ) ] ?? '' ); ?></div><?php endif; ?>
            <div class="psi-admin-card">
                <div class="psi-card-header">
                    <form method="get" class="flex items-center gap-3 flex-wrap">
                        <input type="hidden" name="page" value="psi-members">
                        <input type="text" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="Cari..." class="psi-input" style="min-width:250px">
                        <select name="filter" class="psi-select"><option value="">Semua Status</option><option value="pending" <?php selected( $filter, 'pending' ); ?>>Menunggu</option><option value="verified" <?php selected( $filter, 'verified' ); ?>>Terverifikasi</option></select>
                        <button type="submit" class="psi-btn psi-btn-gray">Filter</button>
                    </form>
                </div>
                <div class="psi-card-body p-0">
                    <?php if ( $members ) : ?>
                    <div class="psi-table-responsive"><table class="psi-table"><thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Kabupaten</th><th>Status</th><th>Terdaftar</th><th>Aksi</th></tr></thead><tbody>
                        <?php foreach ( $members as $i => $m ) :
                            $verify_url = wp_nonce_url( admin_url( 'admin.php?page=psi-members&action=verify&member_id=' . $m->id ), 'psi_member_action' );
                            $delete_url = wp_nonce_url( admin_url( 'admin.php?page=psi-members&action=delete&member_id=' . $m->id ), 'psi_member_action' );
                        ?>
                        <tr>
                            <td class="text-gray-400 text-sm"><?php echo esc_html( $offset + $i + 1 ); ?></td>
                            <td class="font-semibold"><?php echo esc_html( $m->full_name ); ?></td>
                            <td class="text-sm"><?php echo esc_html( $m->email ); ?></td>
                            <td class="text-sm"><?php echo esc_html( $m->phone ); ?></td>
                            <td class="text-sm"><?php echo esc_html( $m->kabupaten ); ?></td>
                            <td><span class="psi-badge psi-status-<?php echo esc_attr( $m->status ); ?>"><?php echo esc_html( $m->status === 'pending' ? 'Menunggu' : 'Terverifikasi' ); ?></span></td>
                            <td class="text-xs text-gray-400"><?php echo esc_html( $m->registered_at ); ?></td>
                            <td><div class="psi-actions">
                                <?php if ( $m->status === 'pending' ) : ?><a href="<?php echo esc_url( $verify_url ); ?>" class="psi-action-btn psi-action-green" title="Verifikasi"><i class="fas fa-check"></i></a><?php endif; ?>
                                <a href="<?php echo esc_url( $delete_url ); ?>" class="psi-action-btn psi-action-red" title="Hapus" onclick="return confirm('Hapus anggota ini?')"><i class="fas fa-trash"></i></a>
                            </div></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody></table></div>
                    <div class="psi-pagination-wrap">
                        <span class="text-sm text-gray-500"><?php echo esc_html( $offset + 1 . '–' . min( $offset + $per_page, $total ) . ' / ' . $total ); ?></span>
                    </div>
                    <?php else : ?>
                    <div class="psi-empty-state"><i class="fas fa-users text-4xl text-gray-300 mb-3"></i><p>Belum ada data anggota.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /* ═══ ADD MEMBER ═══ */
    public function page_add_member(): void {
        global $wpdb;
        $table = $wpdb->prefix . 'psi_members';
        $error = '';

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
            if ( empty( $data['full_name'] ) || empty( $data['email'] ) ) $error = 'Nama dan Email wajib.';
            elseif ( ! is_email( $data['email'] ) ) $error = 'Email tidak valid.';
            else {
                $exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table WHERE email = %s", $data['email'] ) );
                if ( $exists ) $error = 'Email sudah terdaftar.';
                else {
                    $data['registered_at'] = current_time( 'mysql' );
                    $data['verification_token'] = wp_generate_password( 32, false );
                    if ( empty( $data['birth_date'] ) ) $data['birth_date'] = null;
                    if ( $data['status'] === 'verified' ) $data['verified_at'] = current_time( 'mysql' );
                    $wpdb->insert( $table, $data );
                    PSI_Papeng_Activator::log( 'member_added', $data['full_name'] );
                    wp_safe_redirect( admin_url( 'admin.php?page=psi-members&msg=added' ) ); exit;
                }
            }
        }
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header"><h1>Tambah Anggota</h1><a href="<?php echo esc_url( admin_url( 'admin.php?page=psi-members' ) ); ?>" class="psi-btn psi-btn-ghost"><i class="fas fa-arrow-left mr-2"></i>Kembali</a></div>
            <?php if ( $error ) : ?><div class="psi-notice psi-notice-error"><?php echo esc_html( $error ); ?></div><?php endif; ?>
            <div class="psi-admin-card" style="max-width:800px">
                <form method="post"><?php wp_nonce_field( 'psi_add_member', '_wpnonce' ); ?>
                    <div class="psi-form-grid">
                        <div class="psi-form-group"><label>Nama Lengkap <span class="psi-required">*</span></label><input type="text" name="full_name" required class="psi-input" value="<?php echo esc_attr( $_POST['full_name'] ?? '' ); ?>"></div>
                        <div class="psi-form-group"><label>Email <span class="psi-required">*</span></label><input type="email" name="email" required class="psi-input" value="<?php echo esc_attr( $_POST['email'] ?? '' ); ?>"></div>
                        <div class="psi-form-group"><label>Telepon <span class="psi-required">*</span></label><input type="tel" name="phone" required class="psi-input" value="<?php echo esc_attr( $_POST['phone'] ?? '' ); ?>"></div>
                        <div class="psi-form-group"><label>Kabupaten <span class="psi-required">*</span></label><input type="text" name="kabupaten" required class="psi-input" value="<?php echo esc_attr( $_POST['kabupaten'] ?? '' ); ?>"></div>
                        <div class="psi-form-group"><label>NIK</label><input type="text" name="nik" class="psi-input" maxlength="16" value="<?php echo esc_attr( $_POST['nik'] ?? '' ); ?>"></div>
                        <div class="psi-form-group"><label>Tanggal Lahir</label><input type="date" name="birth_date" class="psi-input" value="<?php echo esc_attr( $_POST['birth_date'] ?? '' ); ?>"></div>
                        <div class="psi-form-group"><label>Jenis Kelamin</label><select name="gender" class="psi-select"><option value="">Pilih</option><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
                        <div class="psi-form-group"><label>Pekerjaan</label><input type="text" name="occupation" class="psi-input" value="<?php echo esc_attr( $_POST['occupation'] ?? '' ); ?>"></div>
                        <div class="psi-form-group psi-form-full"><label>Alamat</label><textarea name="address" rows="3" class="psi-input"><?php echo esc_textarea( $_POST['address'] ?? '' ); ?></textarea></div>
                        <div class="psi-form-group"><label>Status</label><select name="status" class="psi-select"><option value="pending">Menunggu Verifikasi</option><option value="verified">Terverifikasi</option></select></div>
                    </div>
                    <div class="psi-form-actions"><button type="submit" class="psi-btn psi-btn-red"><i class="fas fa-save mr-2"></i>Simpan</button></div>
                </form>
            </div>
        </div>
        <?php
    }

    /* ═══ STATS ═══ */
    public function page_stats(): void {
        global $wpdb;
        $t = $wpdb->prefix . 'psi_members';
        $total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $t" );
        $verified = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $t WHERE status='verified'" );
        $pending = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $t WHERE status='pending'" );
        $male = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $t WHERE gender='L'" );
        $female = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $t WHERE gender='P'" );
        $kab = $wpdb->get_results( "SELECT kabupaten, COUNT(*) as cnt FROM $t WHERE kabupaten!='' GROUP BY kabupaten ORDER BY cnt DESC" );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header"><h1>Statistik Anggota</h1></div>
            <div class="psi-stats-grid">
                <div class="psi-stat-card psi-stat-red"><div class="psi-stat-icon"><i class="fas fa-users"></i></div><div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $total ) ); ?></span><span class="psi-stat-label">Total</span></div></div>
                <div class="psi-stat-card psi-stat-green"><div class="psi-stat-icon"><i class="fas fa-check"></i></div><div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $verified ) ); ?></span><span class="psi-stat-label">Terverifikasi</span></div></div>
                <div class="psi-stat-card psi-stat-yellow"><div class="psi-stat-icon"><i class="fas fa-clock"></i></div><div class="psi-stat-info"><span class="psi-stat-number"><?php echo esc_html( number_format_i18n( $pending ) ); ?></span><span class="psi-stat-label">Menunggu</span></div></div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div class="psi-admin-card"><div class="psi-card-header"><h2><i class="fas fa-venus-mars mr-2"></i>Gender</h2></div><div class="psi-card-body"><div class="psi-chart-bars"><?php $gm = max( $male, $female, 1 ); ?>
                    <div class="psi-chart-row"><span class="psi-chart-label">Laki-laki</span><div class="psi-chart-bar-track"><div class="psi-chart-bar-fill" style="width:<?php echo esc_attr( round( ( $male / $gm ) * 100 ) ); ?>%;background:#2563EB"></div></div><span class="psi-chart-value"><?php echo esc_html( $male ); ?></span></div>
                    <div class="psi-chart-row"><span class="psi-chart-label">Perempuan</span><div class="psi-chart-bar-track"><div class="psi-chart-bar-fill" style="width:<?php echo esc_attr( round( ( $female / $gm ) * 100 ) ); ?>%;background:#DB2777"></div></div><span class="psi-chart-value"><?php echo esc_html( $female ); ?></span></div>
                </div></div></div>
                <div class="psi-admin-card"><div class="psi-card-header"><h2><i class="fas fa-download mr-2"></i>Ekspor</h2></div><div class="psi-card-body">
                    <form method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php?action=psi_export_members&nonce=' . wp_create_nonce( 'psi_admin_nonce' ) ) ); ?>"><button type="submit" class="psi-btn psi-btn-red"><i class="fas fa-file-csv mr-2"></i>Ekspor CSV</button></form>
                </div></div>
            </div>
            <?php if ( $kab ) : ?>
            <div class="psi-admin-card mt-6"><div class="psi-card-header"><h2><i class="fas fa-map mr-2"></i>Per Kabupaten</h2></div><div class="psi-card-body p-0"><table class="psi-table"><thead><tr><th>#</th><th>Kabupaten</th><th>Jumlah</th><th>Persen</th></tr></thead><tbody>
                <?php foreach ( $kab as $i => $k ) : $pct = $total > 0 ? round( ( $k->cnt / $total ) * 100, 1 ) : 0; ?>
                <tr><td><?php echo esc_html( $i + 1 ); ?></td><td class="font-semibold"><?php echo esc_html( $k->kabupaten ); ?></td><td><?php echo esc_html( $k->cnt ); ?></td><td><div class="flex items-center gap-2"><div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-psi-red rounded-full" style="width:<?php echo esc_attr( $pct ); ?>%"></div></div><span class="text-sm w-12 text-right"><?php echo esc_html( $pct ); ?>%</span></div></td></tr>
                <?php endforeach; ?>
            </tbody></table></div></div>
            <?php endif; ?>
        </div>
        <?php
    }

    /* ═══ LOGS ═══ */
    public function page_logs(): void {
        global $wpdb;
        $lt = $wpdb->prefix . 'psi_activity_logs';
        $per_page = 30;
        $paged = max( 1, intval( $_GET['paged'] ?? 1 ) );
        $offset = ( $paged - 1 ) * $per_page;
        $total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $lt" );
        $logs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $lt ORDER BY created_at DESC LIMIT %d OFFSET %d", $per_page, $offset ) );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header"><h1>Log Aktivitas</h1></div>
            <div class="psi-admin-card"><div class="psi-card-body p-0">
                <?php if ( $logs ) : ?>
                <table class="psi-table"><thead><tr><th>#</th><th>Aksi</th><th>Deskripsi</th><th>IP</th><th>Waktu</th></tr></thead><tbody>
                    <?php foreach ( $logs as $i => $l ) : ?><tr><td class="text-gray-400 text-sm"><?php echo esc_html( $offset + $i + 1 ); ?></td><td><span class="psi-badge"><?php echo esc_html( $l->action ); ?></span></td><td><?php echo esc_html( $l->description ); ?></td><td class="text-xs text-gray-400"><?php echo esc_html( $l->ip_address ); ?></td><td class="text-xs text-gray-400"><?php echo esc_html( $l->created_at ); ?></td></tr><?php endforeach; ?>
                </tbody></table>
                <?php else : ?><div class="psi-empty-state"><i class="fas fa-history text-4xl text-gray-300 mb-3"></i><p>Belum ada log.</p></div><?php endif; ?>
            </div></div>
        </div>
        <?php
    }

    /* ═══ SETTINGS ═══ */
    public function page_settings(): void {
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' && wp_verify_nonce( $_POST['_wpnonce'] ?? '', 'psi_settings' ) ) {
            update_option( 'psi_smtp_host', sanitize_text_field( wp_unslash( $_POST['smtp_host'] ?? '' ) ) );
            update_option( 'psi_smtp_port', absint( $_POST['smtp_port'] ?? 587 ) );
            update_option( 'psi_smtp_user', sanitize_text_field( wp_unslash( $_POST['smtp_user'] ?? '' ) ) );
            update_option( 'psi_smtp_pass', sanitize_text_field( wp_unslash( $_POST['smtp_pass'] ?? '' ) ) );
            update_option( 'psi_smtp_from', sanitize_email( wp_unslash( $_POST['smtp_from'] ?? '' ) ) );
            update_option( 'psi_smtp_from_name', sanitize_text_field( wp_unslash( $_POST['smtp_from_name'] ?? '' ) ) );
            update_option( 'psi_smtp_encryption', sanitize_key( $_POST['smtp_encryption'] ?? 'tls' ) );
            update_option( 'psi_wa_number', sanitize_text_field( wp_unslash( $_POST['wa_number'] ?? '' ) ) );
            update_option( 'psi_wa_enabled', isset( $_POST['wa_enabled'] ) ? '1' : '0' );
            update_option( 'psi_member_redirect', esc_url_raw( wp_unslash( $_POST['member_redirect'] ?? 'https://psi.id/menjadi-anggota' ) ) );
            PSI_Papeng_Activator::log( 'settings_updated', 'Pengaturan diperbarui' );
            echo '<div class="psi-notice psi-notice-success">Pengaturan berhasil disimpan.</div>';
        }

        $smtp_host = get_option( 'psi_smtp_host', '' );
        $smtp_port = get_option( 'psi_smtp_port', 587 );
        $smtp_user = get_option( 'psi_smtp_user', '' );
        $smtp_pass = get_option( 'psi_smtp_pass', '' );
        $smtp_from = get_option( 'psi_smtp_from', '' );
        $smtp_from_name = get_option( 'psi_smtp_from_name', '' );
        $smtp_enc = get_option( 'psi_smtp_encryption', 'tls' );
        $wa_num = get_option( 'psi_wa_number', '6282267218125' );
        $wa_on = get_option( 'psi_wa_enabled', '1' );
        $member_url = get_option( 'psi_member_redirect', 'https://psi.id/menjadi-anggota' );
        ?>
        <div class="psi-admin-wrap">
            <div class="psi-admin-header"><h1>Pengaturan Plugin</h1></div>
            <form method="post"><?php wp_nonce_field( 'psi_settings', '_wpnonce' ); ?>
                <div class="psi-admin-card mb-6">
                    <div class="psi-card-header"><h2><i class="fas fa-envelope mr-2"></i>SMTP Email</h2></div>
                    <div class="psi-card-body"><p class="text-sm text-gray-500 mb-6">Biarkan kosong jika menggunakan default WordPress.</p>
                        <div class="psi-form-grid">
                            <div class="psi-form-group"><label>SMTP Host</label><input type="text" name="smtp_host" class="psi-input" value="<?php echo esc_attr( $smtp_host ); ?>" placeholder="smtp.gmail.com"></div>
                            <div class="psi-form-group"><label>SMTP Port</label><input type="number" name="smtp_port" class="psi-input" value="<?php echo esc_attr( $smtp_port ); ?>"></div>
                            <div class="psi-form-group"><label>SMTP Username</label><input type="text" name="smtp_user" class="psi-input" value="<?php echo esc_attr( $smtp_user ); ?>"></div>
                            <div class="psi-form-group"><label>SMTP Password</label><input type="password" name="smtp_pass" class="psi-input" value="<?php echo esc_attr( $smtp_pass ); ?>"></div>
                            <div class="psi-form-group"><label>From Email</label><input type="email" name="smtp_from" class="psi-input" value="<?php echo esc_attr( $smtp_from ); ?>"></div>
                            <div class="psi-form-group"><label>From Name</label><input type="text" name="smtp_from_name" class="psi-input" value="<?php echo esc_attr( $smtp_from_name ); ?>" placeholder="DPW PSI Papua Pegunungan"></div>
                            <div class="psi-form-group"><label>Enkripsi</label><select name="smtp_encryption" class="psi-select"><option value="tls" <?php selected( $smtp_enc, 'tls' ); ?>>TLS</option><option value="ssl" <?php selected( $smtp_enc, 'ssl' ); ?>>SSL</option><option value="none" <?php selected( $smtp_enc, 'none' ); ?>>Tanpa Enkripsi</option></select></div>
                        </div>
                    </div>
                </div>
                <div class="psi-admin-card mb-6">
                    <div class="psi-card-header"><h2><i class="fab fa-whatsapp mr-2"></i>WhatsApp</h2></div>
                    <div class="psi-card-body"><div class="psi-form-grid">
                        <div class="psi-form-group"><label>Tombol WhatsApp</label><label class="psi-toggle"><input type="checkbox" name="wa_enabled" value="1" <?php checked( $wa_on, '1' ); ?>><span class="psi-toggle-slider"></span><span class="psi-toggle-label">Aktifkan</span></label></div>
                        <div class="psi-form-group"><label>Nomor WhatsApp</label><input type="text" name="wa_number" class="psi-input" value="<?php echo esc_attr( $wa_num ); ?>" placeholder="6282267218125"></div>
                    </div></div>
                </div>
                <div class="psi-admin-card mb-6">
                    <div class="psi-card-header"><h2><i class="fas fa-user-plus mr-2"></i>Keanggotaan</h2></div>
                    <div class="psi-card-body"><div class="psi-form-group" style="max-width:500px"><label>URL Redirect Pendaftaran</label><input type="url" name="member_redirect" class="psi-input" value="<?php echo esc_url( $member_url ); ?>"></div></div>
                </div>
                <div class="psi-form-actions"><button type="submit" class="psi-btn psi-btn-red"><i class="fas fa-save mr-2"></i>Simpan Pengaturan</button></div>
            </form>
        </div>
        <?php
    }
}
