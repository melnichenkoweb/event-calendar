<?php
/**
 * Shortcode attributes
 * @var $items_per_row
 * @var $css_animation
 * @var $el_id
 * @var $el_class
 * @var $css
 */

$atts = vc_map_get_attributes( 'event_portfolio', $atts );

extract( $atts );

$args       = [
	'posts_per_page' => -1,
	'post_type'      => self::$postType,
	'post_status'    => 'publish',
	'meta_query'     => [
		[
			'key'     => '_is_event_in_portfolio',
			'value'   => 'on',
			'compare' => '=',
		]
	],
	'tax_query'      => [],
];

$query = new WP_Query( $args );
$html_lg = '';
$html = '';
if ( $query->have_posts() ) {
	$total_post_number = $query->post_count;
	$counter = 1;
	$current_post_number = 0;
	$cols = 3;
	$the_items_per_row = $items_per_row;

	if($total_post_number < $items_per_row){
		switch ($total_post_number){
			case 3:
				$the_items_per_row = 3;
				break;
			case 2:
				$the_items_per_row = 2;
				break;
			case 1:
				$the_items_per_row = 1;
				break;
		}
    } else {
	    switch ($items_per_row){
            case 4:
	            if(($total_post_number % 4) == 0){
		            $the_items_per_row = 4;
	            } elseif (($total_post_number % 3) == 0){
		            $the_items_per_row = 3;
	            } elseif (($total_post_number % 2) == 0){
		            $the_items_per_row = 2;
	            } else {
		            $the_items_per_row = 1;
	            }
	            break;
            case 3:
	            if (($total_post_number % 3) == 0){
		            $the_items_per_row = 3;
	            } elseif (($total_post_number % 2) == 0){
		            $the_items_per_row = 2;
	            } else {
		            $the_items_per_row = 1;
	            }
                break;
            case 2:
	            if (($total_post_number % 2) == 0){
		            $the_items_per_row = 2;
	            } else {
		            $the_items_per_row = 1;
	            }
	            break;
            case 1:
	            $the_items_per_row = 1;
	            break;
        }
    }

	$html_lg .= '<div class="carousel-item active">';
	$html_lg .= '<div class="row align-items-stretch justify-content-center h-100">';

	$mobile_counter = 0;
	while ( $query->have_posts() ) {
		$current_post_number++;
		$query->the_post();
		$post_arr                               = [];
		$post_arr['image']                      = has_post_thumbnail( get_the_ID() ) ?
			wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
		$post_arr['title']                      = get_the_title();
		$post_arr['permalink']                  = get_post_permalink( get_the_ID() );
		$post_arr['col'] = $cols;

		$html       .= '<div class="carousel-item '.($mobile_counter == 0 ? 'active' : '').'">';
		$html .= LTTmplToVar( __DIR__ . DIRECTORY_SEPARATOR . '../partials/event_portfolio_item_mobile.tmpl.php', $post_arr );
		$html .= '</div>';
		$mobile_counter++;

		$html_x4 .= LTTmplToVar( __DIR__ . DIRECTORY_SEPARATOR . '../partials/event_portfolio_item.tmpl.php', $post_arr );

		$html_lg .= LTTmplToVar( __DIR__ . DIRECTORY_SEPARATOR . '../partials/event_portfolio_item_bootstrap.tmpl.php', $post_arr );

		if($counter == $the_items_per_row ){
			$html_lg .= '</div>'; //row
			$html_lg .= '</div>'; //carousel-item

			if($current_post_number < $total_post_number){
				$html_lg .= '<div class="carousel-item"><div class="row align-items-stretch justify-content-center h-100">';
			}
			$counter = 1;
		} else {
			$counter++;
			if($current_post_number == $total_post_number){
				$html_lg .= '</div>'; //row
				$html_lg .= '</div>'; //carousel-item
			}
		}
	}
}

$wrap_classes = 'event_portfolio clr';
$wrap_style   = '';

if ( $el_class ) {
	$wrap_classes .= ' ' . vcex_get_extra_class( $el_class );
}

if ( $css_animation && 'none' != $css_animation && function_exists( 'vcex_get_css_animation' ) ) {
	$wrap_classes .= ' ' . vcex_get_css_animation( $css_animation );
}
if ( $css ) {
	$wrap_classes .= ' ' . vc_shortcode_custom_css_class( $css );
}

$wrap_classes = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrap_classes, 'event_portfolio', $atts );

$wrapper_attributes = [];
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}


?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?> class="<?php echo esc_attr( trim( $wrap_classes ) ); ?>">
    <?php if($total_post_number <= 4):?>
        <div id="carouselExampleControls" class="carousel slide d-none d-lg-block" data-ride="carousel">
            <div class="row">
                <div class="col-1">
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                </div>
                <div class="col-10">
                    <div class="carousel-inner d-flex align-items-center">
					    <?php echo $html_lg;?>
                    </div>
                </div>
                <div class="col-1">
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php else:?>
        <?php if($html_x4):?>
            <div class="d-none d-lg-block">
                <div class="row" id="exampleSlider">
                    <div class="col-1 MS-controls">
                        <button class="MS-left carousel-control-prev"><i class="fa fa-angle-left"></i></button>
                    </div>
                    <div class="col-10">
                        <div class="MS-content">
                            <?php echo $html_x4;?>
                        </div>
                    </div>
                    <div class="col-1 MS-controls">
                        <button class="MS-right carousel-control-next"><i class="fa fa-angle-right"></i></button>
                    </div>
                </div>
            </div>
        <?php endif;?>
    <?php endif;?>
    <?php if($html):?>
	<div id="carouselExampleControlsMobile" class="carousel slide d-lg-none" data-ride="carousel">
		<div class="row">
			<div class="col-2">
				<a class="carousel-control-prev" href="#carouselExampleControlsMobile" role="button" data-slide="prev">
					<i class="fa fa-angle-left"></i>
				</a>
			</div>
			<div class="col-8">
				<div class="carousel-inner d-flex align-items-center">
					<?php echo $html;?>
				</div>
			</div>
			<div class="col-2">
				<a class="carousel-control-next" href="#carouselExampleControlsMobile" role="button" data-slide="next">
					<i class="fa fa-angle-right"></i>
				</a>
			</div>
		</div>
	</div>
    <?php endif;?>
</div>