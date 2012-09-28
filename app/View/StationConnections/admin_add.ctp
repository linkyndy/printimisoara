<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_connections', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>Adaugă polyline</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->Form->create('StationConnection', array('class' => 'form-inline')); ?>
		<fieldset>
			<legend>Completeaza campurile si deseneaza conexiunea pe harta</legend>
			<div class="well">
				<?php echo $this->Form->input('from', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($stations)), 'autocomplete' => 'off', 'div' => false, 'label' => false, 'placeholder' => 'Statia de plecare', 'class' => 'input-xlarge')); ?>
				<?php echo $this->Form->input('to', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($stations)), 'autocomplete' => 'off', 'div' => false, 'label' => false, 'placeholder' => 'Statia de sosire', 'class' => 'input-xlarge')); ?>
				<?php echo $this->Form->button('Incarca statiile', array('type' => 'button', 'div' => false, 'id' => 'StationConnectionLoad', 'class' => 'btn')); ?>
			</div>
		</fieldset>
		
		<?php echo $this->Form->hidden('from_station_id'); ?>
		<?php echo $this->Form->hidden('to_station_id'); ?>
		<?php echo $this->Form->hidden('polyline'); ?>
	
		<div class="well well-small">
			<figure id="map" style="height:300px;"></figure>
		</div>
	
	<?php echo $this->Form->end(array('label' => __('Adauga'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
</div>