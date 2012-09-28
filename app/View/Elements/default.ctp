<?php $words = array('Atentie', 'Fii atent'); ?>

<div class="alert">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<strong><?php echo $words[array_rand($words)]; ?>!</strong>
	<?php echo $message; ?>
</div>