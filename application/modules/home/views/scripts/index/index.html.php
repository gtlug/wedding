<?php 
$this->headTitle('Welcome');
$this->headLink()->appendStylesheet('/css/home.css');
?>
<div id="outer">

	<div id="left">
		<div id="homeLeftButtons">
			<a 
				id="moreInfoLink" 
					href="/info"><span>More Information</span></a>
			<a
				id="registryLink" 
				href="http://www.bedbathandbeyond.com/regGiftRegistry.asp?wrn=-1622868992"
				title="Visit Registry"
				target="_blank"><span>Gift Registry</span></a>
			
		</div>
	</div>
	
	<div id="right">
		<div id="top">
			<div id="rsvp">
<?= $this->action('widget', 'index', 'rsvp') ?>
			</div>
		</div>
		
		<div id="bottom">
			

			<div id="photos">
				<a id="viewPhotosLink" href="/photos"><span>View Photos</span></a>
<?= $this->action('widget', 'index', 'photos') ?>
			</div>		
		</div>
	</div>
</div>
