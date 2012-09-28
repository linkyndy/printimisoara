<div class="row">
	<div class="span12">
		<h1>Sugestii</h1>
		<br>
		
		<table class="table table-striped table-bordered">
			<tr>
				<th>Id</th>
				<th>Utilizator</th>
				<th>Model</th>
				<th>Intrare sugerata</th>
				<th>Schimbari</th>
				<th>Actiuni</th>
			</tr>
			<?php foreach ($suggestions as $suggestion): ?>
				<tr>
					<td><?php echo $suggestion['Suggestion']['id']; ?></td>
					<td><?php echo $suggestion['User']['username']; ?></td>
					<td><?php echo $suggestion['Suggestion']['model']; ?></td>
					<td><?php echo $suggestion['Suggestion']['model_id']; ?></td>
					<td>
						<table class="table table-striped table-bordered">
						<tr>
							<th>Camp</th>
							<th>Valoare actuala</th>
							<th>Valoare sugerata</th>
						</tr>
						<?php foreach($suggestion['Suggestion']['suggestions'] as $field => $values): ?>
							<tr>
								<td><?php echo $field; ?></td>
								<td><?php echo $values['actual']; ?></td>
								<td><?php echo $values['suggested']; ?></td>
							</tr>						
						<?php endforeach; ?>
						</table>
					</td>
					<td>
						<?php echo $this->Html->link('<i class="icon-ok"></i>', array('action' => 'accept', $suggestion['Suggestion']['id']), array('title' => 'Accepta sugestia', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false)); ?>
						<?php echo $this->Form->postLink(__('<i class="icon-remove"></i>'), array('action' => 'reject', $suggestion['Suggestion']['id']), array('title' => 'Sterge sugestia', 'rel' => 'tooltip', 'class' => 'btn', 'escape' => false), __('Esti sigur ca vrei sa stergi sugestia # %s?', $suggestion['Suggestion']['id'])); ?>
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