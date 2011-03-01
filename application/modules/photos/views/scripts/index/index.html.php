<?php 
$this->headTitle('Photos [Page ' . $this->photos->getCurrentPageNumber() .  ' of ' . $this->photos->count() . ']');
$this->headLink()->appendStylesheet('/css/pages.css');
?>
<div id="photosOuter">
	<div id="photosHeader">
		<a class="homeButton" href="/home/"><span>Home Button</span></a>
	</div>
	<div id="photosContent">
		<h1>Photos</h1>
		
		<div class="controls">
		<?php $this->placeholder('controls')->captureStart(); ?>
		<div class="pages">
		<?= $this->paginationControl($this->photos, null, 'pagination.html.php', array('baseUri'=>'/photos/index/index')) ?>
		</div>
		<?php $this->placeholder('controls')->captureEnd(); ?>
		<?= $this->placeholder('controls') ?>
		</div>
		
		<dl>
		<?php foreach($this->photos as $photo) { ?>
		
			<dt><?= $photo->title ?></dt>
				<dd><a href="<?= $photo->Original->uri ?>"><img src="<?= $photo->Small->uri ?>" alt="<?= $photo->title ?>" /></a></dd>
		<?php } /*foreach(photos)*/ ?>
		</dl>
		<div style="clear:both;"></div>
		<div class="controls">
		<?= $this->placeholder('controls') ?>
		</div>
	</div>
	<div style="clear:both; background-color:#000;">&nbsp;</div>
	<div id="footer">&nbsp;</div>
</div>