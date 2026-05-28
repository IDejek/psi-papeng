<?php
/**
 * Custom Meta Boxes — FIXED
 * @package PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

/* ── Slider Meta Box ──────────────────────────────────────── */
add_action( 'add_meta_boxes', 'psi_slider_metabox' );
function psi_slider_metabox(): void {
    add_meta_box( 'psi_slider_details', esc_html__( 'Detail Slide', 'psi-papeng' ), 'psi_slider_metabox_cb', 'psi_slider', 'normal', 'high' );
}
function psi_slider_metabox_cb( $post ): void {
    wp_nonce_field( 'psi_slider_nonce', 'psi_slider_nonce_field' );
    $subtitle   = get_post_meta( $post->ID, '_psi_slider_subtitle', true );
    $btn_text   = get_post_meta( $post->ID, '_psi_slider_btn_text', true );
    $btn_url    = get_post_meta( $post->ID, '_psi_slider_btn_url', true );
    $btn_text2  = get_post_meta( $post->ID, '_psi_slider_btn_text2', true );
    $btn_url2   = get_post_meta( $post->ID, '_psi_slider_btn_url2', true );
    $order      = get_post_meta( $post->ID, '_psi_slider_order', true );
    ?>
    <div class="psi-metabox" style="max-width:600px;">
        <p><label for="psi_slider_subtitle"><strong>Subjudul</strong></label><br>
        <input type="text" id="psi_slider_subtitle" name="psi_slider_subtitle" value="<?php echo esc_attr( $subtitle ); ?>" class="large-text" style="width:100%;"></p>
        <p><label for="psi_slider_btn_text"><strong>Tombol 1 – Teks</strong></label><br>
        <input type="text" id="psi_slider_btn_text" name="psi_slider_btn_text" value="<?php echo esc_attr( $btn_text ); ?>" style="width:100%;"></p>
        <p><label for="psi_slider_btn_url"><strong>Tombol 1 – URL</strong></label><br>
        <input type="url" id="psi_slider_btn_url" name="psi_slider_btn_url" value="<?php echo esc_attr( $btn_url ); ?>" style="width:100%;"></p>
        <p><label for="psi_slider_btn_text2"><strong>Tombol 2 – Teks</strong></label><br>
        <input type="text" id="psi_slider_btn_text2" name="psi_slider_btn_text2" value="<?php echo esc_attr( $btn_text2 ); ?>" style="width:100%;"></p>
        <p><label for="psi_slider_btn_url2"><strong>Tombol 2 – URL</strong></label><br>
        <input type="url" id="psi_slider_btn_url2" name="psi_slider_btn_url2" value="<?php echo esc_attr( $btn_url2 ); ?>" style="width:100%;"></p>
        <p><label for="psi_slider_order"><strong>Urutan</strong></label><br>
        <input type="number" id="psi_slider_order" name="psi_slider_order" value="<?php echo esc_attr( $order ?: 0 ); ?>" min="0" style="width:100px;"></p>
    </div>
    <?php
}
add_action( 'save_post', 'psi_save_slider_meta' );
function psi_save_slider_meta( $post_id ): void {
    if ( ! isset( $_POST['psi_slider_nonce_field'] ) || ! wp_verify_nonce( $_POST['psi_slider_nonce_field'], 'psi_slider_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $map = [
        'psi_slider_subtitle' => '_psi_slider_subtitle',
        'psi_slider_btn_text' => '_psi_slider_btn_text',
        'psi_slider_btn_url'  => '_psi_slider_btn_url',
        'psi_slider_btn_text2'=> '_psi_slider_btn_text2',
        'psi_slider_btn_url2' => '_psi_slider_btn_url2',
        'psi_slider_order'    => '_psi_slider_order',
    ];
    foreach ( $map as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
}

/* ── Leadership Meta Box ──────────────────────────────────── */
add_action( 'add_meta_boxes', 'psi_leadership_metabox' );
function psi_leadership_metabox(): void {
    add_meta_box( 'psi_leadership_details', esc_html__( 'Detail Pimpinan', 'psi-papeng' ), 'psi_leadership_metabox_cb', 'psi_leadership', 'normal', 'high' );
}
function psi_leadership_metabox_cb( $post ): void {
    wp_nonce_field( 'psi_lead_nonce', 'psi_lead_nonce_field' );
    $position = get_post_meta( $post->ID, '_psi_lead_position', true );
    $fb       = get_post_meta( $post->ID, '_psi_lead_facebook', true );
    $ig       = get_post_meta( $post->ID, '_psi_lead_instagram', true );
    $tw       = get_post_meta( $post->ID, '_psi_lead_twitter', true );
    $order    = get_post_meta( $post->ID, '_psi_lead_order', true );
    $featured = get_post_meta( $post->ID, '_psi_lead_featured', true );
    ?>
    <div class="psi-metabox" style="max-width:600px;">
        <p><label for="psi_lead_position"><strong>Jabatan</strong></label><br>
        <input type="text" id="psi_lead_position" name="psi_lead_position" value="<?php echo esc_attr( $position ); ?>" style="width:100%;"></p>
        <p><label for="psi_lead_facebook"><strong>Facebook URL</strong></label><br>
        <input type="url" id="psi_lead_facebook" name="psi_lead_facebook" value="<?php echo esc_attr( $fb ); ?>" style="width:100%;"></p>
        <p><label for="psi_lead_instagram"><strong>Instagram URL</strong></label><br>
        <input type="url" id="psi_lead_instagram" name="psi_lead_instagram" value="<?php echo esc_attr( $ig ); ?>" style="width:100%;"></p>
        <p><label for="psi_lead_twitter"><strong>Twitter/X URL</strong></label><br>
        <input type="url" id="psi_lead_twitter" name="psi_lead_twitter" value="<?php echo esc_attr( $tw ); ?>" style="width:100%;"></p>
        <p><label for="psi_lead_order"><strong>Urutan Tampil</strong></label><br>
        <input type="number" id="psi_lead_order" name="psi_lead_order" value="<?php echo esc_attr( $order ?: 0 ); ?>" min="0" style="width:100px;"></p>
        <p><label><input type="checkbox" name="psi_lead_featured" value="1" <?php checked( $featured, '1' ); ?>> <strong>Tampilkan di Homepage</strong></label></p>
    </div>
    <?php
}
add_action( 'save_post', 'psi_save_leadership_meta' );
function psi_save_leadership_meta( $post_id ): void {
    if ( ! isset( $_POST['psi_lead_nonce_field'] ) || ! wp_verify_nonce( $_POST['psi_lead_nonce_field'], 'psi_lead_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $map = [
        'psi_lead_position'  => '_psi_lead_position',
        'psi_lead_facebook'  => '_psi_lead_facebook',
        'psi_lead_instagram' => '_psi_lead_instagram',
        'psi_lead_twitter'   => '_psi_lead_twitter',
        'psi_lead_order'     => '_psi_lead_order',
    ];
    foreach ( $map as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
    update_post_meta( $post_id, '_psi_lead_featured', isset( $_POST['psi_lead_featured'] ) ? '1' : '0' );
}

/* ── DPD Meta Box ─────────────────────────────────────────── */
add_action( 'add_meta_boxes', 'psi_dpd_metabox' );
function psi_dpd_metabox(): void {
    add_meta_box( 'psi_dpd_details', esc_html__( 'Detail DPD', 'psi-papeng' ), 'psi_dpd_metabox_cb', 'psi_dpd', 'normal', 'high' );
}
function psi_dpd_metabox_cb( $post ): void {
    wp_nonce_field( 'psi_dpd_nonce', 'psi_dpd_nonce_field' );
    $ketua   = get_post_meta( $post->ID, '_psi_dpd_ketua', true );
    $phone   = get_post_meta( $post->ID, '_psi_dpd_phone', true );
    $email   = get_post_meta( $post->ID, '_psi_dpd_email', true );
    $address = get_post_meta( $post->ID, '_psi_dpd_address', true );
    $members = get_post_meta( $post->ID, '_psi_dpd_members', true );
    ?>
    <div class="psi-metabox" style="max-width:600px;">
        <p><label for="psi_dpd_ketua"><strong>Nama Ketua DPD</strong></label><br>
        <input type="text" id="psi_dpd_ketua" name="psi_dpd_ketua" value="<?php echo esc_attr( $ketua ); ?>" style="width:100%;"></p>
        <p><label for="psi_dpd_phone"><strong>No. Telepon</strong></label><br>
        <input type="tel" id="psi_dpd_phone" name="psi_dpd_phone" value="<?php echo esc_attr( $phone ); ?>" style="width:100%;"></p>
        <p><label for="psi_dpd_email"><strong>Email</strong></label><br>
        <input type="email" id="psi_dpd_email" name="psi_dpd_email" value="<?php echo esc_attr( $email ); ?>" style="width:100%;"></p>
        <p><label for="psi_dpd_address"><strong>Alamat Kantor</strong></label><br>
        <textarea id="psi_dpd_address" name="psi_dpd_address" rows="3" style="width:100%;"><?php echo esc_textarea( $address ); ?></textarea></p>
        <p><label for="psi_dpd_members"><strong>Jumlah Anggota</strong></label><br>
        <input type="number" id="psi_dpd_members" name="psi_dpd_members" value="<?php echo esc_attr( $members ?: 0 ); ?>" min="0" style="width:100px;"></p>
    </div>
    <?php
}
add_action( 'save_post', 'psi_save_dpd_meta' );
function psi_save_dpd_meta( $post_id ): void {
    if ( ! isset( $_POST['psi_dpd_nonce_field'] ) || ! wp_verify_nonce( $_POST['psi_dpd_nonce_field'], 'psi_dpd_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $map = [
        'psi_dpd_ketua'   => '_psi_dpd_ketua',
        'psi_dpd_phone'   => '_psi_dpd_phone',
        'psi_dpd_email'   => '_psi_dpd_email',
        'psi_dpd_address' => '_psi_dpd_address',
        'psi_dpd_members' => '_psi_dpd_members',
    ];
    foreach ( $map as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
}

/* ── Video Meta Box ───────────────────────────────────────── */
add_action( 'add_meta_boxes', 'psi_video_metabox' );
function psi_video_metabox(): void {
    add_meta_box( 'psi_video_details', esc_html__( 'Detail Video', 'psi-papeng' ), 'psi_video_metabox_cb', 'psi_video', 'normal', 'high' );
}
function psi_video_metabox_cb( $post ): void {
    wp_nonce_field( 'psi_vid_nonce', 'psi_vid_nonce_field' );
    $youtube = get_post_meta( $post->ID, '_psi_video_youtube', true );
    $vimeo   = get_post_meta( $post->ID, '_psi_video_vimeo', true );
    ?>
    <div class="psi-metabox" style="max-width:600px;">
        <p><label for="psi_video_youtube"><strong>YouTube Embed URL</strong></label><br>
        <input type="url" id="psi_video_youtube" name="psi_video_youtube" value="<?php echo esc_attr( $youtube ); ?>" placeholder="https://www.youtube.com/embed/VIDEO_ID" style="width:100%;"></p>
        <p><label for="psi_video_vimeo"><strong>Vimeo Embed URL (opsional)</strong></label><br>
        <input type="url" id="psi_video_vimeo" name="psi_video_vimeo" value="<?php echo esc_attr( $vimeo ); ?>" placeholder="https://player.vimeo.com/video/VIDEO_ID" style="width:100%;"></p>
    </div>
    <?php
}
add_action( 'save_post', 'psi_save_video_meta' );
function psi_save_video_meta( $post_id ): void {
    if ( ! isset( $_POST['psi_vid_nonce_field'] ) || ! wp_verify_nonce( $_POST['psi_vid_nonce_field'], 'psi_vid_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( isset( $_POST['psi_video_youtube'] ) ) {
        update_post_meta( $post_id, '_psi_video_youtube', esc_url_raw( wp_unslash( $_POST['psi_video_youtube'] ) ) );
    }
    if ( isset( $_POST['psi_video_vimeo'] ) ) {
        update_post_meta( $post_id, '_psi_video_vimeo', esc_url_raw( wp_unslash( $_POST['psi_video_vimeo'] ) ) );
    }
}

/* ── Division Meta Box ────────────────────────────────────── */
add_action( 'add_meta_boxes', 'psi_division_metabox' );
function psi_division_metabox(): void {
    add_meta_box( 'psi_division_details', esc_html__( 'Detail Bidang', 'psi-papeng' ), 'psi_division_metabox_cb', 'psi_division', 'normal', 'high' );
}
function psi_division_metabox_cb( $post ): void {
    wp_nonce_field( 'psi_div_nonce', 'psi_div_nonce_field' );
    $head_name  = get_post_meta( $post->ID, '_psi_div_head', true );
    $head_title = get_post_meta( $post->ID, '_psi_div_head_title', true );
    $order      = get_post_meta( $post->ID, '_psi_div_order', true );
    ?>
    <div class="psi-metabox" style="max-width:600px;">
        <p><label for="psi_div_head"><strong>Nama Kepala Bidang</strong></label><br>
        <input type="text" id="psi_div_head" name="psi_div_head" value="<?php echo esc_attr( $head_name ); ?>" style="width:100%;"></p>
        <p><label for="psi_div_head_title"><strong>Jabatan Lengkap</strong></label><br>
        <input type="text" id="psi_div_head_title" name="psi_div_head_title" value="<?php echo esc_attr( $head_title ); ?>" style="width:100%;"></p>
        <p><label for="psi_div_order"><strong>Urutan</strong></label><br>
        <input type="number" id="psi_div_order" name="psi_div_order" value="<?php echo esc_attr( $order ?: 0 ); ?>" min="0" style="width:100px;"></p>
    </div>
    <?php
}
add_action( 'save_post', 'psi_save_division_meta' );
function psi_save_division_meta( $post_id ): void {
    if ( ! isset( $_POST['psi_div_nonce_field'] ) || ! wp_verify_nonce( $_POST['psi_div_nonce_field'], 'psi_div_nonce' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $map = [
        'psi_div_head'       => '_psi_div_head',
        'psi_div_head_title' => '_psi_div_head_title',
        'psi_div_order'      => '_psi_div_order',
    ];
    foreach ( $map as $field => $meta_key ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
}
