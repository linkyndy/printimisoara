<div class="row-fluid">
	<div class="page-header">
		<h1>Timpi</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Adaugă
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu">
				<li><?php echo $this->Html->link('din tabelă', array('controller' => 'times', 'action' => 'add', 'T'), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link('de la utilizator', array('controller' => 'times', 'action' => 'add', 'U'), array('escape' => false)); ?></li>
			</ul>
		</li>
		<li><?php echo $this->Html->link('Afla timp', array('controller' => 'times', 'action' => 'quick'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Ultimii timpi', array('controller' => 'times', 'action' => 'index'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Staţii', array('controller' => 'times', 'action' => 'stations'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Linii', array('controller' => 'times', 'action' => 'lines'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Acoperire', array('controller' => 'times', 'action' => 'coverage'), array('escape' => false)); ?></li>
	</ul>
</div>

<?php echo $this->Form->create('Time', array('class' => 'form-horizontal')); ?>
<?php echo $this->Form->hidden('type', array('value' => $type)); ?>
<div class="row-fluid">
	<div class="span4">
		<?php echo $this->Form->input('station_line', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($station_line_list)), 'data-items' => 10, 'autocomplete' => 'off', 'placeholder' => 'Linia | Staţia', 'class' => 'span12', 'label' => false, 'div' => false)); ?>
		
		<hr>
		
		<div class="btn-group" data-toggle="buttons-radio" data-hidden="TimeDay">
			<button type="button" class="btn active" value="L"><span class="label label-L">L</span></button>
			<button type="button" class="btn" value="LV"><span class="label label-LV">LV</span></button>
			<button type="button" class="btn" value="S"><span class="label label-S">S</span></button>
			<button type="button" class="btn" value="D"><span class="label label-D">D</span></button>
		</div>
		<?php echo $this->Form->hidden('day', array('value' => 'L')); ?>
		
		<?php if ($type == 'T'): ?>
			<hr>
			<span class="label label-info">Nu uita!</span>
			<small class="muted">Timpii adaugati acum ii vor suprascrie pe cei salvati pentru aceasta statie/linie/zi.</small>
		<?php endif; ?>
	</div>

	<div class="span4">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Ora | Minutele</th>
				</tr>
			</thead>
			
			<tbody>
				<?php for ($i = 0; $i <= 11; $i++): ?>
					<tr>
						<td>
							<div class="input-prepend">
								<span class="add-on"><?php echo $i; ?></span>
								<?php echo $this->Form->input('Time.time.' . $i . '.minutes', array('type' => 'text', 'label' => false, 'div' => false)); ?>
							</div>
						</td>
					</tr>
				<?php endfor; ?>
			</tbody>				
		</table>
	</div>
	
	<div class="span4">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>Ora | Minutele</th>
				</tr>
			</thead>
			
			<tbody>
				<?php for ($i = 12; $i <= 23; $i++): ?>
					<tr>
						<td>
							<div class="input-prepend">
								<span class="add-on"><?php echo $i; ?></span>
								<?php echo $this->Form->input('Time.time.' . $i . '.minutes', array('type' => 'text', 'label' => false, 'div' => false)); ?>
							</div>
						</td>
					</tr>
				<?php endfor; ?>
			</tbody>				
		</table>
	</div>		
</div>

<div class="row-fluid">
	<div class="form-actions">
		<?php echo $this->Form->button('Adaugă', array('type' => 'submit', 'class' => 'btn btn-primary', 'div' => false)); ?>
	</div>
</div>
<?php echo $this->Form->end(); ?>