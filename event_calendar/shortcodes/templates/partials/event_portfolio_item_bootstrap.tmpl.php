<div class="col-<?php echo $args['col'];?>">
	<div class="card h-100">
		<a href="<?php echo $args['permalink']; ?>" class="text-center d-flex flex-column">
			<img src="<?php echo $args['image'][0] ?>"
			     class="d-block w-75 mt-4 mx-auto portfolio-image" alt="Event image">
			<div class="card-body d-flex align-items-end justify-content-center">
				<h5 class="card-title"><?php echo $args['title']; ?></h5>
			</div>
		</a>
	</div>
</div>