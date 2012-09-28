<?php echo $this->Html->script('libs/jquery-ui-1.8.17.custom.min', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_station_lines', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>
			Linia 
			<span class="label<?php echo (isset($line['Line']['colour'])) ? ' label-line' . $line['Line']['colour'] : null; ?>">
				<?php echo h($line['Line']['name']); ?>
			</span>
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
	<ul id="stations" class="station-list">
		<?php foreach($stationLine as $station): ?>
			<li>
				<button class="btn<?php echo ($station['StationLine']['end'] == 1) ? ' active' : ''; ?>" data-toggle="button"></button>
				<span><?php echo $station['Station']['name_direction']; ?></span>
				<small>&times;</small>
			</li>
		<?php endforeach; ?>
		</ul>
		
		<?php echo $this->Form->create('StationLine', array('url' => array('action' => 'add', $this->request->params['pass'][0]), 'class' => 'form-inline')); ?>
			<?php echo $this->Form->input('add_station', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($stations)), 'autocomplete' => 'off', 'div' => false, 'label' => false, 'placeholder' => 'Introdu statia...', 'class' => 'span3')); ?>
			<?php echo $this->Form->button('Adauga', array('type' => 'button', 'class' => 'btn btn-primary', 'id' => 'StationLineAddButton')); ?>
		<?php echo $this->Form->end(array('label' => __('Salveaza'), 'div' => 'form-actions', 'class' => 'btn btn-primary', 'id' => 'StationLineSubmit')); ?>
	</div>
</div>