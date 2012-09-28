<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Js->set('$fromStationLat', $stationPoint['FromStation']['lat']); ?>
<?php echo $this->Js->set('$fromStationLng', $stationPoint['FromStation']['lng']); ?>
<?php echo $this->Js->set('$toStationLat', $stationPoint['ToStation']['lat']); ?>
<?php echo $this->Js->set('$toStationLng', $stationPoint['ToStation']['lng']); ?>
<?php echo $this->Html->scriptBlock('
	var $fromStationLat = '.$stationPoint['FromStation']['lat'].';
	var $fromStationLng = '.$stationPoint['FromStation']['lng'].';
	var $toStationLat = '.$stationPoint['ToStation']['lat'].';
	var $toStationLng = '.$stationPoint['ToStation']['lng'].';
', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_point', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>
			<?php echo h($stationPoint['FromStation']['name']); ?>
			<small>&rarr; <?php echo h($stationPoint['FromStation']['direction']); ?></small>
			-
			<?php echo h($stationPoint['ToStation']['name']); ?>
			<small>&rarr; <?php echo h($stationPoint['ToStation']['direction']); ?></small>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo $this->Html->link('Detalii', array('controller' => 'station_points', 'action' => 'view', $stationPoint['StationPoint']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'station_points', 'action' => 'edit', $stationPoint['StationPoint']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'station_points', 'action' => 'delete', $stationPoint['StationPoint']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi point-ul # %s?', $stationPoint['StationPoint']['id'])); ?></li>
	</ul>
</div>

<div class="row-fluid">
	<div class="span4">
		<table class="table table-striped table-bordered">
			<tr>
				<td>ID</td>
				<td><?php echo $this->Html->link('#' . h($stationPoint['StationPoint']['id']), array('action' => 'view', $stationPoint['StationPoint']['id'])); ?></td>
			</tr>
			<tr>
				<td>Statia de plecare</td>
				<td>
					<?php echo $this->Html->link(h($stationPoint['FromStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationPoint['FromStation']['id'])); ?>
					<small class="muted">&rarr; <?php echo h($stationPoint['FromStation']['direction']); ?></small>
				</td>
			</tr>
			<tr>
				<td>Statia de sosire</td>
				<td>
					<?php echo $this->Html->link(h($stationPoint['ToStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationPoint['ToStation']['id'])); ?>
					<small class="muted">&rarr; <?php echo h($stationPoint['ToStation']['direction']); ?></small>
				</td>
			</tr>
			<tr>
				<td>Puncte</td>
				<td><code id="points"><?php echo h($stationPoint['StationPoint']['points']); ?></code></td>
			</tr>
		</table>
	</div>
	<div class="span8">
		<div class="well well-small">
			<figure id="map" style="height:300px;"></figure>
		</div>
	</div>
</div>