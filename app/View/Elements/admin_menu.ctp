<?php

$links = array(
	array(
		'title' => 'Statii',
		'url' => array('controller' => 'stations', 'action' => 'index'),
	),
	array(
		'title' => 'Linii',
		'url' => array('controller' => 'lines', 'action' => 'index'),
	),
	array(
		'title' => 'Timpi',
		'url' => array('controller' => 'times', 'action' => 'index'),
	),
	array(
		'title' => 'Log',
		'url' => array('controller' => 'log', 'action' => 'index'),
	),
	array(
		'title' => 'Bug-uri',
		'url' => array('controller' => 'bugs', 'action' => 'index'),
	),
	array(
		'title' => 'Sugestii',
		'url' => array('controller' => 'suggestions', 'action' => 'index'),
	),
	array(
		'title' => 'Configureaza ruta (BK)',
		'url' => array('controller' => 'routes', 'action' => 'configure', 'discover'),
	),
	array(
		'title' => 'Configureaza ruta (Cache)',
		'url' => array('controller' => 'routes', 'action' => 'configure', 'fetch'),
	),
);

?>

<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
</a>

<div class="nav-collapse collapse">
	<ul class="nav">
		<?php foreach ($links as $link): ?>
			<li <?php if (
				$this->request->params['controller'] == $link['url']['controller'] || 
				$this->request->params['action'] == 'admin_' . Inflector::singularize($link['url']['controller'])
			): ?>
				class="active"
			<?php endif; ?>>
				<?php echo $this->Html->link($link['title'], $link['url']); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>