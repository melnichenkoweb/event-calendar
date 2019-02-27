<?php

if( !class_exists('EventPortfolio') ) {
	class EventPortfolio {
		public static $postType = 'events';
		public static $defaults = [];


		public function __construct() {
			add_shortcode( 'event_portfolio', array( $this, 'render' ) );
			vc_lean_map( 'event_portfolio', array( $this, 'map' ) );

		}


		public function render( $atts, $content = null) {
			ob_start();
			include  __DIR__.DIRECTORY_SEPARATOR.'templates/short_code/event_portfolio.tmpl.php';
			return ob_get_clean();
		}

		function event_portfolio_grid_columns() {
			return apply_filters( 'wpex_grid_columns', array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
			) );
		}


		public function map() {
			return array(
				'name' => __( 'Event Portfolio Slider', 'total' ),
				'base' => 'event_portfolio',
				'description' => __( 'Event Portfolio Slider', 'total' ),
				'icon' => 'vcex-icon fa fa-calendar',
				'php_class_name' => 'EventPortfolio',
				'params' => array(
					array(
						'type' => 'dropdown',
						'value' => array_flip( $this->event_portfolio_grid_columns() ),
						'heading' => __( 'Number of items per row', 'js_composer' ),
						'param_name' => 'items_per_row',
						'description' => __( 'Enter number of items per row.', 'js_composer' ),
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
