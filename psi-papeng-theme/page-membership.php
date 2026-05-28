<?php
/**
 * Template Name: Keanggotaan
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
 $member_url = get_theme_mod( 'psi_member_url', 'https://psi.id/menjadi-anggota' );
wp_redirect( esc_url( $member_url ), 301 );
exit;
