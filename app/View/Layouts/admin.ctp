<!DOCTYPE html>
<html lang="en">
  <head>
		<meta charset="utf-8">
		<title><?php echo $title_for_layout; ?></title>
		<meta name="description" content="">
		<meta name="author" content="">
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<?php echo $this->fetch('meta'); ?>

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le styles -->
		<?php echo $this->Html->css('bootstrap'); ?>
		<?php echo $this->Html->css('bootstrap-responsive'); ?>
		<?php echo $this->Html->css('admin'); ?>
		<style>
			body {
			 /* padding-top: 60px;*/ /* 60px to make the container go all the way to the bottom of the topbar */
			}
		</style>

		<!-- Le fav and touch icons -->
		<?php echo $this->Html->meta('favicon.ico', '/favicon.ico', array('rel' => 'shortcut icon')); ?>
		<?php echo $this->Html->meta('apple-touch-icon', '/apple-touch-icon.png', array('rel' => 'apple-touch-icon')); ?>
		<?php echo $this->Html->meta('apple-touch-icon', '/apple-touch-icon-72x72.png', array('rel' => 'apple-touch-icon', 'sizes' => '72x72')); ?>
		<?php echo $this->Html->meta('apple-touch-icon', '/apple-touch-icon-114x114.png', array('rel' => 'apple-touch-icon', 'sizes' => '114x114')); ?>
  </head>
  <body>
		<div class="navbar">
			<div class="navbar-inner">
				<div class="container-fluid">				
					<?php echo $this->Html->link('Prin Timisoara', array('controller' => 'users', 'action' => 'dashboard'), array('class' => 'brand')); ?>
					
					<?php echo $this->element('admin_menu'); ?>
				</div>
			</div>
		</div>
	
		<div class="container-fluid">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
			<br>
			<div class="row-fluid">
				<?php echo $this->element('sql_dump'); ?>
			</div>
		</div> <!-- /container -->
	
		<!-- Le javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<?php echo $this->Html->script('libs/jquery-1.7.2.min'); ?>
		<?php echo $this->Html->script('libs/bootstrap'); ?>
		<?php echo $this->Html->script('script'); ?>
		<?php echo $this->fetch('script'); ?>
		<?php echo $this->Js->writeBuffer(); ?>
		<?php echo $this->fetch('css'); ?>
  </body>
</html>