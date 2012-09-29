<?php $days = array('L' => 'Zi lucratoare', 'LV' => 'Zi lucratoare vacanta', 'S' => 'Sambata', 'D' => 'Duminica'); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>Timpi</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->element('admin_time_submenu', array('active' => 'index')); ?>
</div>

<div class="row-fluid">
	<div class="span8">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('id', 'ID');?></th>
					<th><?php echo $this->Paginator->sort('line_id', 'Linie');?></th>
					<th><?php echo $this->Paginator->sort('station_id', 'Staţie');?></th>
					<th><?php echo $this->Paginator->sort('time', 'Timp');?></th>
					<th><?php echo $this->Paginator->sort('day', 'Pentru');?></th>
					<th class="actions"><?php echo __('Acţiuni');?></th>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach ($times as $time): ?>
					<tr>
						<td><?php echo $this->Html->link('#' . $time['Time']['id'], array('action' => 'view', $time['Time']['id'])); ?></td>
						<td><?php echo $this->Html->line(h($time['Line']['name']), h($time['Line']['colour']), h($time['Line']['id'])); ?></td>
						<td>
							<?php echo $this->Html->link(h($time['Station']['name']), array('controller' => 'stations', 'action' => 'view', $time['Station']['id'])); ?>
							<small class="muted">&rarr; <?php echo h($time['Station']['direction']); ?></small>
						</td>
						<td><span class="label label-<?php echo h($time['Time']['type']); ?>"><?php echo $this->Time->format('H:i', strtotime(h($time['Time']['time']))); ?></span></td>
						<td><span class="label label-<?php echo h($time['Time']['day']); ?>"><?php echo h($time['Time']['day']); ?></span></td>
						<td class="actions">
							<?php echo $this->Html->link('<i class="icon-th-list"></i> Detalii', array('action' => 'view', $time['Time']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	
		<div class="pagination">
			<ul>
				<?php echo $this->Paginator->prev('&larr; ' . __('Inapoi'), array('tag' => 'li', 'escape' => false), '<a>&larr; ' . __('Inapoi'). '</a>', array('tag' => 'li', 'escape' => false, 'class' => 'disabled')); ?>
				<?php echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active')); ?>
				<?php echo $this->Paginator->next(__('Inainte') . ' &rarr;', array('tag' => 'li', 'escape' => false), '<a>' . __('Inainte'). ' &rarr;</a>', array('tag' => 'li', 'escape' => false, 'class' => 'disabled')); ?>
			</ul>
		</div>
	</div>
	
	<div class="span4">
		<?php echo $this->Form->create('Time', array('url' => array('action' => 'quick'), 'class' => 'form-search well')); ?>
			<div class="input-append">
				<?php echo $this->Form->input('station_line', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($station_line_list)), 'data-items' => 10, 'autocomplete' => 'off', 'placeholder' => 'Linia | Staţia', 'class' => 'search-query span12', 'label' => false, 'div' => false)); ?>
				<?php echo $this->Form->button('Caută timp', array('type' => 'submit', 'class' => 'btn btn-info', 'div' => false)); ?>
			</div>
		<?php echo $this->Form->end(); ?>
		
		<hr>
		
		<p>
			<small class="muted">
				<span>Legenda: </span>
				<span class="label label-M">in minute</span> &bull;
				<span class="label label-G">din grafic</span> &bull;
				<span class="label label-T">din tabelă</span> &bull;
				<span class="label label-U">de la utilizator</span>
			</small>
		</p>
	</div>
</div>