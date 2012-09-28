<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->scriptBlock('var data = '.json_encode($data), array('inline' => false)); ?>
<?php echo $this->Html->script('admin_routes_discover', array('inline' => false)); ?>

<div class="row">
	<div class="row">
		<div class="span4">
			<h1>Detalii despre ruta</h1>
			<br>
		</div>
		<div class="span3">
			<h3>Timp total: <small><?php echo round($data['time']['total'], 5); ?>s</small></h3>
			<table class="table table-condensed">
				<tr><td>Statii apropiate</td><td><?php echo round($data['time']['nearStations'], 5); ?>s</td>
				<tr><td>Linii asociate statiilor</td><td><?php echo round($data['time']['stationLinesLookup'], 5); ?>s</td>
				<tr><td>Backtracking</td><td><?php echo round($data['time']['back'], 5); ?>s</td>
				<tr><td>Rute similare</td><td><?php echo round($data['time']['similarity'], 5); ?>s</td>
				<tr><td>Optimizare rute</td><td><?php echo round($data['time']['optimization'], 5); ?>s</td>
			</table>
		</div>
		<div class="span3">
			<h3>Timp backtracking: <small><?php echo round($data['time']['back'], 5); ?>s</small></h3>
			<table class="table table-condensed">
				<tr><td>Gasire statii urmatoare</td><td><?php echo round($data['time']['following'], 5); ?>s</td>
				<tr><td>Validare regiuni</td><td><?php echo round($data['time']['regions'], 5); ?>s</td>
				<tr><td>Validare linii/numar linii</td><td><?php echo round($data['time']['line'], 5); ?>s</td>
				<tr><td>Validare numar statii</td><td><?php echo round($data['time']['numberOfStations'], 5); ?>s</td>
				<tr><td>Validare grup statii</td><td><?php echo round($data['time']['stationGroup'], 5); ?>s</td>
				<tr><td>Validare schimbari</td><td><?php echo round($data['time']['changes'], 5); ?>s</td>
				<tr><td>Validare nod</td><td><?php echo round($data['time']['node'], 5); ?>s</td>
				<tr><td>Salvare ruta</td><td><?php echo round($data['time']['save'], 5); ?>s</td>
			</table>
		</div>
		<div class="span2">
			<?php echo $this->Html->link('Cauta alt traseu', array('action' => 'configure'), array('class' => 'btn')); ?>
		</div>
	</div>
	
	<?php //debug($data);exit(); ?>
	
	<div class="row">
		<div class="span6">
			<h2>Punctul de pornire</h2>
			<br>
			
			<div class="row">
				<figure id="from_map" class="span6" style="height:300px;"></figure>
			</div>
			
			<table class="table table-bordered">
				<tr>
					<td>Latitudine</td>
					<td>Longitudine</td>
				</tr>
				<tr>
					<td><?php echo $data['coords']['from']['lat']; ?></td>
					<td><?php echo $data['coords']['from']['lng']; ?></td>
				</tr>
			</table>
			
			<h3>Statii apropiate <small><?php echo $data['radius']['from']; ?>m</small></h3>
			<ul>
			<?php foreach($data['stations']['from'] as $station): ?>
				<li><?php echo $this->Html->link($station['Station']['name_direction'], array('controller' => 'stations', 'action' => 'view', $station['Station']['id'])); ?> <?php echo $station['Station']['distance']; ?>m</li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div class="span6">
			<h2>Punctul de sosire</h2>
			<br>
			
			<div class="row">
				<figure id="to_map" class="span6" style="height:300px;"></figure>
			</div>
			
			<table class="table table-bordered">
				<tr>
					<td>Latitudine</td>
					<td>Longitudine</td>
				</tr>
				<tr>
					<td><?php echo $data['coords']['to']['lat']; ?></td>
					<td><?php echo $data['coords']['to']['lng']; ?></td>
				</tr>
			</table>
			
			<h3>Statii apropiate <small><?php echo $data['radius']['to']; ?>m</small></h3>
			<ul>
			<?php foreach($data['stations']['to'] as $station): ?>
				<li><?php echo $this->Html->link($station['Station']['name_direction'], array('controller' => 'stations', 'action' => 'view', $station['Station']['id'])); ?> <?php echo $station['Station']['distance']; ?>m</li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	
	<h2>Rute gasite</h2>
	<br>
	
	<div class="row">
		<figure id="routes_map" class="span12" style="height:300px;"></figure>
	</div>
	
	<?php foreach($data['routes'] as $route): ?>
		<div class="row">
			<div class="span10">
				<table class="table table-bordered table-striped">
				<?php foreach($route['route'] as $i => $station): ?>
					<?php if($i > 0 && $station['Line']['id'] != $route['route'][$i - 1]['Line']['id'] || $i == 0): ?>
						<tr>
							<td><?php echo $this->Html->link($station['Line']['name'], array('controller' => 'lines', 'action' => 'view', $station['Line']['id'])); ?></td><td>
					<?php endif; ?>
					<?php //if($i < count($route['route']) - 1 && $station['StationLine']['id'] == $route['route'][$i + 1]['StationLine']['id']) continue; ?>
					<?php //if($i > 0 && $station['StationLine']['id'] == $route['route'][$i - 1]['StationLine']['id']) echo '<strong>'; ?>
					<?php echo $this->Html->link($station['Station']['name_direction'], array('controller' => 'stations', 'action' => 'view', $station['Station']['id'])); ?>
					<?php //if($i > 0 && $station['StationLine']['id'] == $route['route'][$i - 1]['StationLine']['id']) echo '</strong>'; ?>
					<?php if($i == count($route['route']) - 2 || $i < count($route['route']) - 2 && $station['Line']['id'] == $route['route'][$i + 1]['Line']['id']): ?>
						&rarr;
					<?php endif; ?>
					<?php if($i < count($route['route']) - 1 && $station['Line']['id'] != $route['route'][$i + 1]['Line']['id']): ?>
						</td></tr>
					<?php endif; ?>
				<?php endforeach; ?>
				</table>
			</div>
			<div class="span2">
				<ul>
					<li><?php echo $route['data']['number_of_lines']; ?> linii</li>
					<li><?php echo $route['data']['number_of_nodes']; ?> noduri</li>
					<li><?php echo $route['data']['number_of_stations']; ?> statii</li>
				</ul>
			</div>
		</div>
	<?php endforeach; ?>
</div>