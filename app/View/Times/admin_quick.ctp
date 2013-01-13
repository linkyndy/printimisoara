<div class="row-fluid">
	<div class="page-header">
		<h1>Timpi</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->element('admin_time_submenu', array('active' => 'quick')); ?>
</div>
	
<div class="row-fluid">
	<div class="span6">
		<?php echo $this->Form->create('Time', array('url' => array('action' => 'quick'), 'class' => 'form-search well')); ?>
			<div class="input-append">
				<?php echo $this->Form->input('station_line', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($station_line_list)), 'data-items' => 10, 'autocomplete' => 'off', 'placeholder' => 'Linia | Staţia', 'class' => 'search-query span12', 'label' => false, 'div' => false)); ?>
				<?php echo $this->Form->button('Caută timp', array('type' => 'submit', 'class' => 'btn btn-info', 'div' => false)); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
	
	<div class="span6">
		<table class="table table-striped table-bordered">
			<?php if (isset($time)): ?>
				<tr>
					<td>Timpul</td>
					<td>
						<span class="label"><?php echo $this->Time->format('H:i', strtotime($time)); ?></span>
					</td>
				</tr>
				<tr>
					<td>Timp detaliat</td>
					<td>
						<?php if (!empty($detailedTime)): ?>
							<?php echo $detailedTime['ComputedTime']['log']; ?>
						<?php endif; ?>
					</td>
				</tr>
				
			<?php endif;?>
		</table>
		
		<p>
			<small class="muted">
				<span>Legenda: </span>
				<span class="label label-M">in minute</span> &bull;
				<span class="label label-G">din grafic</span> &bull;
				<span class="label label-T">din tabelă</span> &bull;
				<span class="label label-U">de la utilizator</span>
			</small>
		</p>
	</div>
</div>