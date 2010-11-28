<h1>Photos</h1>

<dl>
<?php foreach($this->photos as $photo) { ?>

	<dt><?= $photo->title ?></dt>
		<dd><img src="<?= $photo->Small->uri ?>" alt="<?= $photo->title ?>" /></dd>
<?php } /*foreach(photos)*/ ?>
</dl>