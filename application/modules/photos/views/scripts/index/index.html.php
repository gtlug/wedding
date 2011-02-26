<?php 
$this->headTitle('Photos');
$this->headLink()->appendStylesheet('/css/pages.css');
?>
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

<div class="controls">
<?= $this->placeholder('controls') ?>
</div>
