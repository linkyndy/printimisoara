<?php $labels = array('new' => 'label-new', 'pending' => 'label-pending', 'invalid' => 'label-invalid', 'resolved' => 'label-resolved', 'future' => 'label-future'); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1><?php echo h($bug['Bug']['title']); ?></h1>
	</div>
</div>

<div class="row-fluid">
	<div class="span4">
		<ul class="nav nav-tabs">
			<li class="active"><?php echo $this->Html->link('Detalii', array('controller' => 'bugs', 'action' => 'view', $bug['Bug']['id']), array('escape' => false)); ?></li>
			<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'bugs', 'action' => 'edit', $bug['Bug']['id']), array('escape' => false)); ?></li>
			<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'bugs', 'action' => 'delete', $bug['Bug']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi bug-ul # %s?', $bug['Bug']['id'])); ?></li>
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					Modifică stare
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<?php foreach ($labels as $status => $class): ?>
						<li>
							<?php echo $this->Html->link($status, array('action' => 'update_status', $bug['Bug']['id'], $status), array('escape' => false)); ?>	
						</li>
					<?php endforeach; ?>
				</ul>
			</li>
		</ul>
		
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<td>ID</td>
					<td><?php echo $this->Html->link('#' . $bug['Bug']['id'], array('action' => 'view', $bug['Bug']['id'])); ?></td>
				</tr>
				<tr>
					<td>Stare</td>
					<td><span class="label<?php echo ' '.$labels[$bug['Bug']['status']]; ?>"><?php echo $bug['Bug']['status']; ?></span></td>
				</tr>
				<tr>
					<td>Tip</td>
					<td><?php echo h($bug['Bug']['type']); ?></td>
				</tr>
				<tr>
					<td>Utilizator</td>
					<td><?php echo h($bug['User']['username']); ?></td>
				</tr>
				<tr>
					<td>Creat la</td>
					<td><?php echo h($bug['Bug']['created']); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="span8">
		<h3>Bug</h3>
		<p class="lead"><?php echo h($bug['Bug']['bug']); ?></p>
		
		<small class="muted">
			<em>
				<?php 
					$users = array_unique(Set::extract('/BugMessage/User/username', $bug));
					echo $this->Text->toList(empty($users) ? array(h($bug['User']['username'])) : $users, 'si', ', '); 
				?> 
				participa la discutie
			</em>
		</small>
		
		<hr>
		
		<h3>Răspunsuri</h3>
		<table id="messages" class="table table-striped table-bordered">
			<?php if(!empty($bug['BugMessage'])): ?>
				<?php foreach($bug['BugMessage'] as $bugMessage): ?>
					<tr>
						<td>
							<p><?php echo $bugMessage['message']; ?></p>
							<small class="muted">
								Postat de <?php echo $bugMessage['User']['username']; ?> la <?php echo $bugMessage['created']; ?>
								<span class="pull-right">
									<?php echo $this->Html->link('<i class="icon-edit"></i> Editează', array('controller' => 'bug_messages', 'action' => 'edit', $bug['Bug']['id'], $bugMessage['id']), array('class' => 'btn btn-mini', 'escape' => false)); ?>
									<?php echo $this->Form->postLink('<i class="icon-trash icon-white"></i> Șterge', array('controller' => 'bug_messages', 'action' => 'delete', $bug['Bug']['id'], $bugMessage['id']), array('class' => 'btn btn-mini btn-danger', 'escape' => false), __('Esti sigur ca vrei sa stergi mesajul # %s?', $bugMessage['id'])); ?>
								</span>
							</small>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<p>Niciun mesaj pentru acest bug.</p>
			<?php endif; ?>
		</table>
		
		<hr>
		
		<?php echo $this->element('add_bug_message'); ?>		
	</div>
</div>