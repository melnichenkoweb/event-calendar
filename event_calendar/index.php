<?php
/**
 * Plugin Name: Taste USA Calendar
 * Description: Taste USA Calendar
 * Author: Developer
 * Author URI:
 * Plugin URI:
 * Version: 1.0
 * License: GPL2
 * =======================================================================
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

$TCPluginDir = __DIR__.DIRECTORY_SEPARATOR;
$TCPluginUrl = plugin_dir_url(__FILE__);

require $TCPluginDir.'EventCalendarBuilder.class.php';
require_once $TCPluginDir . 'shortcodes/TCGridShortcode.class.php';
require_once $TCPluginDir . 'shortcodes/EventPortfolio.class.php';
require_once $TCPluginAjax . 'ECAjax.class.php';

$TCBuilderClass = new EventCalendarBuilder();
$TCGridShortcode = new TCGridShortcode();
$TCEventPortfolio = new EventPortfolio();
$TCPluginAjax = new ECAjax();

function calendarScriptsAdmin(){
	wp_register_script('admin_tc_datatimepicker_script',plugin_dir_url(__FILE__).'assets/libs/datetimepicker/build/jquery.datetimepicker.full.min.js', ['jquery'], false, true);
	wp_register_script('admin_tc_calendar_script',plugin_dir_url(__FILE__).'assets/js/admin_taste_calendar.js', ['jquery'], false, true);

	wp_enqueue_script('admin_tc_datatimepicker_script');
	wp_enqueue_script('admin_tc_calendar_script');
}
add_action('admin_enqueue_scripts', 'calendarScriptsAdmin');

function calendarStylesAdmin(){
	wp_register_style('admin_tc_calendar_styles',plugin_dir_url(__FILE__).'assets/css/admin_taste_styles.css',[],false,	'all'	);
	wp_enqueue_style('admin_tc_calendar_styles');
}
add_action('admin_head', 'calendarStylesAdmin');

function calendarScripts(){
	wp_register_script('tc_datatimepicker_script',plugin_dir_url(__FILE__).'assets/libs/datetimepicker/build/jquery.datetimepicker.full.min.js', ['jquery'], false, true);
	wp_register_script('multislider_script',plugin_dir_url(__FILE__).'assets/libs/multislider/js/multislider.js', ['jquery'],false,true);
	wp_register_script('tc_calendar_script',plugin_dir_url(__FILE__).'assets/js/taste_calendar.js', ['jquery', 'jquery-form'],false,true);

	wp_enqueue_script('tc_datatimepicker_script');
	wp_enqueue_script('multislider_script');
	wp_enqueue_script('tc_calendar_script');

	wp_localize_script('tc_calendar_script', 'ajaxurl', array(
		'ajaxurl' => admin_url('admin-ajax.php')
	));
}
add_action('wp_enqueue_scripts', 'calendarScripts');

function calendarStyles(){
	wp_register_style('tc_timepicker_styles', plugin_dir_url(__FILE__).'assets/libs/period-picker/jquery.timepicker.css',[],false,	'all');
	wp_register_style('multislider_styles',plugin_dir_url(__FILE__).'assets/libs/multislider/css/custom.css',[],false,	'all'	);
	wp_register_style('tc_calendar_styles',plugin_dir_url(__FILE__).'assets/css/taste_styles.css',[],false,	'all'	);

	wp_enqueue_style('tc_timepicker_styles');
	wp_enqueue_style('multislider_styles');
	wp_enqueue_style('tc_calendar_styles');

}
add_action('wp_head', 'calendarStyles');

register_activation_hook( __FILE__, array( 'EventCalendarBuilder', 'plugin_activation' ) );

register_deactivation_hook( __FILE__, array( 'EventCalendarBuilder', 'plugin_deactivation' ) );

function calendar_init(){
	global $TCBuilderClass;
	global $TCPluginAjax;

	$TCBuilderClass->init();
	$TCPluginAjax->init();
}
add_action( 'init', 'calendar_init' );

function LTTmplToVar($file, $args=[], $extract=false){
	global $TBPluginDir;
	if($extract)extract($args);
	ob_start();
	require($TBPluginDir.$file);
	return ob_get_clean();
}
