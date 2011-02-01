<?php 
$this->headStyle()->appendStyle('@import url(/css/home.css);');
?>
<div id="outer">

	<div id="left">
		<h1>Adam VanBerlo &amp; Lindsey White <br />Wedding</h1>
		<p>Picture</p>
	</div>
	<div id="right">
		<div id="top">
			<div id="rsvp">
<?= $this->action('widget', 'index', 'rsvp') ?>
			</div>
		</div>
		<div id="bottom">
			<p><a href="#">Visit Registry</a></p>
			<p><a href="/photos">View Photos</a></p>
			<div id="photos" style="padding-left: 20px;">
<?= $this->action('widget', 'index', 'photos') ?>
			</div>		
		</div>
	</div>
</div>
