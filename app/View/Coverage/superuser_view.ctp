<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_line', array('inline' => false)); ?>

<?php $line_types = array('tv' => 'Tramvai', 'tb' => 'Troleibuz', 'ab' => 'Autobuz', 'am' => 'Autobuz Metropolitan', 'ae' => 'Autobuz Expres'); ?>

<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
				<li class="nav-header">Actiuni</li>
				<li><?php echo $this->Html->link(__('<i class="icon-edit"></i> Editeaza linia'), array('action' => 'edit', $line['Line']['id']), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link(__('<i class="icon-th"></i> Vezi toate liniile'), array('action' => 'index'), array('escape' => false)); ?></li>
				<li class="nav-header">Date relationate</li>
				<li><?php echo $this->Html->link(__('<i class="icon-time"></i> Timpi de sosire'), array('controller' => 'times', 'action' => 'line', $line['Line']['id']), array('escape' => false)); ?></li>
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Linia <?php echo h($line['Line']['name']); ?></h1>
		<br>
		
		<h2>Detalii <small>despre linia <?php echo h($line['Line']['name']); ?></small></h2>
		
		<table class="table table-striped table-bordered">
			<tr>
				<td>ID</td>
				<td id="line_id"><?php echo h($line['Line']['id']); ?></td>
			</tr>
			<tr>
				<td>ID Ratt</td>
				<td><?php echo h($line['Line']['id_ratt']); ?></td>
			</tr>
			<tr>
				<td>Denumire linie</td>
				<td><?php echo h($line['Line']['name']); ?></td>
			</tr>
			<tr>
				<td>Tip linie</td>
				<td><?php echo h($line_types[$line['Line']['type']]); ?></td>
			</tr>
		</table>
		
		<h2>Statiile <small>liniei <?php echo h($line['Line']['name']); ?></small></h2>
		<br>
		
		<?php if(!empty($directions)): ?>
			<div class="row">
				<?php foreach($directions as $i => $direction): ?>
					<div class="span4">
						<ul class="station-list">
							<h3>Directia <?php echo $i + 1; ?></h3>
							<br>
							<?php foreach($direction as $station): ?>
								<li>
									<button class="btn btn-disabled<?php echo ($station['StationLine']['end']) ? ' active' : ''; ?>" disabled></button>
									<?php echo $this->Html->link($station['Station']['name_direction'], array('controller' => 'stations', 'action' => 'view', $station['Station']['id'])); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			Nu exista nicio statie asociata cu aceasta linie.
		<?php endif; ?>
		
		<h2>Harta <small>statiilor liniei <?php echo h($line['Line']['name']); ?></small></h2>
		
		<div class="row">
			<figure id="map" class="span9" style="height:300px;"></figure>
		</div>
	</div>
</div>