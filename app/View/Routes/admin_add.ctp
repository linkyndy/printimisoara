<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_stations', array('inline' => false)); ?>

<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
				<li class="nav-header">Actiuni</li>
				<li><?php echo $this->Html->link(__('<i class="icon-plus"></i> Adauga statie'), array('action' => 'add'), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link(__('<i class="icon-th"></i> Vezi toate statiile'), array('action' => 'index'), array('escape' => false)); ?></li>
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Adauga statie</h1>
		<br>
		
		<?php echo $this->Form->create('Station', array('class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Completeaza campurile si alege un punct de pe harta</legend>
				<?php
					echo $this->Form->input('id_ratt', array('div' => 'control-group', 'label' => array('text' => 'ID Ratt', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('name', array('div' => 'control-group', 'label' => array('text' => 'Denumirea statiei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('direction', array('div' => 'control-group', 'label' => array('text' => 'Directia statiei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('StationGroup.name', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($stationGroups)), 'autocomplete' => 'off', 'div' => 'control-group', 'label' => array('text' => 'Grupul de statii', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				?>
			</fieldset>
			<div class="row">
				<figure id="map" class="span9" style="height:300px;"></figure>
			</div>
			
			<table class="table table-bordered">
				<tr>
					<td>Latitudine: <span id="StationLatText"></span></td>
					<td>Longitudine: <span id="StationLngText"></span></td>
					<td>Regiune: <span id="StationRegionText"></span></td>
				</tr>
			</table>
			
			<?php echo $this->Form->hidden('lat'); ?>
			<?php echo $this->Form->hidden('lng'); ?>
			<?php echo $this->Form->hidden('region'); ?>
		<?php echo $this->Form->end(array('label' => __('Adauga'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
	</div>
</div>