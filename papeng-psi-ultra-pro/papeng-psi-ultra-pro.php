<?php
/**
 * Plugin Name: PSI Papeng Premium
 * Plugin URI: https://psipapeng.id
 * Description: Plugin premium untuk DPW PSI Papua Pegunungan
 * Version: 1.0.0
 * Author: Iqbal Tombinawa
 * Author URI: https://psipapeng.id
 * License: GPL v2 or later
 * Text Domain: psi-papeng
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

defined( 'ABSPATH' ) || exit;

define( 'PSI_PLUGIN_VERSION', '1.0.0' );
define( 'PSI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PSI_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'PSI_PLUGIN_FILE', __FILE__ );

spl_autoload_register( function( $class ) {
    $prefix = 'PSI_Papeng_';
    if ( strpos( $class, $prefix ) !== 0 ) return;
    $file = PSI_PLUGIN_DIR . 'includes/class-' . strtolower( str_replace( '_', '-', substr( $class, strlen( $prefix ) ) ) ) . '.php';
    if ( file_exists( $file ) ) require $file;
});

register_activation_hook( __FILE__, [ 'PSI_Papeng_Activator', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'PSI_Papeng_Activator', 'deactivate' ] );

add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'psi-papeng', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    PSI_Papeng_Init::get_instance();
});
