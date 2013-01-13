<?php $labels = array('error' => 'label-important', 'warning' => 'label-warning', 'notice' => 'label-info', 'info' => '', 'debug' => '', 'time-info' => 'label-time-info', 'detailed-time' => 'label-detailed-time', 'cron' => 'label-cron', 'hijack' => 'label-hijack'); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>Log</h1>
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
				<th><?php echo $this->Paginator->sort('type', 'Tip');?></th>
				<th><?php echo $this->Paginator->sort('message', 'Mesaj');?></th>
				<th><?php echo $this->Paginator->sort('created', 'Creat la');?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($log)): ?>
				<?php foreach ($log as $event): ?>
					<tr>
						<td>#<?php echo $event['Log']['id']; ?>&nbsp;</td>
						<td><span class="label<?php echo ' '.$labels[$event['Log']['type']]; ?>"><?php echo $event['Log']['type']; ?></span>&nbsp;</td>
						<td><?php echo $event['Log']['message']; ?>&nbsp;</td>
						<td><?php echo $event['Log']['created']; ?>&nbsp;</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan="4">Nu exista niciun mesaj</td></tr>
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