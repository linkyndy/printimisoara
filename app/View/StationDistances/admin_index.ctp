<div class="row-fluid">
	<div class="page-header">
		<h1>
			Distanţe
			<small>Distanţe intre staţii</small>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<div class="btn-group well">
		<?php echo $this->Html->link(__('<i class="icon-plus"></i> Adaugă'), array('action' => 'add'), array('class' => 'btn', 'escape' => false)); ?>
	</div>
</div>

<div class="row-fluid">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('id', 'ID');?></th>
				<th><?php echo $this->Paginator->sort('from_station_id', 'De la staţia &rarr; Direcţia', array('escape' => false));?></th>
				<th><?php echo $this->Paginator->sort('to_station_id', 'Până la staţia &rarr; Direcţia', array('escape' => false));?></th>
				<th><?php echo $this->Paginator->sort('minutes', 'Distanţa', array('escape' => false)); ?></th>
				<th><?php echo $this->Paginator->sort('time', 'La ora'); ?></th>
				<th><?php echo $this->Paginator->sort('day', 'Tipul de zi'); ?></th>
				<th class="actions"><?php echo __('Acţiuni');?></th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($stationDistances as $stationDistance): ?>
				<tr>
					<td><?php echo $this->Html->link('#' . $stationDistance['StationDistance']['id'], array('action' => 'view', $stationDistance['StationDistance']['id'])); ?></td>
					<td>
						<?php echo $this->Html->link(h($stationDistance['FromStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationDistance['FromStation']['id'])); ?>
						<small class="muted">&rarr; <?php echo h($stationDistance['FromStation']['direction']); ?></small>	
					</td>
					<td>
						<?php echo $this->Html->link(h($stationDistance['ToStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationDistance['ToStation']['id'])); ?>
						<small class="muted">&rarr; <?php echo h($stationDistance['ToStation']['direction']); ?></small>
					</td>
					<td>
						<?php echo h($stationDistance['StationDistance']['minutes']); ?>
						<small class="muted">min</small>
					</td>
					<td><?php echo $this->Time->format('H:i', strtotime(h($stationDistance['StationDistance']['time']))); ?></td>
					<td><span class="label label-<?php echo h($stationDistance['StationDistance']['day']); ?>"><?php echo h($stationDistance['StationDistance']['day']); ?></span></td>
					<td class="actions">
						<?php echo $this->Html->link('<i class="icon-th-list"></i> Detalii', array('action' => 'view', $stationDistance['StationDistance']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
						<?php echo $this->Html->link('<i class="icon-edit"></i> Editează', array('action' => 'edit', $stationDistance['StationDistance']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
						<?php echo $this->Form->postLink('<i class="icon-trash icon-white"></i> Șterge', array('action' => 'delete', $stationDistance['StationDistance']['id']), array('class' => 'btn btn-small btn-danger', 'escape' => false), __('Esti sigur ca vrei sa stergi distanta # %s?', $stationDistance['StationDistance']['id'])); ?>
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