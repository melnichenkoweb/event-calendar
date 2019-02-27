<?php

if( !class_exists('TCGridShortcode') ) {
	class TCGridShortcode {
		public static $postType = 'events';
		public static $defaults = [];


		public function __construct() {
			add_shortcode( 'taste_calendar', array( $this, 'render' ) );
			vc_lean_map( 'taste_calendar', array( $this, 'map' ) );

			add_shortcode( 'paste_taste_calendar', array( $this, 'past_render' ) );
			vc_lean_map( 'paste_taste_calendar', array( $this, 'past_map' ) );
		}


		public function render( $atts, $content = null) {
			ob_start();
			include  __DIR__.DIRECTORY_SEPARATOR.'templates/short_code/event_list.tmpl.php';
			return ob_get_clean();
		}

		public function past_render( $atts, $content = null) {
			ob_start();
			include  __DIR__.DIRECTORY_SEPARATOR.'templates/short_code/past_event_list.tmpl.php';
			return ob_get_clean();
		}


		public function map() {
			return array(
				'name' => __( 'Taste Calendar', 'total' ),
				'base' => 'taste_calendar',
				'description' => __( 'Taste Calendar', 'total' ),
				'icon' => 'vcex-icon fa fa-calendar',
				'php_class_name' => 'TCGridShortcode',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Archive page URL', 'js_composer' ),
						'param_name' => 'archive_page',
						'description' => __( 'Enter archive page URL.', 'js_composer' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Label above archive button', 'js_composer' ),
						'param_name' => 'label_above_archive_button',
						'description' => __( 'Enter label above archive button.', 'js_composer' ),
						'value' => "Click Below to See How Much Fun We've Had in the Past",
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Archive button text', 'js_composer' ),
						'param_name' => 'archive_button_text',
						'description' => __( 'Enter label above archive button.', 'js_composer' ),
						'value' => "PAST TASTEUSA FOOD AND DRINK FESTIVALS",
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'el_id',
						'heading' => __( 'Element ID', 'js_composer' ),
						'param_name' => 'el_id',
						'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ),
							'http://www.w3schools.com/tags/att_global_id.asp' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'js_composer' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS box', 'js_composer' ),
						'param_name' => 'css',
						'group' => __( 'Design Options', 'js_composer' ),
					),

				),
			);
		}


		public function past_map() {
			return array(
				'name' => __( 'Paste Taste Calendar', 'total' ),
				'base' => 'past_taste_calendar',
				'description' => __( 'Paste Taste Calendar', 'total' ),
				'icon' => 'vcex-icon fa fa-calendar',
				'php_class_name' => 'TCGridShortcode',
				'params' => array(
					array(
						'type' => 'textfield',
						'heading' => __( 'Upcoming events page URL', 'js_composer' ),
						'param_name' => 'upcoming_page',
						'description' => __( 'Enter Upcoming events page URL.', 'js_composer' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Label above archive button', 'js_composer' ),
						'param_name' => 'label_above_upcoming_button',
						'description' => __( 'Enter label above archive button.', 'js_composer' ),
						'value' => "Click Below to See How Much Fun We'll Be Having in the Future!",
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Archive button text', 'js_composer' ),
						'param_name' => 'upcoming_button_text',
						'description' => __( 'Enter label above archive button.', 'js_composer' ),
						'value' => "UPCOMING TASTEUSA FOOD AND DRINK FESTIVALS",
					),
					vcex_vc_map_add_css_animation(),
					array(
						'type' => 'el_id',
						'heading' => __( 'Element ID', 'js_composer' ),
						'param_name' => 'el_id',
						'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ),
							'http://www.w3schools.com/tags/att_global_id.asp' ),
					),
					array(
						'type' => 'textfield',
						'heading' => __( 'Extra class name', 'js_composer' ),
						'param_name' => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => __( 'CSS box', 'js_composer' ),
						'param_name' => 'css',
						'group' => __( 'Design Options', 'js_composer' ),
					),
				),
			);
		}
	}
}
