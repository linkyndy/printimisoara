<div class="row-fluid">
	<div class="page-header">
		<h1>
			Points
			<small>Puncte intre staţii</small>
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
				<th class="actions"><?php echo __('Acţiuni');?></th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($stationPoints as $stationPoint): ?>
				<tr>
					<td><?php echo $this->Html->link('#' . $stationPoint['StationPoint']['id'], array('action' => 'view', $stationPoint['StationPoint']['id'])); ?></td>
					<td>
						<?php echo $this->Html->link(h($stationPoint['FromStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationPoint['FromStation']['id'])); ?>
						<small class="muted">&rarr; <?php echo h($stationPoint['FromStation']['direction']); ?></small>	
					</td>
					<td>
						<?php echo $this->Html->link(h($stationPoint['ToStation']['name']), array('controller' => 'stations', 'action' => 'view', $stationPoint['ToStation']['id'])); ?>
						<small class="muted">&rarr; <?php echo h($stationPoint['ToStation']['direction']); ?></small>
					</td>
					<td class="actions">
						<?php echo $this->Html->link('<i class="icon-th-list"></i> Detalii', array('action' => 'view', $stationPoint['StationPoint']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
						<?php echo $this->Html->link('<i class="icon-edit"></i> Editează', array('action' => 'edit', $stationPoint['StationPoint']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
						<?php echo $this->Form->postLink('<i class="icon-trash icon-white"></i> Șterge', array('action' => 'delete', $stationPoint['StationPoint']['id']), array('class' => 'btn btn-small btn-danger', 'escape' => false), __('Esti sigur ca vrei sa stergi punctele # %s?', $stationPoint['StationPoint']['id'])); ?>
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