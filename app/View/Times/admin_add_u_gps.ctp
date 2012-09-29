<?php echo $this->Html->script('geolocation', array('inline' => false)); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>Timpi</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->element('admin_time_submenu', array('active' => '')); ?>
</div>

<div class="row-fluid">
	<p>
		<span class="label">?</span>
		<small>Incarca statiile apropiate si apasa pe butonul "Acum" pentru a salva timpul curent.</small>
		<button class="geolocation-get btn btn-info pull-right"><i class="icon-refresh icon-white"></i></button>
	</p>
	
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