<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_points', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>
			<?php echo h($this->Form->value('FromStation.name')); ?>
			<small>&rarr; <?php echo h($this->Form->value('FromStation.direction')); ?></small>
			-
			<?php echo h($this->Form->value('ToStation.name')); ?>
			<small>&rarr; <?php echo h($this->Form->value('ToStation.direction')); ?></small>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link('Detalii', array('controller' => 'station_points', 'action' => 'view', $this->Form->value('StationPoint.id')), array('escape' => false)); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Editează'), array('controller' => 'station_points', 'action' => 'edit', $this->Form->value('StationPoint.id')), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'station_points', 'action' => 'delete', $this->Form->value('StationPoint.id')), array('escape' => false), __('Eşti sigur că vrei să ştergi point-ul # %s?', $this->Form->value('StationPoint.id'))); ?></li>
	</ul>
</div>

<div class="row-fluid">
	<div class="span4">
		<?php echo $this->Form->create('StationPoint', array('class' => 'form-inline')); ?>
			<fieldset>
				<legend>Plaseaza punctele pe harta</legend>
			</fieldset>
			
			<?php echo $this->Form->hidden('id'); ?>
			<?php echo $this->Form->hidden('from_station_id'); ?>
			<?php echo $this->Form->hidden('to_station_id'); ?>
			<?php echo $this->Form->hidden('from_station_lat', array('value' => $this->Form->value('FromStation.lat'))); ?>
			<?php echo $this->Form->hidden('from_station_lng', array('value' => $this->Form->value('FromStation.lng'))); ?>
			<?php echo $this->Form->hidden('to_station_lat', array('value' => $this->Form->value('ToStation.lat'))); ?>
			<?php echo $this->Form->hidden('to_station_lng', array('value' => $this->Form->value('ToStation.lng'))); ?>
			<?php echo $this->Form->hidden('points'); ?>
		<?php echo $this->Form->end(array('label' => __('Salveaza'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
	</div>
	<div class="span8">
		<div class="well well-small">
			<figure id="map" style="height:300px;"></figure>
		</div>
	</div>
</div>