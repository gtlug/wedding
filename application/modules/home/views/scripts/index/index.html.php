<?php 
$this->headTitle('Welcome');
$this->headLink()->appendStylesheet('/css/home.css');
?>
<div id="outer">

	<div id="left">
		<h1>Lindsey White &amp;<br/>Adam VanBerlo<br />Wedding</h1>
		<p>Picture</p>
	</div>
	<div id="right">
		<div id="top">
			<div id="rsvp">
<?= $this->action('widget', 'index', 'rsvp') ?>
			</div>
		</div>
		<div id="bottom">
			<p style="float: left; width: 100px;" id="registryLink">
				<a 
					href="http://www.bedbathandbeyond.com/regGiftRegistry.asp?wrn=-1622868992&"
					title="Visit Registry"
					target="_blank">
					<img 
						src="/images/home/bedBathBeyondLogo.jpg" 
						alt="Visit Registry" 
						width="80" />
				</a>
			</p>
			<p style=""><a href="/info">More Information</a></p>
			<p><a href="/photos">View Photos</a></p>
			<div id="photos" style="padding-left: 20px; clear: left;">
<?= $this->action('widget', 'index', 'photos') ?>
			</div>		
		</div>
	</div>
</div>
