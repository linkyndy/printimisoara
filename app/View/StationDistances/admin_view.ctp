<div class="row-fluid">
	<div class="page-header">
		<h1>
			<?php echo h($stationDistance['FromStation']['name']); ?>
			<small>&rarr; <?php echo h($stationDistance['FromStation']['direction']); ?></small>
			-
			<?php echo h($stationDistance['ToStation']['name']); ?>
			<small>&rarr; <?php echo h($stationDistance['ToStation']['direction']); ?></small>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo $this->Html->link('Detalii', array('controller' => 'station_distances', 'action' => 'view', $stationDistance['StationDistance']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'station_distances', 'action' => 'edit', $stationDistance['StationDistance']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'station_distances', 'action' => 'delete', $stationDistance['StationDistance']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi distanta # %s?', $stationDistance['StationDistance']['id'])); ?></li>
	</ul>
</div>

<div class="row-fluid">
	<table class="table table-striped table-bordered">
		<tr>
			<td>ID</td>
			<td><?php echo $this->Html->link('#' . h($stationDistance['StationDistance']['id']), array('action' => 'view', $stationDistance['StationDistance']['id'])); ?></td>
		</tr>
		<tr>
			<td>Statia de plecare</td>
			<td>
				<?php echo $this->Html->link(h($stationDistance['FromStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationDistance['FromStation']['id'])); ?>
				<small class="muted">&rarr; <?php echo h($stationDistance['FromStation']['direction']); ?></small>
			</td>
		</tr>
		<tr>
			<td>Statia de sosire</td>
			<td>
				<?php echo $this->Html->link(h($stationDistance['ToStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationDistance['ToStation']['id'])); ?>
				<small class="muted">&rarr; <?php echo h($stationDistance['ToStation']['direction']); ?></small>
			</td>
		</tr>
		<tr>
			<td>Distanta</td>
			<td>
				<?php echo h($stationDistance['StationDistance']['minutes']); ?>
				<small class="muted">min</small>
			</td>
		</tr>
		<tr>
			<td>La ora</td>
			<td><?php echo $this->Time->format('H:i', strtotime(h($stationDistance['StationDistance']['time']))); ?></td>
		</tr>
		<tr>
			<td>Tipul de zi</td>
			<td><span class="label label-<?php echo h($stationDistance['StationDistance']['day']); ?>"><?php echo h($stationDistance['StationDistance']['day']); ?></span></td>
		</tr>
	</table>
</div>