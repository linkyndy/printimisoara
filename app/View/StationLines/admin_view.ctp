<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_line', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>
			Linia 
			<?php echo $this->Html->line($line['Line']['name'], $line['Line']['colour'], $line['Line']['id']); ?>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link(__('Detalii'), array('controller' => 'lines', 'action' => 'view', $line['Line']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'lines', 'action' => 'edit', $line['Line']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'lines', 'action' => 'delete', $line['Line']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi linia # %s?', $line['Line']['id'])); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Staţii'), array('controller' => 'station_lines', 'action' => 'view', $line['Line']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Timpi'), array('controller' => 'times', 'action' => 'line', $line['Line']['id']), array('escape' => false)); ?></li>
		<!--<li><?php echo $this->Html->link(__('Verifica nodurile'), array('action' => 'validate_nodes', $line['Line']['id']), array('escape' => false)); ?></li>-->
	</ul>
</div>

<div class="row-fluid">
	<div class="btn-group well">
		<?php echo $this->Html->link(__('<i class="icon-edit"></i> Editează'), array('action' => 'edit', $line['Line']['id']), array('class' => 'btn', 'escape' => false)); ?>
	</div>
</div>

<div class="row-fluid">
	<?php if(!empty($directions)): ?>
		<?php foreach($directions as $i => $direction): ?>
			<div class="span4">
				<ul class="station-list">
					<h3>Directia <?php echo $i + 1; ?></h3>
					<br>
					<?php foreach($direction as $station): ?>
						<li>
							<button class="btn btn-disabled<?php echo ($station['StationLine']['end']) ? ' active' : ''; ?>" disabled></button>
							<?php echo $this->Html->link($station['Station']['name'], array('controller' => 'stations', 'action' => 'view', $station['Station']['id'])); ?>
							<small class="muted">&rarr; <?php echo $station['Station']['direction']; ?></small>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	<?php else: ?>
		Nu exista nicio statie asociata cu aceasta linie.
	<?php endif; ?>
</div>