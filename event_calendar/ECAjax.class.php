<?php

class TCAjax
{
	public static $postType = 'events';

	public function __construct()
	{

	}

	public function init()
	{
		$this->registerAjax();
	}

	public function registerAjax()
	{
		add_action('wp_ajax_tc_get_events_by_filter', [$this, 'getEventsByFilter']);
		add_action( 'wp_ajax_nopriv_tc_get_events_by_filter', [$this, 'getEventsByFilter']);
	}

	public function getEventsByFilter()
	{

		$data = [];
		parse_str($_POST['data'], $data);

		$city = isset($data['event_city']) ? $data['event_city'] : '';
		$date_from = isset($data['_event_date_start']) ? $data['_event_date_start'] : '';
		$date_to = isset($data['_event_date_finish']) ? $data['_event_date_finish'] : '';
		$category = isset($data['event_category']) ? $data['event_category'] : '';

		$html = '';
		$args = [
			'posts_per_page' => -1,
			'post_type' => self::$postType,
			'post_status' => 'publish',
			'meta_query' => [],
			'tax_query' => [],
		];

		if($date_from){
			$args['meta_query'][] = [
				'key' => '_event_date_start',
				'value' => $date_from,
				'compare' => '>='
			];
		}
		if($date_to){
			$args['meta_query'][] = [
				'key' => '_event_date_finish',
				'value' => $date_to,
				'compare' => '<='
			];
		}
		if($city){
			$args['tax_query'][] = [
				'taxonomy' => 'event_city',
				'field'    => 'term_id',
				'terms' => [$city],
				'operator' => 'IN',
			];
		}
		if($category){
			$args['tax_query'][] = [
				'taxonomy' => 'event_category',
				'field'    => 'term_id',
				'terms' => [$category],
				'operator' => 'IN',
			];
		}


		$query = new WP_Query($args);
		if($query->have_posts()){
			while ($query->have_posts()){
				$query->the_post();

				$event_date_start = get_post_meta(get_the_ID(), '_event_date_start', true);
				$event_date_finish = get_post_meta(get_the_ID(), '_event_date_finish', true);
				$event_url = get_post_meta(get_the_ID(), '_event_url', true);
				$event_cost = get_post_meta(get_the_ID(), '_event_cost', true);
				$event_location_address = get_post_meta(get_the_ID(), '_event_location_address', true);

				$post_arr = [];
				$post_arr['image'] = has_post_thumbnail(get_the_ID()) ?
					wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail') : '';
				$post_arr['title'] = get_the_title();
				$post_arr['excerpt'] = has_excerpt() ? get_the_excerpt() : '';
				$post_arr['event_date_start'] = $event_date_start ? $event_date_start : '';
				$post_arr['event_url'] = $event_url ? $event_url : '';
				$post_arr['event_cost'] = $event_cost ? $event_cost : '';
				$post_arr['event_location_address'] = $event_location_address ? $event_location_address : '';

				$html .= LTTmplToVar('templates/partials/event_item.tmpl.php', $post_arr);


			}
		}




	}
}