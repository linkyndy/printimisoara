<div class="row-fluid">
	<div class="page-header">
		<h1>Timpi detaliati</h1>
	</div>
</div>

<div class="row-fluid">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('id', 'ID');?></th>
				<th><?php echo $this->Paginator->sort('line_id', 'Linie');?></th>
				<th><?php echo $this->Paginator->sort('station_id', 'Statie');?></th>
				<th><?php echo $this->Paginator->sort('time', 'Timp');?></th>
				<th><?php echo $this->Paginator->sort('log', 'Detalii');?></th>
				<th><?php echo $this->Paginator->sort('created', 'Creat la');?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($times)): ?>
				<?php foreach ($times as $time): ?>
					<tr>
						<td>#<?php echo $time['ComputedTime']['id']; ?></td>
						<td><?php echo $this->Html->line($time['Line']['name'], $time['Line']['colour'], $time['Line']['id']); ?></td>
						<td><?php echo $this->Html->link($time['Station']['name_direction'], array('controller' => 'stations', 'action' => 'view', $time['Station']['id'])); ?></td>
						<td><span class="label"><?php echo $this->Time->format('H:i', strtotime(h($time['ComputedTime']['time']))); ?></span></td>
						<td><?php echo $time['ComputedTime']['log']; ?></td>
						<td><?php echo $time['ComputedTime']['created']; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan="4">Nu exista niciun timp detaliat salvat</td></tr>
			<?php endif; ?>
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