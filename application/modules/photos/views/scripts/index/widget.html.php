<div id="photosWidget">
<?php foreach($this->photos as $photo) { ?>
<img src="<?= $photo->Square->uri ?>" alt="<?= $photo->title ?>" style="float: left;" />
<?php } /*foreach(photos)*/ ?>
</div>