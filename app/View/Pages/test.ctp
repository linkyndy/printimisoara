<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_routes_configure', array('inline' => false)); ?>

<!--<h2>O scurta descriere - rezumatul rutei</h2>

<p>
	Text normal
	<strong>Text strong</strong>
	<em>Text em</em>
	<big>Text big</big>
	<small>Text small</small>
</p>

<u>Linia 1</u>

<time datetime="15:40">2 min</time>
<time datetime="20:39">20:39</time>

<ul>
	<li>Lista</li>
	<li>Lista</li>
	<li>Lista</li>
	<li>Lista</li>
</ul>

<aside>Harta</aside>

<a href="#">Link</a>

<button>Buton</button>

<form>
	<label>Label</label>
	<input type='text'>
	<input type='datetime'>
</form>

<article>
	<u class="blue">1</u>
	<u class="pink">2</u>
	<u class="grey">3</u>
	<u class="yellow">4</u>
	<u class="grass">5</u>
	<u class="orange">6</u>
	<u class="purple">7</u>
	<u class="grey">8</u>
	<u class="brown">9</u>
	<u class="orange">11</u>
	<u class="blue">14</u>
	<u class="pink">15</u>
	<u class="sky">17</u>
	<u class="green">18</u>
	<u class="grass">19</u>
	<u class="candy">21</u>
	<u class="brown">28</u>
	<u class="green">32</u>
	<u class="yellow">33</u>
	<u class="purple">40</u>
	<u class="sky">46</u>
	<u class="pink">E1</u>
	<u class="orange">E2</u>
	<u class="green">E3</u>
	<u class="blue">E4</u>
	<u class="grey">E5</u>
	<u class="candy">E6</u>
	<u class="dirt">E7</u>
	<u class="grass">E8</u>
	<u class="yellow">M30</u>
	<u class="orange">M35</u>
	<u class="cream">M36</u>
</article>

<figure><img src="http://placehold.it/350x150"></figure>-->
<figure>
	<img id="map">
</figure>

<!--<h2>Grabeste-te! In <strong>3 minute</strong> trebuie sa ajungi in statia <strong>Piata 700</strong>. Vei ajunge in <strong>4 minute</strong> la <strong>Iulius Mall</strong>. Calatoria te costa <strong>2lei</strong></h2>-->

<h2>Vei pleca la 20:34 si vei ajunge la 21:07 (33 minute, 12 statii).</h2>

<section id="routes-container">
	<table class="zebra">
		<tr>
			<td><u class="yellow">4</u></td>
			<td><big>Calea Torontalului</big><em>Piata 700 <small> - 10 statii</small></em></td>
			<td><time datetime="17:50">17:50</time> <small>- <time datetime="17:59">17:59</time></small></td>
		</tr>
		<!--<tr>
			<td colspan="2">Tramvaiul pleaca la 17:50 din statie si ajunge in 10 minute. Cobori la a 6-a statie.</td>
		</tr>-->
		<tr>
			<td><u class="pink">2</u></td>
			<td><big>Piata 700</big><em>Piata Maria <small>- 1 statie</small></em></td>
			<td><time datetime="18:10">18:10</time> <small>- <time datetime="18:33">18:33</time></small></td>
		</tr>
		<tr>
			<td><u class="orange">M35</u></td>
			<td><big>Ghiroda</big><em>Punctele Cardinale <small>- 5 statii</small></em></td>
			<td><time datetime="22:22">22:22</time> <small>- <time datetime="22:59">22:59</time></small></td>
		</tr>
		<tr>
			<td><u class="purple">7</u></td>
			<td><big>Piata Maria</big><em>Strada Drubeta <small>- 8 statii</small></em></td>
			<td><time datetime="23:39">23:39</time> <small>- <time datetime="23:59">23:59</time></small></td>
		</tr>
		<tr>
			<td><u class="grey">E5</u></td>
			<td><big>Catedrala Mitropolitana</big><em>Real/Praktiker <small>- 22 statii</small></em></td>
			<td><time datetime="11:11">11:11</time> <small>- <time datetime="12:59">12:59</time></small></td>
		</tr>
	</table>
</section>

<aside>
	<nav>
		<ul>
			<li>
				<a href="#">
					<ol>
						<li><u class="purple">7</u></li>
						<li><u class="grey">E5</u></li>
						<li><u class="orange">M35</u></li>
						<li><small>10 minute, 5 statii</small></li>
					</ol>
				</a>
			</li>
			<li>
				<a href="#">
					<ol>
						<li><u class="yellow">4</u></li>
						<li><small>1 minut, 1 statie</small></li>
					</ol>
				</a>
			</li>
			<li>
				<a href="#">
					<ol>
						<li><u class="blue">1</u></li>
						<li><u class="pink">2</u></li>
						<li><small>1 ora 10 minute, 25 statii</small></li>
					</ol>
				</a>
			</li>
			<li>
				<a href="#" class="button">Recalculeaza rute</a>
			</li>
		</ul>
	</nav>
</aside>

<!--<aside><img src="http://placehold.it/350x150"></aside>

<article>
	<p style="font-family:NovecentowideBold">Font test</p>
	<p style="font-family:PTSansRegular">Font test</p>
	<p style="font-family:PTSansBold">Font test</p>
	<p style="font-family:PTSansItalic">Font test</p>
	<p style="font-family:PTSansBoldItalic">Font test</p>
	<p style="font-family:PTSansCaptionRegular">Font test</p>
	<p style="font-family:PTSansCaptionBold">Font test</p>
	<p style="font-family:PTSansNarrowRegular">Font test</p>
	<p style="font-family:PTSansNarrowBold">Font test</p>
</article>

<article>
	<header>
		<h1>article header h1</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec.</p>
	</header>
	<section>
		<h2>article section h2</h2>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec. Curabitur posuere enim eget turpis feugiat tempor. Etiam ullamcorper lorem dapibus velit suscipit ultrices. Proin in est sed erat facilisis pharetra.</p>
	</section>
	<section>
		<h2>article section h2</h2>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec. Curabitur posuere enim eget turpis feugiat tempor. Etiam ullamcorper lorem dapibus velit suscipit ultrices. Proin in est sed erat facilisis pharetra.</p>
	</section>
	<footer>
		<h3>article footer h3</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec. Curabitur posuere enim eget turpis feugiat tempor.</p>
	</footer>
</article>

<aside>
	<h3>aside</h3>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec. Curabitur posuere enim eget turpis feugiat tempor. Etiam ullamcorper lorem dapibus velit suscipit ultrices.</p>
</aside>-->