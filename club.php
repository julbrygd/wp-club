<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://conrad.pics
 * @since             1.0.0
 * @package           Club
 *
 * @wordpress-plugin
 * Plugin Name:       Club
 * Plugin URI:        https://conrad.pics/project/wp-club
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Stephan Conrad
 * Author URI:        https://conrad.pics
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       club
 * Domain Path:       /languages
 */

require_once dirname(__FILE__).'/vendor/autoload.php';


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}


register_activation_hook(__FILE__, array('Club\Club', 'install'));
register_deactivation_hook(__FILE__, array('Club\Club', 'uninstall'));

Club\Club::run();