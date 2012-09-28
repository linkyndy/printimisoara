<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_routes_configure', array('inline' => false)); ?>

<figure id="map-container">
	<img id="map">
</figure>

<aside id="routes-container">
	<header>
		<h1 class="logo">PRIN ORAS</h1>
	</header>
	
	<h3>Traseu</h3>
	
	<table id="route">
		<tr>
			<td><span class="line yellow">4</span></td>
			<td>
				<strong>Calea Torontalului</strong> <small>spre Piata 700</small><br>
				<small>10 statii, ~11 minute</small></td>
			<td>
				<time datetime="20:56">20:56</time><br>
				<small><time datetime="21:07">21:07</time></small>
			</td>
		</tr>
		<tr>
			<td><span class="line orange">11</span></td>
			<td>
				<strong>Piata 700</strong> <small>spre Gara de Nord</small><br>
				<small>3 statii, ~5 minute</small></td>
			<td>
				<time datetime="21:22">21:22</time><br>
				<small><time datetime="21:27">21:27</time></small>
			</td>
		</tr>
		<tr>
			<td><span class="line cream">M36</span></td>
			<td>
				<strong>Piata Alexandru Mocioni</strong> <small>spre Sinmihaiu German</small><br>
				<small>13 statii, ~25 minute</small></td>
			<td>
				<time datetime="22:22">22:22</time><br>
				<small><time datetime="22:47">22:47</time></small>
			</td>
		</tr>
	</table>
	
	<h3>Rute alternative <a href="#">Recalculeaza</a></h3>
	
	<ul id="alternate-routes">
		<li>
			<a href="#">
				<span class="line yellow">4</span>
				<span class="line purple route">7</span>
				<span class="duration">23 minute</span>
			</a>
		</li>
		<li>
			<a href="#">
				<span class="line orange">11</span>
				<span class="duration">2 minute</span>
			</a>
		</li>
		<li>
			<a href="#">
				<span class="line cream">M36</span>
				<span class="line green route">E3</span>
				<span class="line sky route">17</span>
				<span class="duration">59 minute</span>
			</a>
		</li>
		<li>
			<a href="#">
				<span class="line blue">1</span>
				<span class="line pink route">2</span>
				<span class="line grey route">3</span>
				<span class="line grass route">5</span>
				<span class="line brown route">9</span>
				<span class="line candy route">21</span>
				<span class="line dirt route">E7</span>
				<span class="duration">59 minute</span>
			</a>
		</li>
	</ul>
	
	<footer>
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
</aside>