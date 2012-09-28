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
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width">

	<?php echo $this->Html->css('style'); ?>

	<?php echo $this->Html->script('libs/modernizr-2.5.3-respond-1.1.0.min'); ?>
</head>
<body>
	<div id="header-container">
		<header class="wrapper clearfix">
			<h1 class="logo">PRIN ORAS</h1>
		</header>
	</div>
	<div id="main-container">
		<div id="main" class="wrapper clearfix">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
			<br><article class="clearfix"><?php echo $this->element('sql_dump'); ?></article>			
		</div> <!-- #main -->
	</div> <!-- #main-container -->

	<div id="footer-container">
		<footer class="wrapper">
			<nav>
				<ul>
					<li><h3 class="logo">PRIN ORAS</h3></li>
					<li>&copy; 2012</li>
					<li><a href="#">Despre noi</a></li>
					<li><a href="#">Termeni si conditii</a></li>
					<li><a href="#">Raporteaza</a></li>
				</ul>
			</nav>
		</footer>
	</div>

<?php echo $this->Html->script("//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"); ?>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
<?php echo $this->Html->script('libs/jquery.lettering'); ?>
<?php echo $this->fetch('script'); ?>
<?php echo $this->Html->script('script'); ?>
<?php echo $this->Js->writeBuffer(); ?>
<?php echo $this->fetch('css'); ?>

</body>
</html>