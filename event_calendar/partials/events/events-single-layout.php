<?php
$event_date_start           = get_post_meta( get_the_ID(), '_event_date_start', true );
$event_date_finish          = get_post_meta( get_the_ID(), '_event_date_finish', true );
$event_url                  = get_post_meta( get_the_ID(), '_event_url', true );
$event_cost                 = get_post_meta( get_the_ID(), '_event_cost', true );
$event_location_address     = get_post_meta( get_the_ID(), '_event_location_address', true );
$event_location_place_title = get_post_meta( get_the_ID(), '_event_location_place_title', true );
$event_ticket_url           = get_post_meta( get_the_ID(), '_event_ticket_url', true );
$event_instagram_link       = get_post_meta( get_the_ID(), '_event_instagram_link', true );
$event_facebook_link        = get_post_meta( get_the_ID(), '_event_facebook_link', true );

$is_past                    = false;


$post_arr          = [];
$post_arr['image'] = has_post_thumbnail( get_the_ID() ) ?
	wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' )[0] : '';

$post_arr['title']   = get_the_title();
$post_arr['excerpt'] = has_excerpt() ? get_the_excerpt() : '';
$post_arr['content'] = get_the_content();

$start_date  = null;
$finish_date = null;
$date        = null;
if ( $event_date_start ) {
	$start_date = ( new DateTime() )->setTimestamp( $event_date_start );
	$date       = $start_date->format( 'F jS, Y h:i A' ) . ' - NO End Date';
	$is_past    = intval( $event_date_start ) < time() ? true : false;

}

if ( $event_date_finish ) {
	$finish_date = ( new DateTime() )->setTimestamp( $event_date_finish );

	$interval = $start_date->diff( $finish_date )->format( '%a' );
	if ( intval( $interval ) > 0 ) {
		$date = $start_date->format( 'F jS' ) . ' - ' . $finish_date->format( 'F jS, Y' ) . ' - ' . $start_date->format( 'h:i A' ) . ' - ' . $finish_date->format( 'h:i A' );
	} else {
		$date = $start_date->format( 'F jS, Y h:i A' ) . ' - ' . $finish_date->format( 'h:i A' );
	}

}


$post_arr['event_url']                  = $event_url ? $event_url : '';
$post_arr['event_ticket_url']           = $event_ticket_url ? $event_ticket_url : '';
$post_arr['event_cost']                 = $event_cost ? $event_cost : '';
$post_arr['event_location_address']     = $event_location_address ? $event_location_address : '';
$post_arr['event_location_place_title'] = $event_location_place_title ? $event_location_place_title : '';
$carousel_images                        = '';
?>

<?php if ( have_rows( 'carousel_image' ) ): ?>
	<?php ob_start(); ?>
    <div class="col-12 mb-5">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
				<?php $counter = 0; ?>
				<?php while ( have_rows( 'carousel_image' ) ) : the_row(); ?>

                    <div class="carousel-item w-100 <?php echo $counter === 0 ? 'active' : ''; ?>"
                         style="background-image: url(<?php the_sub_field( 'carousel_item' ); ?>);">
                    </div>
					<?php $counter ++; ?>

				<?php endwhile; ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
	<?php $carousel_images = ob_get_contents(); ?>
	<?php ob_end_clean(); ?>
<?php endif; ?>

<div class="vc_row wpb_row vc_row-fluid vc-has-max-width vc-max-width-80 tablet-fullwidth-columns">
    <div class="wpb_column vc_column_container vc_col-sm-12">
        <div class="vc_column-inner ">
            <div class="wpb_wrapper">

				<?php if ( $_SERVER['HTTP_REFERER'] ): ?>
                    <div class="row mt-4 mb-3">
                        <div class="col-12 col-sm-4 col-md-3 col-lg-2">
                            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-primary btn-back "><i
                                        class="fa fa-angle-left"></i> Back</a>
                        </div>
                    </div>
				<?php endif; ?>
                <div class="row">
                    <div class="col-12 col-sm-12 col-lg-7 mb-5">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="media flex-column flex-sm-row">
                                    <img src="<?php echo $post_arr['image'] ?>"
                                         class="mr-sm-4 mt-2 mb-3 border detailed-img" alt="Event image">
                                    <div class="media-body">
                                        <h1 class="mt-0 purple-color detailed-event-title"><?php echo $post_arr['title']; ?></h1>
                                        <p><span><i class="fa fa-calendar mr-2"></i></span><?php echo $event_date_start ? $date : ''; ?></p>
                                        <p><span><i class="fa fa-map-marker mr-2"></i></span><?php echo $post_arr['event_location_place_title'] . ' ' . $post_arr['event_location_address']; ?></p>
                                        <p></p>
                                    </div>
                                </div>
                            </div>

                                <div class="col-12  col-md-4 col-lg-12 col-xl-4 mb-3">
                                    <a href="<?php echo $post_arr['event_url']; ?>"
                                       class="btn btn-primary btn-block btn-event-details">Event Website</a>
                                </div>
                                <div class="col-12 col-md-4 col-lg-12 col-xl-4 mb-3">
			                        <?php if ( ! $is_past ): ?>
                                        <a href="<?php echo $post_arr['event_ticket_url']; ?>"
                                           class="btn btn-primary btn-block btn-event-details btn-buy-ticket">Get
                                            Tickets</a>
			                        <?php endif; ?>
                                </div>
                                <div class="col-12 col-md-4 col-lg-12 col-xl-4 mb-3">
                                    <div class="">
                                        <div class="wpex-fa-social-widget clr textcenter">
                                            <ul style="font-size:28px;" class="ml-0 d-flex flex-row justify-content-start justify-content-md-around justify-content-lg-start justify-content-xl-around top_header_area">
                                                <li>
                                                    <a href="<?php echo $event_facebook_link;?>"
                                                       title="Facebook"
                                                       class="wpex-facebook wpex-social-btn wpex-social-btn-flat wpex-social-bg"
                                                       target="_blank"
                                                       style="height:40px;width:40px;line-height:42px;border-radius:50%;"><span
                                                                class="ticon ticon-facebook"
                                                                aria-hidden="true"></span><span
                                                                class="screen-reader-text">Facebook</span></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo $event_instagram_link;?>"
                                                       title="Instagram"
                                                       class="wpex-instagram wpex-social-btn wpex-social-btn-flat wpex-social-bg"
                                                       target="_blank"
                                                       style="height:40px;width:40px;line-height:42px;border-radius:50%;"><span
                                                                class="ticon ticon-instagram"
                                                                aria-hidden="true"></span><span
                                                                class="screen-reader-text">Instagram</span></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

							<?php if ( $post_arr['excerpt'] != '' ): ?>
                                <div class="col-12 mb-3">
                                    <p><?php echo $post_arr['excerpt']; ?></p>
                                </div>
							<?php endif; ?>
                            <div class="col-12 mb-3">
                                <p><?php echo get_the_content(); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-5 mb-3 mb-lg-5">
                        <div class="row">
							<?php echo $carousel_images; ?>
							<?php
							$args = [
								'post_limits'  => 5,
								'post_type'    => TasteCalendarBuilder::get_post_type_slug(),
								'post_status'  => 'publish',
								'meta_query'   => [
									'event_date_start' => [
										'key'     => '_event_date_start',
										'value'   => ( new DateTime() )->getTimestamp(),
										'compare' => '>=',
									]
								],
								'post__not_in' => [ get_the_ID() ],
								'tax_query'    => [],
								'orderby'      => 'event_date_start',
								'order'        => 'ASC',
							];

							$query = new WP_Query( $args );
							?>
							<?php if ( $query->have_posts() ): ?>
                                <div class="col-12 mb-5">
                                    <h2 class="orange-color"><strong>Similar Events</strong></h2>
                                    <hr>
									<?php while ( $query->have_posts() ): ?>
										<?php $query->the_post(); ?>
										<?php $image_src = has_post_thumbnail( get_the_ID() ) ?
											wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' )[0] : '';

										$event_date_start  = get_post_meta( get_the_ID(), '_event_date_start', true );
										$event_date_finish = get_post_meta( get_the_ID(), '_event_date_finish', true );

										$start_date  = null;
										$finish_date = null;
										$date        = null;
										if ( $event_date_start ) {
											$start_date = ( new DateTime() )->setTimestamp( $event_date_start );
											$date       = $start_date->format( 'F jS, Y h:i A' ) . ' - NO End Date';
										}

										if ( $event_date_finish ) {
											$finish_date = ( new DateTime() )->setTimestamp( $event_date_finish );

											$interval = $start_date->diff( $finish_date )->format( '%a' );
											if ( intval( $interval ) > 0 ) {
												$date = $start_date->format( 'F jS' ) . ' - ' . $finish_date->format( 'F jS, Y' ) . ' - ' . $start_date->format( 'h:i A' ) . ' - ' . $finish_date->format( 'h:i A' );
											} else {
												$date = $start_date->format( 'F jS, Y h:i A' ) . ' - ' . $finish_date->format( 'h:i A' );
											}

										}
										$event_location_place_title = get_post_meta( get_the_ID(), '_event_location_place_title', true );
										$event_location_address     = get_post_meta( get_the_ID(), '_event_location_address', true );

										?>
                                        <a href="<?php echo get_post_permalink( get_the_ID() ); ?>"
                                           class="d-block mb-3 additional-event-wrapper">
                                            <div class="media flex-column flex-sm-row ">
                                                <img src="<?php echo $image_src; ?>"
                                                     class="mr-sm-3 mt-2 mb-3 img-thumbnail additional-event-img"
                                                     alt="Event image">
                                                <div class="media-body">
                                                    <h2 class="mt-0 additional-event-title blue-color">
                                                        <strong><?php echo get_the_title(); ?></strong></h2>
                                                    <p class="additional-event-date"><span><i class="fa fa-calendar mr-2"></i></span><?php echo $date; ?></p>
                                                    <p class="additional-event-location"><span><i class="fa fa-map-marker mr-2"></i></span><?php echo $event_location_place_title . ' ' . $event_location_address; ?></p>
                                                </div>
                                            </div>
                                        </a>
									<?php endwhile; ?>
                                    <hr>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



