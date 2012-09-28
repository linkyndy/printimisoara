<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Grupuri de statii</h1>
		<br>
		
		<?php echo $this->Form->create('StationGroup', array('url' => array('action' => 'search'), 'class' => 'well form-search')); ?>
			<?php echo $this->Form->input('search', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($station_group_list)), 'autocomplete' => 'off', 'placeholder' => 'Cauta un grup de statii...', 'class' => 'search-query span3', 'label' => false, 'div' => false)); ?>
		<?php echo $this->Form->end(array('label' => 'Cauta', 'class' => 'btn', 'div' => false)); ?>
		
		<table class="table table-striped table-bordered">
			<tr>
				<th><?php echo $this->Paginator->sort('id');?></th>
				<th><?php echo $this->Paginator->sort('name');?></th>
				<th class="actions"><?php echo __('Actiuni');?></th>
			</tr>
			
			<?php foreach ($stationGroups as $stationGroup): ?>
				<tr>
					<td><?php echo h($stationGroup['StationGroup']['id']); ?>&nbsp;</td>
					<td>
						<?php echo $this->Html->link($stationGroup['StationGroup']['name'], array('action' => 'view', $stationGroup['StationGroup']['id'])); ?>
					</td>
					<td class="actions">
						<?php echo $this->Html->link('<i class="icon-th-list"></i>', array('action' => 'view', $stationGroup['StationGroup']['id']), array('title' => 'Detalii', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
						<?php echo $this->Html->link('<i class="icon-edit"></i>', array('action' => 'edit', $stationGroup['StationGroup']['id']), array('title' => 'Editeaza', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
		
		<div class="pagination">
			<ul>
				<?php echo $this->Paginator->prev('&larr; ' . __('Inapoi'), array('tag' => 'li', 'escape' => false), '<a>&larr; ' . __('Inapoi'). '</a>', array('tag' => 'li', 'escape' => false, 'class' => 'disabled')); ?>
				<?php echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li')); ?>
				<?php echo $this->Paginator->next(__('Inainte') . ' &rarr;', array('tag' => 'li', 'escape' => false), '<a>' . __('Inainte'). ' &rarr;</a>', array('tag' => 'li', 'escape' => false, 'class' => 'disabled')); ?>
			</ul>
		</div>
	</div>
</div>