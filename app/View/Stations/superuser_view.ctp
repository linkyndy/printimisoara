<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_station', array('inline' => false)); ?>

<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
				<li class="nav-header">Actiuni</li>
				<li><?php echo $this->Html->link(__('<i class="icon-edit"></i> Editeaza statia'), array('action' => 'edit', $station['Station']['id']), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link(__('<i class="icon-th"></i> Vezi toate statiile'), array('action' => 'index'), array('escape' => false)); ?></li>
				<li class="nav-header">Date relationate</li>
				<li><?php echo $this->Html->link(__('<i class="icon-time"></i> Timpii de sosire'), array('controller' => 'times', 'action' => 'view', $station['Station']['id']), array('escape' => false)); ?></li>
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Statia: <?php echo h($station['Station']['name']); ?></h1>
		<br>
		
		<h2>Detalii <small>despre aceasta statie</small></h2>
		<br>
		
		<table class="table table-striped table-bordered">
			<tr>
				<td>ID</td>
				<td><?php echo h($station['Station']['id']); ?></td>
			</tr>
			<tr>
				<td>ID Ratt</td>
				<td><?php echo h($station['Station']['id_ratt']); ?></td>
			</tr>
			<tr>
				<td>Denumire</td>
				<td><?php echo h($station['Station']['name']); ?></td>
			</tr>
			<tr>
				<td>Directie</td>
				<td><?php echo h($station['Station']['direction']); ?></td>
			</tr>
			<tr>
				<td>Grup de statii</td>
				<td><?php echo $this->Html->link($station['StationGroup']['name'], array('controller' => 'station_groups', 'action' => 'view', $station['StationGroup']['id'])); ?></td>
			</tr>
			<tr>
				<td>Coordonate</td>
				<td>
					<table class="table table-bordered">
						<tr>
							<th>Latitudine</th>
							<th>Longitudine</th>
							<th>Regiune</th>
						</tr>
						<tr>
							<td id="lat"><?php echo h($station['Station']['lat']); ?></td>
							<td id="lng"><?php echo h($station['Station']['lng']); ?></td>
							<td><?php echo h($station['Station']['region']); ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>Nod</td>
				<td><?php echo ($station['Station']['node'] == 1) ? 'Da' : 'Nu'; ?></td>
			</tr>
		</table>
		
		<h2>Harta</h2>
		<br>
		
		<div class="row">
			<figure id="map" class="span9" style="height:200px;"></figure>
		</div>
		
		<h2>Linii <small>ce trec prin aceasta statie</small></h2>
		<br>
		
		<?php if(!empty($station['StationLine'])): ?>
			<?php foreach($station['StationLine'] as $line): ?>
				<?php echo $this->Html->link($line['Line']['name'], array('controller' => 'lines', 'action' => 'view', $line['line_id'])); ?>
			<?php endforeach; ?>
		<?php else: ?>
			<p>Nicio linie nu trece prin aceasta statie.</p>
		<?php endif; ?>
	</div>
</div>