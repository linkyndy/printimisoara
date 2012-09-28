<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
				<li class="nav-header">Actiuni</li>
				<li><?php echo $this->Html->link(__('<i class="icon-plus"></i> Adauga statie'), array('action' => 'add'), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link(__('<i class="icon-random"></i> Harta nodurilor'), array('action' => 'nodes'), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link(__('<i class="icon-random"></i> Conexiuni intre statii'), array('controller' => 'station_connections', 'action' => 'index'), array('escape' => false)); ?></li>
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Harta nodurilor</h1>
		<br>
		
		<?php echo $this->Form->create('Station', array('url' => array('action' => 'search'), 'class' => 'well form-search')); ?>
			<?php echo $this->Form->input('search', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($station_list)), 'autocomplete' => 'off', 'placeholder' => 'Cauta un nod...', 'class' => 'search-query span3', 'label' => false, 'div' => false)); ?>
		<?php echo $this->Form->end(array('label' => 'Cauta', 'class' => 'btn', 'div' => false)); ?>
		
		<table class="table table-striped table-bordered">
			<tr>
				<th><?php echo $this->Paginator->sort('id');?></th>
				<th><?php echo $this->Paginator->sort('id_ratt');?></th>
				<th><?php echo $this->Paginator->sort('station_group_id');?></th>
				<th><?php echo $this->Paginator->sort('name_direction');?></th>
				<th><?php echo $this->Paginator->sort('lat');?></th>
				<th><?php echo $this->Paginator->sort('lng');?></th>
				<th><?php echo $this->Paginator->sort('region');?></th>
				<th><?php echo $this->Paginator->sort('node');?></th>
				<th class="actions"><?php echo __('Actiuni');?></th>
			</tr>
			
			<?php foreach ($stations as $station): ?>
				<tr>
					<td><?php echo h($station['Station']['id']); ?>&nbsp;</td>
					<td><?php echo h($station['Station']['id_ratt']); ?>&nbsp;</td>
					<td>
						<?php echo $this->Html->link($station['StationGroup']['name'], array('controller' => 'station_groups', 'action' => 'view', $station['StationGroup']['id'])); ?>
					</td>
					<td><?php echo $this->Html->link(h($station['Station']['name_direction']), array('action' => 'view', $station['Station']['id'])); ?>&nbsp;</td>
					<td><?php echo h($station['Station']['lat']); ?>&nbsp;</td>
					<td><?php echo h($station['Station']['lng']); ?>&nbsp;</td>
					<td><?php echo h($station['Station']['region']); ?>&nbsp;</td>
					<td><?php echo ($station['Station']['node'] == 1) ? 'Da' : 'Nu'; ?>&nbsp;</td>
					<td class="actions">
						<?php echo $this->Html->link('<i class="icon-th-list"></i>', array('action' => 'view', $station['Station']['id']), array('title' => 'Detalii', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
						<?php echo $this->Html->link('<i class="icon-edit"></i>', array('action' => 'edit', $station['Station']['id']), array('title' => 'Editeaza', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
						<?php echo $this->Form->postLink('<i class="icon-trash"></i>', array('action' => 'delete', $station['Station']['id']), array('title' => 'Sterge', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false), __('Esti sigur ca vrei sa stergi statia # %s?', $station['Station']['id'])); ?>
						<?php echo $this->Html->link('<i class="icon-asterisk"></i>', array('controller' => 'bugs', 'action' => 'add'), array('data-toggle' => 'modal', 'data-target' => '#modal-'.$station['Station']['id'], 'title' => 'Raporteaza bug', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
		
		<div class="pagination">
			<ul>
				<?php echo $this->Paginator->prev('&larr; ' . __('Inapoi'), array('tag' => 'li', 'escape' => false), '<a>&larr; ' . __('Inapoi'). '</a>', array('tag' => 'li', 'escape' => false, 'class' => 'disabled')); ?>
				<?php echo $this->Paginator->numbers(array('separator' => '')); ?>
				<?php echo $this->Paginator->next(__('Inainte') . ' &rarr;', array('tag' => 'li', 'escape' => false), '<a>' . __('Inainte'). ' &rarr;</a>', array('tag' => 'li', 'escape' => false, 'class' => 'disabled')); ?>
			</ul>
		</div>
	</div>
</div>

<?php foreach($stations as $station): ?>
	<?php echo $this->element('report', array('id' => $station['Station']['id'], 'type' => 'database', 'title' => 'Problema legata de statia '.$station['Station']['name_direction'].' (#'.$station['Station']['id'].')')); ?>
<?php endforeach; ?>