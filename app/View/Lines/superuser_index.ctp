<?php $line_types = array('tv' => 'Tramvai', 'tb' => 'Troleibuz', 'ab' => 'Autobuz', 'am' => 'Autobuz Metropolitan', 'ae' => 'Autobuz Expres'); ?>

<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Linii</h1>
		<br>
		
		<?php echo $this->Form->create('Line', array('url' => array('action' => 'search'), 'class' => 'well form-search')); ?>
			<?php echo $this->Form->input('search', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($line_list)), 'autocomplete' => 'off', 'placeholder' => 'Cauta o linie...', 'class' => 'search-query span3', 'label' => false, 'div' => false)); ?>
		<?php echo $this->Form->end(array('label' => 'Cauta', 'class' => 'btn', 'div' => false)); ?>
		
		<table class="table table-striped table-bordered">
			<tr>
				<th><?php echo $this->Paginator->sort('id');?></th>
				<th><?php echo $this->Paginator->sort('id_ratt');?></th>
				<th><?php echo $this->Paginator->sort('name');?></th>
				<th><?php echo $this->Paginator->sort('type');?></th>
				<th class="actions"><?php echo __('Actiuni');?></th>
			</tr>
			<?php foreach ($lines as $line): ?>
				<tr>
					<td><?php echo h($line['Line']['id']); ?>&nbsp;</td>
					<td><?php echo h($line['Line']['id_ratt']); ?>&nbsp;</td>
					<td><?php echo $this->Html->link(h($line['Line']['name']), array('action' => 'view', $line['Line']['id'])); ?>&nbsp;</td>
					<td><?php echo h($line_types[$line['Line']['type']]); ?>&nbsp;</td>
					<td class="actions">
						<?php echo $this->Html->link('<i class="icon-th-list"></i>', array('action' => 'view', $line['Line']['id']), array('title' => 'Detalii', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
						<?php echo $this->Html->link('<i class="icon-edit"></i>', array('action' => 'edit', $line['Line']['id']), array('title' => 'Editeaza', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
						<?php echo $this->Html->link('<i class="icon-asterisk"></i>', array('controller' => 'bugs', 'action' => 'add'), array('data-toggle' => 'modal', 'data-target' => '#modal-'.$line['Line']['id'], 'title' => 'Raporteaza bug', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
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

<?php foreach($lines as $line): ?>
	<?php echo $this->element('report', array('id' => $line['Line']['id'], 'type' => 'database', 'title' => 'Problema legata de linia '.$line['Line']['name'].' (#'.$line['Line']['id'].')')); ?>
<?php endforeach; ?>