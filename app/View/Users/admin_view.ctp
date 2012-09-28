<div class="row">
	<div class="span12">
		<h1>Utilziatorul <?php echo h($user['User']['username']); ?></h1>
		<br>
		
		<table class="table table-striped table-bordered">
			<tr>
				<td>ID</td>
				<td><?php echo h($user['User']['id']); ?></td>
			</tr>
			<tr>
				<td>Username</td>
				<td><?php echo h($user['User']['username']); ?></td>
			</tr>
			<tr>
				<td>Rol</td>
				<td><?php echo h($user['User']['role']); ?></td>
			</tr>
			<tr>
				<td>Created</td>
				<td><?php echo h($user['User']['created']); ?></td>
			</tr>
		</table>
	</div>
</div>