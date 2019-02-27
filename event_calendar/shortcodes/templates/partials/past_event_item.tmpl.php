<?php

$start_date = null;
$finish_date = null;
$date = null;
if($args['event_date_start']){
	$start_date = (new DateTime())->setTimestamp($args['event_date_start']);
	$date = $start_date->format('F jS, Y h:i A').' - NO End Date';
}

if($args['event_date_finish']){
	$finish_date = (new DateTime())->setTimestamp($args['event_date_finish']);

	$interval = $start_date->diff($finish_date)->format('%a');
	if(intval($interval) > 0 ){
		$date = $start_date->format('F jS').' - '.$finish_date->format('F jS, Y').' - '.$start_date->format('h:i A').' - '.$finish_date->format('h:i A');
	} else {
		$date = $start_date->format('F jS, Y h:i A').' - '.$finish_date->format('h:i A');
	}

}

?>

<div class="media flex-column flex-sm-row align-items-center mb-4">
	<a href="<?php echo $args['permalink'];?>" class="detailed-page-link">
        <img src="<?php echo $args['image'][0] ?>" class="mr-5 mt-2 mb-4 mb-md-0" alt="Event image">
    </a>
	<div class="media-body">
		<a href="<?php echo $args['permalink'];?>" class="detailed-page-link">
            <h2 class="event-list-title mt-0"><strong><?php echo $args['title']; ?></strong></h2>
        </a>
		<p class="event-detailes"><span><i class="fa fa-calendar mr-2"></i></span><?php echo $args['event_date_start'] ? $date : ''; ?></p>
		<p class="event-detailes"><span><i class="fa fa-map-marker mr-2"></i></span><?php echo $args['event_city']; ?></p>
		<div class="row">
			<div class="col-12 col-sm-6 mb-3">
				<a href="<?php echo $args['permalink'];?>"
                   class="btn btn-primary btn-lg btn-block btn-event-details">Event Details</a>
			</div>
            <div class="col-12 col-sm-6 mb-3">
                <a href="<?php echo $args['event_ticket_url'];?>"
                   class="btn btn-primary btn-lg btn-block btn-event-details btn-buy-ticket">Get Tickets</a>
            </div>
		</div>
	</div>
</div>
<hr>
