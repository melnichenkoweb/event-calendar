<?php

/**
 * Shortcode attributes
 * @var $upcoming_page
 * @var $label_above_upcoming_button
 * @var $upcoming_button_text
 * @var $css_animation'
 * @var $el_id
 * @var $el_class
 * @var $css
 */
$atts = vc_map_get_attributes( 'paste_taste_calendar', $atts );

extract( $atts );

$event_city_id = isset( $_GET['event_city_id'] ) ? $_GET['event_city_id'] : '';
$date_from     = isset( $_GET['_event_date_start'] ) ? $_GET['_event_date_start'] : '';
$date_to       = isset( $_GET['_event_date_finish'] ) ? $_GET['_event_date_finish'] : '';
$category_id   = isset( $_GET['event_category_id'] ) ? $_GET['event_category_id'] : '';


$sort_by = isset( $_GET['sort_by'] ) ? $_GET['sort_by'] : '';

if ( get_query_var( 'paged' ) ) {
	$paged = get_query_var( 'paged' );
} elseif ( get_query_var( 'page' ) ) {
	$paged = get_query_var( 'page' );
} else {
	$paged = 1;
}

$html       = '';
$pagination = '';
$args       = [
	'posts_per_page' => -1,
	//'paged'          => $paged,
	'post_type'      => self::$postType,
	'post_status'    => 'publish',
	'meta_query'     => [
		[
			'key'     => '_event_date_start',
			'value'   => ( new DateTime() )->getTimestamp(),
			'compare' => '<',
		]
	],
	'tax_query'      => [],
    'orderby' => ['meta_value_num' => 'DESC'],
    'meta_key' => '_event_date_start',
];


if ( $event_city_id ) {
	$args['tax_query'][] = [
		'taxonomy' => 'event_city',
		'field'    => 'term_id',
		'terms'    => [ $event_city_id ],
		'operator' => 'IN',
	];
}
if ( $category_id ) {
	$args['tax_query'][] = [
		'taxonomy' => 'event_category',
		'field'    => 'term_id',
		'terms'    => [ $category_id ],
		'operator' => 'IN',
	];
}


$cities           = get_terms( [
	'taxonomy' => 'event_city',
	'orderby' => 'name',
	'order' => 'ASC',
	'hide_empty' => false ] );
$event_categories = get_terms( [
	'taxonomy' => 'event_category',
	'orderby' => 'name',
	'order' => 'ASC',
	'hide_empty' => false
] );

$query = new WP_Query( $args );

if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();

		$event_date_start           = get_post_meta( get_the_ID(), '_event_date_start', true );
		$event_date_finish          = get_post_meta( get_the_ID(), '_event_date_finish', true );
		$event_url                  = get_post_meta( get_the_ID(), '_event_url', true );
		$event_cost                 = get_post_meta( get_the_ID(), '_event_cost', true );
		$event_location_place_title = get_post_meta( get_the_ID(), '_event_location_place_title', true );
		$event_location_address     = get_post_meta( get_the_ID(), '_event_location_address', true );
		$event_ticket_url           = get_post_meta( get_the_ID(), '_event_ticket_url', true );

		$post_arr                               = [];
		$post_arr['image']                      = has_post_thumbnail( get_the_ID() ) ?
			wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
		$post_arr['title']                      = get_the_title();
		$post_arr['excerpt']                    = has_excerpt() ? get_the_excerpt() : '';
		$post_arr['event_date_start']           = $event_date_start ? $event_date_start : '';
		$post_arr['event_date_finish']          = $event_date_finish ? $event_date_finish : '';
		$post_arr['event_url']                  = $event_url ? $event_url : '';
		$post_arr['event_cost']                 = $event_cost ? $event_cost : '';
		$post_arr['event_location_place_title'] = $event_location_place_title ? $event_location_place_title : '';
		$post_arr['event_location_address']     = $event_location_address ? $event_location_address : '';
		$post_arr['event_ticket_url']           = $event_ticket_url ? $event_ticket_url : '';
		$post_arr['permalink']                  = get_post_permalink( get_the_ID() );
		$post_arr['event_city']                 = wp_get_post_terms( get_the_ID(), 'event_city' )[0]->name;


		$html .= LTTmplToVar( __DIR__ . DIRECTORY_SEPARATOR . '../partials/past_event_item.tmpl.php', $post_arr );

	}
	$total_pages = $query->max_num_pages;
	if ( $total_pages > 1 ) {

		$pagination = paginate_links( array(
			'format'    => 'page/%#%',
			'current'   => $paged,
			'total'     => $total_pages,
			'show_all'  => false,
			'end_size'  => 1,
			'mid_size'  => 2,
			'prev_next' => true,
			'prev_text' => '<i class="fa fa-angle-left"></i>',
			'next_text' => '<i class="fa fa-angle-right"></i>',
			'type'      => 'list',
		) );
	}
} else {
	$html .= "<p>There are no events yet.</p>";
}



$wrap_classes = 'taste-calendar';

if ( $el_class ) {
	$wrap_classes .= ' ' . vcex_get_extra_class( $el_class );
}
if ( $css_animation && 'none' != $css_animation ) {
	$wrap_classes .= ' ' . vcex_get_css_animation( $css_animation );
}

$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'taste_calendar', $atts );

$wrapper_attributes = [];
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

?>


<div <?php echo implode( ' ', $wrapper_attributes ); ?> class="<?php echo esc_attr( trim( $wrap_classes ) ); ?>">

	<form action="<?php echo get_bloginfo( 'url' ); ?>/archive" name="event-filter"
	      method="get" class="event-filter" id="eventFilter">
        <div class="row">
            <div class="col-12">
                <label class="sr-only">Filters</label>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group mb-3">
                                            <label for="event_city_id" class="align-self-start sr-only">Choose City</label>
                                            <select name="event_city_id" id="event_city_id" class="custom-select filter-options-select">
                                                <option value="">City</option>
												<?php if ( count( $cities ) > 0 ): ?>
													<?php foreach ( $cities as $city ): ?>
                                                        <option value="<?php echo $city->term_id; ?>"
															<?php echo intval( $event_city_id ) == $city->term_id ? ' selected="selected" ' : ''; ?>><?php echo $city->name; ?></option>
													<?php endforeach; ?>
												<?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group mb-3">
                                            <label for="event_category_id" class="align-self-start sr-only">Category</label>
                                            <select name="event_category_id" id="event_category_id"
                                                    class="custom-select filter-options-select">
                                                <option value="" selected>Category</option>
												<?php if ( count( $event_categories ) > 0 ): ?>
													<?php foreach ( $event_categories as $event_category ): ?>
                                                        <option value="<?php echo $event_category->term_id; ?>"
															<?php echo intval( $category_id ) == $event_category->term_id ? ' selected="selected" ' : ''; ?>><?php echo $event_category->name; ?></option>
													<?php endforeach; ?>
												<?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="row">
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="align-self-start sr-only">Search</label>
                                            <button type="submit" class="btn events-btn get-events">Search</button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="align-self-start sr-only">Reset</label>
                                            <button type="button" class="btn events-btn reset-events">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


	</form>


	<div class="row">
		<div class="col-12 col-sm-12 col-lg-12 my-5"><?php echo $html; ?></div>
	</div>

	<?php if ( $pagination ): ?>
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-12 my-5 pagination-wrapper text-center"><?php echo $pagination; ?></div>
        </div>
	<?php endif; ?>


    <div class="row">
        <?php if($label_above_upcoming_button):?>
        <div class="col-12">
            <p class="past-event-header"><?php echo $label_above_upcoming_button;?></p>
        </div>
        <?php endif;?>
        <?php if($upcoming_button_text):?>
        <div class="col-12 mb-3">
            <a href="<?php echo $upcoming_page;?>" class="btn btn-primary btn-lg btn-block past-event-button"><?php echo $upcoming_button_text;?></a>
        </div>
        <?php endif;?>
    </div>



</div>




