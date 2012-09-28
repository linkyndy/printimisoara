<?php $labels = array('new' => 'blue-bg', 'pending' => 'orange-bg', 'invalid' => 'pink-bg', 'resolved' => 'grass-bg', 'future' => 'purple-bg'); ?>
<?php $statusTranslations = array('new' => 'Nou', 'pending' => 'In asteptare', 'invalid' => 'Invalid', 'resolved' => 'Rezolvat', 'future' => 'In viitor'); ?>
<?php $typeTranslations = array('routes' => 'rute', 'times' => 'timpi', 'database' => 'baza de date', 'location' => 'locatii', 'app' => 'aplicatie'); ?>

<h2>#<?php echo $bug['Bug']['id']; ?> <?php echo h($bug['Bug']['title']); ?></h2>

<section>
	<?php echo h($bug['Bug']['bug']); ?>
	
	<h4>Mesaje</h4>
	<?php echo $this->Text->toList(array_unique(Set::extract('/BugMessage/User/username', $bug)), 'si', ', '); ?> participa la discutie
	<?php if(!empty($bug['BugMessage'])): ?>
		<?php foreach($bug['BugMessage'] as $bugMessage): ?>
			<?php echo $bugMessage['username']; ?>
			<?php echo $bugMessage['message']; ?>
			<?php echo $bugMessage['created']; ?>
		<?php endforeach; ?>
	<?php else: ?>
		<p>Nu a fost postat niciun mesaj pentru acest bug.</p>
	<?php endif; ?>
	
	<?php echo $this->element('add_bug_message'); ?>
</section>

<aside>
	<h4>Informatii</h4>
	
	<table>
		<tr>
			<td><small><strong>ID</strong></small></td>
			<td><?php echo $bug['Bug']['id']; ?></td>
		</tr>
		<tr>
			<td><small><strong>Postat de</strong></small></td>
			<td><?php echo h($bug['User']['username']); ?></td>
		</tr>
		<tr>
			<td><small><strong>Creat la</strong></small></td>
			<td><?php echo h($bug['Bug']['created']); ?></td>
		</tr>
		<tr>
			<td><small><strong>Status</strong></small></td>
			<td><span class="label<?php echo ' '.$labels[$bug['Bug']['status']]; ?>"><?php echo $statusTranslations[$bug['Bug']['status']]; ?></span></td>
		</tr>
		<tr>
			<td><small><strong>Topic</strong></small></td>
			<td><?php echo $typeTranslations[$bug['Bug']['type']]; ?></td>
		</tr>
	</table>
	
	<?php if(AuthComponent::user('id') == $bug['Bug']['user_id']): ?>
		<br>
		<?php echo $this->Html->link('Editeaza', array('action' => 'edit', $bug['Bug']['id'])); ?>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $this->Form->postLink('Sterge', array('action' => 'delete', $bug['Bug']['id']), array('escape' => false), __('Esti sigur ca vrei sa stergi bug-ul # %s?', $bug['Bug']['id'])); ?>
	<?php endif; ?>
</aside>