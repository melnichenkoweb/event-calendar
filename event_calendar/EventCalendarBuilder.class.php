<?php

class EventCalendarBuilder {

	protected static $slugs = array(
		'post_type' => 'events',
		'taxonomy' => array(
			'city'      => 'wpex_taxonomy_city',
			'category'  => 'wpex_taxonomy_category',
		)
	);


	public function __construct()
	{

	}


	static function plugin_activation()
	{
		return true;
	}

	static function plugin_deactivation()
	{
		return true;
	}

	public function init()
	{
		$this->register_post_type();
		$this->register_taxonomies();

		if ( is_admin() ) {
			// Add settings metabox to event
			add_filter( 'wpex_main_metaboxes_post_types', array( $this, 'meta_array' ), 20 );

			// events_columns_head events_columns_content
			add_filter('manage_events_posts_columns', array( $this, 'events_columns_head'));
			add_action('manage_events_posts_custom_column', array($this, 'events_columns_content'), 10, 2);
			// custom columns sortable
			add_action( 'pre_get_posts', array($this, 'custom_columns_events_posts_orderby'));
			add_filter('manage_edit-events_sortable_columns', array($this, 'custom_columns_events_posts_sortable') );

			add_action('wp_ajax_set_event_to_portfolio', [$this, 'set_event_to_portfolio']);

		}

		add_action('save_post', [$this, 'save_thumbnail_metabox'], 1, 2);
	}

	public static function get_post_type_slug()
	{
		return self::$slugs['post_type'];
	}

	public static function get_taxonomy_slug($arg)
	{
		return self::$slugs['taxonomy'][$arg];
	}

	public static function meta_array( $types ) {
		$types[] = self::get_post_type_slug();
		return $types;
	}

	public function register_post_type()
	{
		register_post_type( self::get_post_type_slug(),
			array(
				'labels'          => array(
					'name'               => __( 'Events' ),
					'singular_name'      => __( 'Event' ),
					'add_new'            => __( 'Add New' ),
					'add_new_item'       => __( 'Add New Event' ),
					'edit'               => __( 'Edit' ),
					'edit_item'          => __( 'Edit Event' ),
					'new_item'           => __( 'New Event' ),
					'view'               => __( 'View' ),
					'view_item'          => __( 'View Event' ),
					'search_items'       => __( 'Search Event' ),
					'not_found'          => __( 'No Events found' ),
					'not_found_in_trash' => __( 'No Events found in Trash' ),
					'parent'             => __( 'Parent Event' )
				),
				'public'          => true,
				'capability_type' => 'post',
				'has_archive'     => false,
				'menu_icon'       => 'dashicons-calendar',
				'menu_position'   => 20,
				'rewrite'         => array( 'slug' => 'event', 'with_front' => false ),
				'supports'        => array(
					'title',
					'editor',
					'excerpt',
					'thumbnail',
					'comments',
					'custom-fields',
					'revisions',
					'author',
					'page-attributes',
				),
				'register_meta_box_cb' => array( $this, 'add_event_metaboxes')


			)
		);
	}

	public function register_taxonomies()
	{
		$args = apply_filters( self::get_taxonomy_slug('city'), array(
			'labels'            => array(
				'name'               => __( 'Event cities', 'total' ),
				'singular_name'      => __( 'Event city', 'total' ),
				'add_new'            => __( 'Add New', 'total' ),
				'add_new_item'       => __( 'Add New Item', 'total' ),
				'edit_item'          => __( 'Edit Item', 'total' ),
				'new_item'           => __( 'Add New Item', 'total' ),
				'view_item'          => __( 'View Item', 'total' ),
				'search_items'       => __( 'Search Items', 'total' ),
				'not_found'          => __( 'No Items Found', 'total' ),
				'not_found_in_trash' => __( 'No Items Found In Trash', 'total' )
			),
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'event-city', 'with_front' => false ),
			'query_var'         => true
		) );
		register_taxonomy( 'event_city', array( self::get_post_type_slug() ), $args );

		$args = apply_filters( self::get_taxonomy_slug('category'), array(
			'labels'            => array(
				'name'               => __( 'Event categories', 'total' ),
				'singular_name'      => __( 'Event category', 'total' ),
				'add_new'            => __( 'Add New', 'total' ),
				'add_new_item'       => __( 'Add New Item', 'total' ),
				'edit_item'          => __( 'Edit Item', 'total' ),
				'new_item'           => __( 'Add New Item', 'total' ),
				'view_item'          => __( 'View Item', 'total' ),
				'search_items'       => __( 'Search Items', 'total' ),
				'not_found'          => __( 'No Items Found', 'total' ),
				'not_found_in_trash' => __( 'No Items Found In Trash', 'total' )
			),
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			//'show_in_rest'      => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'event-category', 'with_front' => false ),
			'query_var'         => true
		) );
		register_taxonomy( 'event_category', array( self::get_post_type_slug() ), $args );


	}



	public function add_event_metaboxes()
	{
		add_meta_box('wp_event_date', 'Event Details', [$this, 'wp_event_date_view'], self::get_post_type_slug(), 'normal', 'high');

	}


	public function wp_event_date_view()
	{
		global $post;
		$event_date_start = get_post_meta($post->ID, '_event_date_start', true);
		$event_date_finish = get_post_meta($post->ID, '_event_date_finish', true);
		$event_url = get_post_meta($post->ID, '_event_url', true);
		$event_ticket_url = get_post_meta($post->ID, '_event_ticket_url', true);
		$event_cost = get_post_meta($post->ID, '_event_cost', true);
		$event_location_place_title = get_post_meta($post->ID, '_event_location_place_title', true);
		$event_location_address = get_post_meta($post->ID, '_event_location_address', true);
		$event_facebook_link = get_post_meta($post->ID, '_event_facebook_link', true);
		$event_instagram_link = get_post_meta($post->ID, '_event_instagram_link', true);
		$is_event_in_portfolio = get_post_meta($post->ID, '_is_event_in_portfolio', true);

		$html = '<div class="container-fluid">'.
			        '<div class="form-group row">'.
				        '<div class="col">'.
		                    '<p class="meta-options mb-0">'.
					        '<label class="">'.
					        '<input type="checkbox" name="_is_event_in_portfolio" value="on" '.
		                        ($is_event_in_portfolio == 'on' ? 'checked="checked"' : '') . '> Add Event to the Portfolio</label>'.
		                    '</p>'.
				        '</div>'.
			        '</div>'.
			        '<div class="form-group row">'.
				        '<div class="col">'.
					        '<label class="">Event URL</label>'.
					        '<input type="text" name="_event_url" value="'.$event_url.'" class="form-control">'.
				        '</div>'.
			        '</div>'.

			        '<div class="form-group row">'.
				        '<div class="col">'.
					        '<label class="">Tickets URL</label>'.
					        '<input type="text" name="_event_ticket_url" value="'.$event_ticket_url.'" class="form-control">'.
				        '</div>'.
			        '</div>'.

		            '<div class="form-group row">'.
		                '<div class="col">'.
					        '<label class="">Event Start Date</label>'.
		                    '<input type="text" name="_event_date_start" value="'.($event_date_start ? (new DateTime())->setTimestamp($event_date_start)->format('Y-m-d H:i') : '').'" autocomplete="off" class="form-control custom_date">'.
		                '</div>'.
                    '</div>'.

			        '<div class="form-group row">'.
				        '<div class="col">'.
					        '<label class="">Event Finish Date</label>'.
					        '<input type="text" name="_event_date_finish" value="'.($event_date_finish ? (new DateTime())->setTimestamp($event_date_finish)->format('Y-m-d H:i') : '').'" autocomplete="off" class="form-control custom_date">'.
				        '</div>'.
			        '</div>'.

			        '<div class="form-group row">'.
				        '<div class="col">'.
					        '<label class="">Event Cost $:</label>'.
					        '<input type="text" name="_event_cost" value="'.$event_cost.'" class="form-control">'.
				        '</div>'.
			        '</div>'.

					'<div class="form-group row">'.
						'<div class="col">'.
							'<label class="">Location Place Title</label>'.
							'<input type="text" name="_event_location_place_title" value="'.$event_location_place_title.'" class="form-control">'.
						'</div>'.
					'</div>'.
			        '<div class="form-group row">'.
				        '<div class="col">'.
					        '<label class="">Location address</label>'.
					        '<input type="text" name="_event_location_address" value="'.$event_location_address.'" class="form-control">'.
				        '</div>'.
			        '</div>'.

		            '<div class="form-group row">'.
				        '<div class="col">'.
					        '<label class="">Event Facebook Link</label>'.
					        '<input type="text" name="_event_facebook_link" value=\''.$event_facebook_link.'\' class="form-control">'.
				        '</div>'.
			        '</div>'.
			        '<div class="form-group row">'.
				        '<div class="col">'.
					        '<label class="">Event Instagram Link</label>'.
					        '<input type="text" name="_event_instagram_link" value=\''.$event_instagram_link.'\' class="form-control">'.
				        '</div>'.
			        '</div>'.
					'<input type="hidden" name="meta_noncename" id="meta_noncename" value="'.wp_create_nonce(plugin_basename(__FILE__)).'" >'.
                '</div>';
		echo $html;
	}


	public function save_thumbnail_metabox($postId, $post)
	{

		if (!isset($_POST['meta_noncename']) || !wp_verify_nonce($_POST['meta_noncename'], plugin_basename(__FILE__))) {
			return $post->ID;
		}

		if (!current_user_can('edit_post', $post->ID)) return $post->ID;

		$is_event_in_portfolio = isset($_POST['_is_event_in_portfolio']) ? $_POST['_is_event_in_portfolio'] : 'off';
		$this->updateMeta($post->ID, '_is_event_in_portfolio', $is_event_in_portfolio);

		$event_date_start =  isset($_POST['_event_date_start']) ? $_POST['_event_date_start'] : '';
		$this->updateMeta($post->ID, '_event_date_start', $event_date_start ? strtotime($event_date_start) : '');

		$event_date_finish =  isset($_POST['_event_date_finish']) ? $_POST['_event_date_finish'] : '';
		$this->updateMeta($post->ID, '_event_date_finish', $event_date_finish ? strtotime($event_date_finish) : '');

		$event_cost = isset($_POST['_event_cost']) ? $_POST['_event_cost'] : '';
		$this->updateMeta($post->ID, '_event_cost', $event_cost);

		$event_location_address = isset($_POST['_event_location_address']) ? $_POST['_event_location_address'] : '';
		$this->updateMeta($post->ID, '_event_location_address', $event_location_address);

		$event_location_place_title = isset($_POST['_event_location_place_title']) ? $_POST['_event_location_place_title'] : '';
		$this->updateMeta($post->ID, '_event_location_place_title', $event_location_place_title);

		$event_facebook_link = isset($_POST['_event_facebook_link']) ? $_POST['_event_facebook_link'] : '';
		$this->updateMeta($post->ID, '_event_facebook_link', $event_facebook_link);

		$event_instagram_link = isset($_POST['_event_instagram_link']) ? $_POST['_event_instagram_link'] : '';
		$this->updateMeta($post->ID, '_event_instagram_link', $event_instagram_link);

		$event_url = isset($_POST['_event_url']) ? $_POST['_event_url'] : '';
		if (strpos($event_url, "http://") === false && strpos($event_url, "https://") === false) $event_url = 'http://' . $event_url;
		$this->updateMeta($post->ID, '_event_url', $event_url);

		$event_ticket_url = isset($_POST['_event_ticket_url']) ? $_POST['_event_ticket_url'] : '';
		if (strpos($event_ticket_url, "http://") === false && strpos($event_ticket_url, "https://") === false) $event_ticket_url = 'http://' . $event_ticket_url;
		$this->updateMeta($post->ID, '_event_ticket_url', $event_ticket_url);

		return $post->ID;
	}


	public function updateMeta($postId, $key, $value)
	{
		$value = implode(',', (array)$value);
		if (!isset($value) || $value === false) delete_post_meta($postId, $key);
		if (get_post_meta($postId, $key, FALSE)) {
			update_post_meta($postId, $key, $value);
		} else {
			add_post_meta($postId, $key, $value);
		}
	}

	public function events_columns_head($defaults) {
		$defaults['event_in_portfolio'] = 'Listed on the Portfolio';
		$defaults['start_event']  = 'Start Event';
		$defaults['finish_event'] = 'Finish Event';
		return $defaults;
	}


	public function get_event_start_date($post_ID){
		$event_date_start = get_post_meta($post_ID, '_event_date_start', true);
		return $event_date_start ? (new DateTime())->setTimestamp($event_date_start)->format('Y-m-d H:i') : '';
	}

	public function get_event_finish_date($post_ID){
		$event_date_finish = get_post_meta($post_ID, '_event_date_finish', true);
		return $event_date_finish ? (new DateTime())->setTimestamp($event_date_finish)->format('Y-m-d H:i') : '';
	}

	public function get_event_is_in_portfolio($post_ID){
		$is_event_in_portfolio = get_post_meta($post_ID, '_is_event_in_portfolio', true);
		return $is_event_in_portfolio == 'on' ? true  : false;
	}

	public function events_columns_content($column_name, $post_ID) {

		if ($column_name == 'event_in_portfolio') {
			$is_event_in_portfolio = $this->get_event_is_in_portfolio($post_ID);
			if ($is_event_in_portfolio) {
				echo '<input type="checkbox" name="_is_event_in_portfolio" value="'.$post_ID.'" checked="checked">';
			} else {
				echo '<input type="checkbox" name="_is_event_in_portfolio" value="'.$post_ID.'">';
			}
		}

		if ($column_name == 'start_event') {
			$event_start_date = $this->get_event_start_date($post_ID);
			if ($event_start_date) {
				echo $event_start_date;
			} else {
				echo 'No date';
			}
		}
		if ($column_name == 'finish_event') {
			$event_finish_date = $this->get_event_finish_date($post_ID);
			if ($event_finish_date) {
				echo $event_finish_date;
			} else {
				echo 'No date';
			}
		}
	}

	public function custom_columns_events_posts_sortable( $columns ) {

		$columns['event_in_portfolio']  = 'event_in_portfolio';
		$columns['start_event']  = 'start_event';
		$columns['finish_event'] = 'finish_event';

		//To make a column 'un-sortable' remove it from the array
		//unset($columns['start_event']);
		//unset($columns['finish_event']);

		return $columns;
	}

	public function custom_columns_events_posts_orderby( $query ) {
		$orderby = $query->get( 'orderby');

		if( 'event_in_portfolio' == $orderby ) {
			$query->set('meta_key','_is_event_in_portfolio');
			$query->set('orderby', 'meta_value_num');
		}

		if( 'start_event' == $orderby ) {
			$query->set('meta_key','_event_date_start');
			$query->set('orderby', 'meta_value_num');
		}

		if( 'finish_event' == $orderby ) {
			$query->set('meta_key','_event_date_finish');
			$query->set('orderby', 'meta_value_num');
		}

	}

	public function set_event_to_portfolio(){
		$post_ID = $_POST['post_id'];
		$is_event_in_portfolio = $_POST['is_event_in_portfolio'];
		$this->updateMeta($post_ID, '_is_event_in_portfolio', $is_event_in_portfolio);
		echo 'updated';
		wp_die();
	}


}