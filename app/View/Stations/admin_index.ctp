<div class="row-fluid">
	<div class="page-header">
		<h1>Staţii</h1>
	</div>
</div>
	
<div class="row-fluid">
	<?php echo $this->Form->create('Station', array('url' => array('action' => 'search'), 'class' => 'form-search well')); ?>
		<div class="input-append">
			<?php echo $this->Form->input('search', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($station_list)), 'data-items' => 10, 'autocomplete' => 'off', 'placeholder' => 'Caută o staţie...', 'class' => 'search-query input-xlarge', 'label' => false, 'div' => false)); ?>
			<?php echo $this->Form->button('Caută', array('type' => 'submit', 'class' => 'btn', 'div' => false)); ?>
		</div>
		<div class="btn-group pull-right">
			<?php echo $this->Html->link(__('<i class="icon-plus"></i> Adaugă'), array('action' => 'add'), array('class' => 'btn', 'escape' => false)); ?>
			<!--<?php echo $this->Html->link(__('<i class="icon-random"></i> Harta nodurilor'), array('action' => 'nodes'), array('class' => 'btn', 'escape' => false)); ?>-->
			<?php echo $this->Html->link(__('<i class="icon-briefcase"></i> Grupuri de staţii'), array('controller' => 'station_groups', 'action' => 'index'), array('class' => 'btn', 'escape' => false)); ?>
			<?php echo $this->Html->link(__('<i class="icon-random"></i> Polylines'), array('controller' => 'station_connections', 'action' => 'index'), array('class' => 'btn', 'escape' => false)); ?>
			<?php echo $this->Html->link(__('<i class="icon-screenshot"></i> Points'), array('controller' => 'station_points', 'action' => 'index'), array('class' => 'btn', 'escape' => false)); ?>
			<!--<?php echo $this->Html->link(__('<i class="icon-share-alt"></i> Refa cache-ul statiilor urmatoare'), array('controller' => 'following_station_lines', 'action' => 'compute'), array('class' => 'btn', 'escape' => false)); ?>-->
		</div>
	<?php echo $this->Form->end(); ?>
</div>
		
<div class="row-fluid">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('id', 'ID');?></th>
				<th><?php echo $this->Paginator->sort('id_ratt', 'ID RATT');?></th>
				<th><?php echo $this->Paginator->sort('name_direction', 'Nume &rarr; Direcţie', array('escape' => false));?></th>
				<th><?php echo $this->Paginator->sort('station_group_id', 'Grupul de staţii');?></th>
				<th><?php echo $this->Paginator->sort('node', 'Nod?');?></th>
				<th class="actions"><?php echo __('Acţiuni');?></th>
			</tr>
		</thead>
		
		<tbody>
		<?php foreach ($stations as $station): ?>
			<tr>
				<td><?php echo $this->Html->link('#' . h($station['Station']['id']), array('action' => 'view', $station['Station']['id'])); ?></td>
				<td>#<?php echo h($station['Station']['id_ratt']); ?></td>
				<td>
					<?php echo $this->Html->link(h($station['Station']['name']), array('action' => 'view', $station['Station']['id'])); ?>
					<small class="muted">&rarr; <?php echo h($station['Station']['direction']); ?></small>
				</td>
				<td><?php echo $this->Html->link($station['StationGroup']['name'], array('controller' => 'station_groups', 'action' => 'view', $station['StationGroup']['id'])); ?></td>
				<td>
					<?php if ($station['Station']['node'] == 1): ?>
						<span class="label label-info">&#10004;</span>
					<?php endif; ?>
				</td>
				<td class="actions">
					<?php echo $this->Html->link('<i class="icon-th-list"></i> Detalii', array('action' => 'view', $station['Station']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
					<?php echo $this->Html->link('<i class="icon-edit"></i> Editează', array('action' => 'edit', $station['Station']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
					<?php echo $this->Form->postLink('<i class="icon-trash icon-white"></i> Șterge', array('action' => 'delete', $station['Station']['id']), array('class' => 'btn btn-danger btn-small', 'escape' => false), __('Eşti sigur că vrei să ştergi staţia # %s?', $station['Station']['id'])); ?>
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