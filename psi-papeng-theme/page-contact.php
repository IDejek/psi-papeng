<?php
/**
 * Template Name: Kontak
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
 $contact_addr = get_theme_mod( 'psi_contact_address', '' );
 $contact_phone = get_theme_mod( 'psi_contact_phone', '' );
 $contact_email = get_theme_mod( 'psi_contact_email', 'info@psipapeng.id' );
 $contact_wa    = get_theme_mod( 'psi_contact_whatsapp', '6282267218125' );
 $contact_map   = get_theme_mod( 'psi_contact_map', '' );
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;"><?php the_title(); ?></h1>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Hubungi Kami</h2>
                <div class="space-y-6">
                    <?php if ( $contact_addr ) : ?>
                    <div class
