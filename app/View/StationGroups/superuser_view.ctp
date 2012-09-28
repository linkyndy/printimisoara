<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_station_group', array('inline' => false)); ?>

<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
				<li class="nav-header">Actiuni</li>
				<li><?php echo $this->Html->link(__('<i class="icon-edit"></i> Editeaza grupul de statii'), array('action' => 'edit', $stationGroup['StationGroup']['id']), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link(__('<i class="icon-th"></i> Vezi toate grupurile de statii'), array('action' => 'index'), array('escape' => false)); ?></li>
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Grupul de statii: <?php echo h($stationGroup['StationGroup']['name']); ?></h1>
		<br>
		
		<h2>Detalii <small>despre aceast grup de statii</small></h2>
		<br>
		
		<table class="table table-striped table-bordered">
			<tr>
				<td>ID</td>
				<td id="station_group_id"><?php echo h($stationGroup['StationGroup']['id']); ?></td>
			</tr>
			<tr>
				<td>Denumire</td>
				<td><?php echo h($stationGroup['StationGroup']['name']); ?></td>
			</tr>
		</table>
		
		<h2>Harta</h2>
		<br>
		
		<div class="row">
			<figure id="map" class="span9" style="height:200px;"></figure>
		</div>
		
		<h2>Statii <small>ce fac parte din acest grup de statii</small></h2>
		<br>
		
		<?php if(!empty($stationGroup['Station'])): ?>
			<?php foreach($stationGroup['Station'] as $station): ?>
				<?php echo $this->Html->link($station['name_direction'], array('controller' => 'stations', 'action' => 'view', $station['id'])); ?>
			<?php endforeach; ?>
		<?php else: ?>
			<p>Nicio statie nu face parte din acest grup de statii.</p>
		<?php endif; ?>
	</div>
</div>