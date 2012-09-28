<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_points', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>Adaugă point</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->Form->create('StationPoint', array('class' => 'form-inline')); ?>
		<fieldset>
			<legend>Completeaza campurile si plaseaza punctele pe harta</legend>
			<div class="well">
				<?php echo $this->Form->input('from', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($stations)), 'autocomplete' => 'off', 'div' => false, 'label' => false, 'placeholder' => 'Statia de plecare', 'class' => 'span3')); ?>
				<?php echo $this->Form->input('to', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($stations)), 'autocomplete' => 'off', 'div' => false, 'label' => false, 'placeholder' => 'Statia de sosire', 'class' => 'span3')); ?>
				<?php echo $this->Form->button('Incarca statiile', array('type' => 'button', 'div' => false, 'id' => 'StationPointLoad', 'class' => 'btn')); ?>
			</div>
		</fieldset>
		
		<?php echo $this->Form->hidden('from_station_id'); ?>
		<?php echo $this->Form->hidden('to_station_id'); ?>
		<?php echo $this->Form->hidden('from_station_lat'); ?>
		<?php echo $this->Form->hidden('from_station_lng'); ?>
		<?php echo $this->Form->hidden('to_station_lat'); ?>
		<?php echo $this->Form->hidden('to_station_lng'); ?>
		<?php echo $this->Form->hidden('points'); ?>
		
		<div class="well well-small">
			<figure id="map" style="height:300px;"></figure>
		</div>
		
	<?php echo $this->Form->end(array('label' => __('Adauga'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
</div>