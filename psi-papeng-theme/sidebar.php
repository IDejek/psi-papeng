<?php
/**
 * Sidebar
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
if ( ! is_active_sidebar( 'sidebar-main' ) ) return;
?>
<aside class="space-y-6">
    <?php dynamic_sidebar( 'sidebar-main' ); ?>
</aside>
