<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_connection', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>
			<?php echo h($stationConnection['FromStation']['name']); ?>
			<small>&rarr; <?php echo h($stationConnection['FromStation']['direction']); ?></small>
			-
			<?php echo h($stationConnection['ToStation']['name']); ?>
			<small>&rarr; <?php echo h($stationConnection['ToStation']['direction']); ?></small>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo $this->Html->link('Detalii', array('controller' => 'station_connections', 'action' => 'view', $stationConnection['StationConnection']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'station_connections', 'action' => 'edit', $stationConnection['StationConnection']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'station_connections', 'action' => 'delete', $stationConnection['StationConnection']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi polyline-ul # %s?', $stationConnection['StationConnection']['id'])); ?></li>
	</ul>
</div>

<div class="row-fluid">
	<div class="span4">
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<td>ID</td>
					<td><?php echo $this->Html->link('#' . h($stationConnection['StationConnection']['id']), array('action' => 'view', $stationConnection['StationConnection']['id'])); ?></td>
				</tr>
				<tr>
					<td>Staţia de plecare</td>
					<td>
						<?php echo $this->Html->link(h($stationConnection['FromStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationConnection['FromStation']['id'])); ?>
						<small class="muted">&rarr; <?php echo $stationConnection['FromStation']['direction']; ?></small>	
					</td>
				</tr>
				<tr>
					<td>Staţia de sosire</td>
					<td>
						<?php echo $this->Html->link(h($stationConnection['ToStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationConnection['ToStation']['id'])); ?>
						<small class="muted">&rarr; <?php echo $stationConnection['ToStation']['direction']; ?></small>
					</td>
				</tr>
				<tr>
					<td>Polyline</td>
					<td><code id="polyline"><?php echo h($stationConnection['StationConnection']['polyline']); ?></code></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="span8">
		<div class="well well-small">
			<figure id="map" style="height:300px;"></figure>
		</div>
	</div>
</div>