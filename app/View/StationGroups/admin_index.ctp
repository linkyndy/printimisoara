<div class="row-fluid">
	<div class="page-header">
		<h1>Grupuri de staţii</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->Form->create('StationGroup', array('url' => array('action' => 'search'), 'class' => 'well form-search')); ?>
		<div class="input-append">
			<?php echo $this->Form->input('search', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($station_group_list)), 'data-items' => 10, 'autocomplete' => 'off', 'placeholder' => 'Caută un grup de staţii...', 'class' => 'search-query input-xlarge', 'label' => false, 'div' => false)); ?>
			<?php echo $this->Form->button('Caută', array('type' => 'submit', 'class' => 'btn', 'div' => false)); ?>
		</div>
		<div class="btn-group pull-right">
			<?php echo $this->Html->link(__('<i class="icon-plus"></i> Adaugă'), array('action' => 'add'), array('class' => 'btn', 'escape' => false)); ?>
		</div>
	<?php echo $this->Form->end(); ?>
</div>

<div class="row-fluid">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('id', 'ID');?></th>
				<th><?php echo $this->Paginator->sort('name', 'Nume');?></th>
				<th class="actions"><?php echo __('Acţiuni');?></th>
			</tr>
		</thead>
			
		<tbody>
		<?php foreach ($stationGroups as $stationGroup): ?>
			<tr>
				<td><?php echo $this->Html->link('#' . $stationGroup['StationGroup']['id'], array('action' => 'view', $stationGroup['StationGroup']['id'])); ?></td>
				<td><?php echo $this->Html->link(h($stationGroup['StationGroup']['name']), array('action' => 'view', $stationGroup['StationGroup']['id'])); ?></td>
				<td class="actions">
					<?php echo $this->Html->link('<i class="icon-th-list"></i> Detalii', array('action' => 'view', $stationGroup['StationGroup']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
					<?php echo $this->Html->link('<i class="icon-edit"></i> Editează', array('action' => 'edit', $stationGroup['StationGroup']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
					<?php echo $this->Form->postLink('<i class="icon-trash icon-white"></i> Șterge', array('action' => 'delete', $stationGroup['StationGroup']['id']), array('class' => 'btn btn-danger btn-small', 'escape' => false), __('Eşti sigur că vrei să ştergi grupul de staţii # %s?', $stationGroup['StationGroup']['id'])); ?>
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