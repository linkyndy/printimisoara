<?php $words = array('Eroare', 'Ah', 'Of'); ?>

<div class="alert alert-error">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong><?php echo $words[array_rand($words)]; ?>!</strong>
	<?php echo $message; ?>
</div>