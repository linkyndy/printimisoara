<div class="row-fluid">
	<div class="page-header">
		<h1>Timpi</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->element('admin_time_submenu', array('active' => 'stations')); ?>
</div>
	
<div class="row-fluid">
	<?php echo $this->Form->create('Time', array('url' => array('action' => 'station'), 'class' => 'form-search well')); ?>
		<div class="input-append">
			<?php echo $this->Form->input('search', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($station_list)), 'data-items' => 10, 'autocomplete' => 'off', 'placeholder' => 'Caută o staţie...', 'class' => 'search-query input-xlarge', 'label' => false, 'div' => false)); ?>
			<?php echo $this->Form->button('Caută timp pt. staţie', array('type' => 'submit', 'class' => 'btn btn-info', 'div' => false)); ?>
		</div>
	<?php echo $this->Form->end(); ?>
</div>