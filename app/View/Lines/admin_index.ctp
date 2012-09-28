<?php $line_types = array('tv' => 'Tramvai', 'tb' => 'Troleibuz', 'ab' => 'Autobuz', 'am' => 'Autobuz Metropolitan', 'ae' => 'Autobuz Expres'); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>Linii</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->Form->create('Line', array('url' => array('action' => 'search'), 'class' => 'well form-search')); ?>
		<div class="input-append">
			<?php echo $this->Form->input('search', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($line_list)), 'data-items' => 10, 'autocomplete' => 'off', 'placeholder' => 'Caută o linie...', 'class' => 'search-query input-xlarge', 'label' => false, 'div' => false)); ?>
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
				<th><?php echo $this->Paginator->sort('id_ratt', 'ID RATT');?></th>
				<th><?php echo $this->Paginator->sort('name', 'Nume');?></th>
				<th><?php echo $this->Paginator->sort('type', 'De');?></th>
				<th class="actions"><?php echo __('Acţiuni');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($lines as $line): ?>
				<tr>
					<td><?php echo $this->Html->link('#' . h($line['Line']['id']), array('action' => 'view', $line['Line']['id'])); ?></td>
					<td>#<?php echo h($line['Line']['id_ratt']); ?></td>
					<td>
						<?php echo $this->Html->line($line['Line']['name'], $line['Line']['colour'], $line['Line']['id']); ?>
					</td>
					<td><?php echo h($line_types[$line['Line']['type']]); ?></td>
					<td class="actions">
						<?php echo $this->Html->link('<i class="icon-th-list"></i> Detalii', array('action' => 'view', $line['Line']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
						<?php echo $this->Html->link('<i class="icon-edit"></i> Editează', array('action' => 'edit', $line['Line']['id']), array('class' => 'btn btn-small', 'escape' => false)); ?>
						<?php echo $this->Form->postLink('<i class="icon-trash icon-white"></i> Șterge', array('action' => 'delete', $line['Line']['id']), array('class' => 'btn btn-small btn-danger', 'escape' => false), __('Eşti sigur că vrei să ştergi linia # %s?', $line['Line']['id'])); ?>
						<!--<?php echo $this->Html->link('<i class="icon-asterisk"></i>', array('controller' => 'bugs', 'action' => 'add'), array('data-toggle' => 'modal', 'data-target' => '#modal-'.$line['Line']['id'], 'title' => 'Raporteaza bug', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>-->
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

<!--<?php foreach($lines as $line): ?>
	<?php echo $this->element('report', array('id' => $line['Line']['id'], 'type' => 'database', 'title' => 'Problema legata de linia '.$line['Line']['name'].' (#'.$line['Line']['id'].')')); ?>
<?php endforeach; ?>-->