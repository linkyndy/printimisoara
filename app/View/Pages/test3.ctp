<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_routes_configure', array('inline' => false)); ?>

<section id="content-container">
	<h2>Rute</h2>
	<ul class="accordion">
		<li>
			<a>
				<span class="">3 linii, 15 statii</span>
				<span class="">in 10 min</span>
				<!--<span class="line route-line yellow-bg">4</span>
				<span class="line route-line orange-bg">33</span>
				<span class="line route-line red-bg">E5</span>
				<span class="time time-exact">20:38</span>
				<span class="time time-relative">5 min</span>-->
			</a>
			<ol>
				<li class="clearfix">
					<a>
						<div class="lines">
							<span class="line route-line yellow-bg">4</span>
						</div>
						<div class="stations">
							<span class="station">Piata 700</span>
							<span class="station">pana la AEM (12 statii)</span>
						</div>
						<div class="times">
							<span class="time time-exact">20:38</span>
							<span class="time time-exact">20:58</span>
						</div>
					</a>
				</li>
				<li class="clearfix">
					<a>
						<div class="lines">
							<span class="line route-line orange-bg">33</span>
						</div>
						<div class="stations">
							<span class="station">Catedrala Mitropolitana</span>
							<span class="station">pana la Calea Torontalului (10 statii)</span>
						</div>
						<div class="times">
							<span class="time time-exact">22:05</span>
							<span class="time time-exact">22:58</span>
						</div>
					</a>
				</li>
			</ol>
		</li>
		<li>
			<a>
				<span class="line route-line blue-bg">14</span>
				<span class="line route-line cyan-bg">E3</span>
				<span class="line route-line lime-bg">46</span>
				<span class="line route-line pink-bg">1</span>
				<span class="time time-exact">23:08</span>
				<span class="time time-relative">1 ora</span>
			</a>
		</li>
		<li>
			<a>
				<span class="line route-line green-bg">40</span>
				<span class="line route-line violet-bg">32</span>
				<span class="line route-line purple-bg">E7</span>
				<span class="time time-exact">13:28</span>
				<span class="time time-relative">1 ora, 5 min</span>
			</a>
		</li>
		<!--<li><a href="#"><span class="icon icon-send">&nbsp;</span>Trimite ruta</a></li>
		<li><a href="#"><span class="icon icon-refresh">&nbsp;</span>Reimprospateaza timpii</a></li>
		<li><a href="#"><span class="icon icon-new">&nbsp;</span>Calculeaza alta ruta</a></li>
		<li>
			<a href="#">Linia 4</a>
			<ul>
				<li>
					Linia 4      20:03
					Calea Torontalului - Piata 700 (5 statii)
				</li>
				<li>bla</li>
				<li>bla</li>
			</ul>
		</li>-->
	</ul>
	<!--<h2>Rute</h2>
	<ul class="accordion">
		<li>
			<a href="#">Linia 4</a>
			<ul>
				<li>
					Linia 4      20:03
					Calea Torontalului - Piata 700 (5 statii)
				</li>
				<li>bla</li>
				<li>bla</li>
			</ul>
		</li>
		<li>
			<a href="#">bulina linie 1, bulina linie 2</a>
			<ul>
				<li>bla</li>
				<li>bla</li>
				<li>bla</li>
			</ul>
		</li>
		<li>
			<a href="#"><span class="line">&nbsp;</span> Linia 4 <span class="line orange">&nbsp;</span></a>
			<ul>
				<li>bla</li>
				<li>bla</li>
				<li>bla</li>
			</ul>
		</li>
	</ul>
	
	<div class="button-container">
		<button class="button">Recalculeaza rutele</button>
		<button class="button-green">Sterge rezultatele</button>
	</div>-->
</section>

<section id="map-container">
	<figure id="map"></figure>
</section>