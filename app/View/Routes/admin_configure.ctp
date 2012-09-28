<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_routes_configure', array('inline' => false)); ?>

<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
				
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Configureaza ruta</h1>
		<br>
		
		<?php echo $this->Form->create('Route', array('url' => array('action' => $method), 'class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Alege doua puncte de pe harta</legend>
			</fieldset>
			<div class="row">
				<figure id="map" class="span9" style="height:300px;"></figure>
			</div>
			
			<div class="row">
				<div class="span4">
					<table class="table table-bordered">
						<caption>Plecare</caption>
						<tr>
							<td>Latitudine: <span id="FromLatText"></span></td>
							<td>Longitudine: <span id="FromLngText"></span></td>
						</tr>
					</table>
				</div>
				<div class="span4">
					<table class="table table-bordered">
						<caption>Sosire</caption>
						<tr>
							<td>Latitudine: <span id="ToLatText"></span></td>
							<td>Longitudine: <span id="ToLngText"></span></td>
						</tr>
					</table>
				</div>
			</div>
			
			<?php echo $this->Form->hidden('From.lat'); ?>
			<?php echo $this->Form->hidden('From.lng'); ?>
			<?php echo $this->Form->hidden('To.lat'); ?>
			<?php echo $this->Form->hidden('To.lng'); ?>
		<?php echo $this->Form->end(array('label' => __('Cauta'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
	</div>
</div>