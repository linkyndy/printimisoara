<div class="row-fluid">
	<div class="page-header">
		<h1>Pagina de administrare</h1>
		<h3>Bine ai revenit!</h3>
	</div>
</div>

<div class="row-fluid">
	<div class="span4">
		<?php if ($vacation['is_vacation']): ?>
			<div class="alert alert-info">
				<strong>Este vacanta!</strong> Din <?php echo $this->Time->format('d F', $vacation['vacation_start']); ?> pana in <?php echo $this->Time->format('d F', $vacation['vacation_end']); ?></strong>.
			</div>
		<?php elseif ($vacation['is_close_to_vacation']): ?>
			<div class="alert alert-info">
				<strong>In curand vine vacanta!</strong> Din <?php echo $this->Time->format('d F', $vacation['vacation_start']); ?> pana in <?php echo $this->Time->format('d F', $vacation['vacation_end']); ?></strong>.
			</div>
		<?php endif; ?>
	</div>
</div>