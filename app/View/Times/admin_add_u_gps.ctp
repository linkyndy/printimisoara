<?php echo $this->Html->script('geolocation', array('inline' => false)); ?>

<div class="row-fluid">
	<?php echo $this->Html->link('<i class="icon-chevron-left icon-white"></i> Inapoi', array('controller' => 'times', 'action' => 'index'), array('class' => 'btn btn-inverse', 'escape' => false)); ?>
	<button class="geolocation-get btn btn-inverse pull-right"><i class="icon-refresh icon-white"></i></button>
</div>

<div class="row-fluid">
	<hr>
	<table class="table table-striped table-bordered">
		<thead>
			<th>Linia</th>
			<th>Statia</th>
			<th>Acum</th>
		</thead>
		<tbody class="geolocation-container">
			<tr>
				<td colspan="3"><button class="geolocation-get btn btn-link">Apasa aici</button> pentru a incarca statiile apropiate.</td>
			</tr>
		</tbody>
	</table>
</div>