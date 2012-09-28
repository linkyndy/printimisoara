<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_stations', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>Adaugă staţie</h1>
	</div>
</div>

<div class="row-fluid">
	<div class="span4">
		<?php echo $this->Form->create('Station', array('class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Completeaza campurile si alege un punct de pe harta</legend>
				<?php
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
				<?php echo $this->Form->button('Adaugă', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
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