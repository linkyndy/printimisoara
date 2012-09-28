<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_stations', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>
			<?php echo h($this->Form->value('Station.name')); ?>
			<small>&rarr; <?php echo h($this->Form->value('Station.direction')); ?></small>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link('Detalii', array('controller' => 'stations', 'action' => 'view', $this->Form->value('Station.id')), array('escape' => false)); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Editează'), array('controller' => 'stations', 'action' => 'edit', $this->Form->value('Station.id')), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'stations', 'action' => 'delete', $this->Form->value('Station.id')), array('escape' => false), __('Eşti sigur că vrei să ştergi staţia # %s?', $this->Form->value('Station.id'))); ?></li>
		<li><?php echo $this->Html->link(__('Timpi'), array('controller' => 'times', 'action' => 'station', $this->Form->value('Station.id')), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Bug', array('controller' => 'bugs', 'action' => 'add'), array('data-toggle' => 'modal', 'data-target' => '#modal-'.$this->Form->value('Station.id'), 'escape' => false)); ?></li>
	</ul>
</div>

<div class="row-fluid">
	<div class="span4">	
		<?php echo $this->Form->create('Station', array('class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Modifica campurile sau muta punctul pe harta</legend>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('id_ratt', array('div' => 'control-group', 'label' => array('text' => 'ID Ratt', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('name', array('div' => 'control-group', 'label' => array('text' => 'Denumirea statiei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('direction', array('div' => 'control-group', 'label' => array('text' => 'Directia statiei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('StationGroup.name', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($stationGroups)), 'autocomplete' => 'off', 'div' => 'control-group', 'label' => array('text' => 'Grupul de statii', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('node', array('div' => 'control-group', 'label' => array('text' => 'Nod', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				?>
			</fieldset>
			
			
			<?php echo $this->Form->hidden('lat'); ?>
			<?php echo $this->Form->hidden('lng'); ?>
			<?php echo $this->Form->hidden('region'); ?>
			<div class="form-actions">
				<?php echo $this->Form->button(__('Salvează'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
	<div class="span8">
		<div class="well well-small">
			<figure id="map" style="height:300px;"></figure>
		</div>
			
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td>Latitudine: <span id="StationLatText"></span></td>
					<td>Longitudine: <span id="StationLngText"></span></td>
					<td>Regiune: <span id="StationRegionText"></span></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>