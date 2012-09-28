<?php $labels = array('new' => 'label-new', 'pending' => 'label-pending', 'invalid' => 'label-invalid', 'resolved' => 'label-resolved', 'future' => 'label-future'); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>Bugs</h1>
	</div>
</div>

<div class="row-fluid">
	<div class="well">
		<div class="btn-group pull-right">
			<?php echo $this->Html->link(__('<i class="icon-plus"></i> Adaugă'), array('action' => 'add'), array('class' => 'btn', 'escape' => false)); ?>
		</div>
	</div>
</div>

<div class="row-fluid">
	<div class="well">
		Filtre:
		<?php foreach($labels as $name => $value): ?>
			<?php echo $this->Html->link($name, array('action' => 'index', $name), array('class' => 'label ' . $value)); ?>
		<?php endforeach; ?>
		
		<?php if(isset($this->request->params['pass'][0])): ?>
			<div class="pull-right">
				Filtru activ: <span class="label<?php echo ' '.$labels[$this->request->params['pass'][0]]; ?>"><?php echo $this->request->params['pass'][0]; ?></span>
				<?php echo $this->Html->link('Elimina filtru', array('action' => 'index')); ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<div class="row-fluid">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('id', 'ID');?></th>
				<th><?php echo $this->Paginator->sort('status', 'Stare');?></th>
				<th><?php echo $this->Paginator->sort('username', 'Utilizator');?></th>
				<th><?php echo $this->Paginator->sort('type', 'Tip');?></th>
				<th><?php echo $this->Paginator->sort('title', 'Titlu');?></th>
				<th><?php echo $this->Paginator->sort('created', 'Creat la');?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($bugs)): ?>
				<?php foreach ($bugs as $bug): ?>
					<tr>
						<td><?php echo $this->Html->link('#' . $bug['Bug']['id'], array('action' => 'view', $bug['Bug']['id'])); ?></td>
						<td><span class="label<?php echo ' '.$labels[h($bug['Bug']['status'])]; ?>"><?php echo h($bug['Bug']['status']); ?></span></td>
						<td><?php echo h($bug['User']['username']); ?></td>
						<td><?php echo h($bug['Bug']['type']); ?></td>
						<td><?php echo $this->Html->link(h($bug['Bug']['title']), array('action' => 'view', $bug['Bug']['id'])); ?></td>
						<td><?php echo $bug['Bug']['created']; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan="6">Nu exista niciun bug</td></tr>
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