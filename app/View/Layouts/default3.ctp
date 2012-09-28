<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php echo $title_for_layout; ?></title>
  <meta name="description" content="">

  <meta name="viewport" content="width=device-width">
  <?php echo $this->Html->css('style3'); ?>
	
	<?php echo $this->Html->script('libs/modernizr-2.5.3.min'); ?>
</head>
<body>
  <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
  <header>

  </header>
  <div role="main" id="main" class="clearfix">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
  </div>
  <footer>

  </footer>
	
	<?php echo $this->Html->script("//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"); ?>
	<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
	<?php echo $this->Html->script('libs/jquery-ui-1.8.21.custom.min'); ?>
	<?php echo $this->fetch('script'); ?>
	<?php echo $this->Html->script('script'); ?>
	<?php echo $this->Js->writeBuffer(); ?>
	<?php echo $this->fetch('css'); ?>
	<link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700' rel='stylesheet' type='text/css'>

</body>
</html>