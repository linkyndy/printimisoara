<div class="row">
	<div class="span12">
		<h1>Utilizatori</h1>
		<br>
		
		<table class="table table-striped table-bordered">
			<tr>
				<th><?php echo $this->Paginator->sort('id');?></th>
				<th><?php echo $this->Paginator->sort('username');?></th>
				<th><?php echo $this->Paginator->sort('role');?></th>
				<th><?php echo $this->Paginator->sort('created');?></th>
				<th class="actions"><?php echo __('Actiuni');?></th>
			</tr>
			<?php foreach ($users as $user): ?>
				<tr>
					<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
					<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
					<td><?php echo h($user['User']['role']); ?>&nbsp;</td>
					<td><?php echo h($user['User']['created']); ?>&nbsp;</td>
					<td class="actions">
						<?php echo $this->Html->link('<i class="icon-th-list"></i>', array('action' => 'view', $user['User']['id']), array('title' => 'Detalii', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
						<?php echo $this->Html->link('<i class="icon-edit"></i>', array('action' => 'edit', $user['User']['id']), array('title' => 'Editeaza', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
						<?php echo $this->Html->link('<i class="icon-lock"></i>', array('action' => 'password', $user['User']['id']), array('title' => 'Schimba parola', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
						<?php echo $this->Form->postLink('<i class="icon-trash"></i>', array('action' => 'delete', $user['User']['id']), array('title' => 'Sterge', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false), __('Esti sigur ca vrei sa stergi linia # %s?', $user['User']['id'])); ?>
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