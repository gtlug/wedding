<div style="width: 300px; padding: 0px; margin: 0px;">
<?php foreach($this->photos as $photo) { ?>
<img src="<?= $photo->Square->uri ?>" alt="<?= $photo->title ?>" style="float: left;" />
<?php } /*foreach(photos)*/ ?>
</div>