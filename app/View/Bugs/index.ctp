<?php $labels = array('new' => 'blue-bg', 'pending' => 'orange-bg', 'invalid' => 'pink-bg', 'resolved' => 'grass-bg', 'future' => 'purple-bg'); ?>
<?php $statusTranslations = array('new' => 'Nou', 'pending' => 'In asteptare', 'invalid' => 'Invalid', 'resolved' => 'Rezolvat', 'future' => 'In viitor'); ?>
<?php $typeTranslations = array('routes' => 'rute', 'times' => 'timpi', 'database' => 'baza de date', 'location' => 'locatii', 'app' => 'aplicatie'); ?>

<h2>
	Bug-uri
	<?php if(isset($this->request->params['pass'][0])): ?>
		<small> - <?php echo $this->request->params['pass'][0]; ?></small>
	<?php endif; ?>
</h2>

<section id="bugs-container">
	<table class="zebra">
		<?php if(!empty($bugs)): ?>
			<?php foreach ($bugs as $bug): ?>
				<tr>
					<td>#<?php echo $bug['Bug']['id']; ?></td>
					<td><span class="label<?php echo ' '.$labels[$bug['Bug']['status']]; ?>"><?php echo $statusTranslations[$bug['Bug']['status']]; ?></span></td>
					<td>
						<strong><?php echo $this->Html->link($bug['Bug']['title'], array('action' => 'view', $bug['Bug']['id'])); ?></strong>
						<br>
						<small>postat de <i><?php echo $bug['User']['username']; ?></i> la <i><?php echo $bug['Bug']['created']; ?></i> in topicul <i><?php echo $typeTranslations[$bug['Bug']['type']]; ?></i></small>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr><td colspan="3">Nu exista niciun bug raportat in acest moment.</td></tr>
		<?php endif; ?>
	</table>
	
	<div class="pagination">
		<ul>
			<?php echo $this->Paginator->prev('&larr; ' . __('Inapoi'), array('tag' => 'li', 'escape' => false), '<a>&larr; ' . __('Inapoi'). '</a>', array('tag' => 'li', 'escape' => false, 'class' => 'disabled')); ?>
			<?php echo $this->Paginator->numbers(array('separator' => '')); ?>
			<?php echo $this->Paginator->next(__('Inainte') . ' &rarr;', array('tag' => 'li', 'escape' => false), '<a>' . __('Inainte'). ' &rarr;</a>', array('tag' => 'li', 'escape' => false, 'class' => 'disabled')); ?>
		</ul>
	</div>
</section>

<aside>
	<?php echo $this->Html->link('Raporteaza bug', array('action' => 'add'), array('class' => 'button')); ?>
	<br>
	
	<h4>Filtreaza dupa:</h4>
	<ul>
		<?php $total = 0; ?>
		<?php foreach($labels as $name => $value): ?>
			<?php $total += count($bugsByStatus[$name]); ?>
			<li>
				<span class="label<?php echo ' '.$value; ?>">
					<?php echo $this->Html->link($statusTranslations[$name], array('action' => 'index', $name)); ?>
				</span>&nbsp;&times;&nbsp;<?php echo count($bugsByStatus[$name]); ?>
			</li>
		<?php endforeach; ?>
		<li>
			<span class="label brown-bg">
				<?php echo $this->Html->link('Toate', array('action' => 'index')); ?>
			</span>&nbsp;&times;&nbsp;<?php echo $total; ?>
		</li>
	</ul>
</aside>