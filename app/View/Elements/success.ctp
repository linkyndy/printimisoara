<?php $words = array('Minunat', 'Super', 'Perfect'); ?>

<div class="alert alert-success">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong><?php echo $words[array_rand($words)]; ?>!</strong>
	<?php echo $message; ?>
</div>