<div class="row-fluid">
	<div class="page-header">
		<h1>Adaugă distanta</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->Form->create('StationDistance', array('class' => 'form-horizontal')); ?>
		<fieldset>
			<legend>Completeaza campurile</legend>
			<?php echo $this->Form->input('from', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($stations)), 'autocomplete' => 'off', 'div' => 'control-group', 'label' => array('text' => 'Statia de plecare', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')), 'class' => 'span3')); ?>
			<?php echo $this->Form->input('to', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($stations)), 'autocomplete' => 'off', 'div' => 'control-group', 'label' => array('text' => 'Statia de sosire', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')), 'class' => 'span3')); ?>
			<?php echo $this->Form->input('minutes', array('div' => 'control-group', 'label' => array('text' => 'Distanta (in minute)', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')), 'class' => 'input-small')); ?>
			<?php echo $this->Form->input('time', array('type' => 'text', 'div' => 'control-group', 'label' => array('text' => 'Timpul (H:i)', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')), 'class' => 'input-small')); ?>
			
			<div class="control-group">
				<label class="control-label">Tipul de zi</label>
				<div class="controls">
					<div class="btn-group" data-toggle="buttons-radio" data-hidden="StationDistanceDay">
						<button type="button" class="btn active" value="L"><span class="label label-L">L</span></button>
						<button type="button" class="btn" value="LV"><span class="label label-LV">LV</span></button>
						<button type="button" class="btn" value="S"><span class="label label-S">S</span></button>
						<button type="button" class="btn" value="D"><span class="label label-D">D</span></button>
					</div>
				</div>
			</div>
			<?php echo $this->Form->hidden('day', array('value' => 'L')); ?>
		</fieldset>
		
	<?php echo $this->Form->end(array('label' => __('Adauga'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
</div>