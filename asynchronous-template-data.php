<?php
/**
 * Plugin Name: Asynchronous Template Data
 * Plugin URI:  https://github.com/martinsluters/asynchronous-template-data
 * Description: Inject data in templates asynchronously.
 * Version:     0.1
 * Author:      Martins Luters
 * Author URI:  https://github.com/martinsluters
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: asynchronous-template-data
 *
 * @package     AsynchronousTemplateData
 * @author      Martins Luters
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 */

declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

defined( 'ABSPATH' ) || die();

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require dirname( __FILE__ ) . '/vendor/autoload.php';
}

Bootstrap::getInstance()->init();
