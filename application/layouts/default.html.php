<?php 
$this
	->headTitle($this->layout()->site)
	->setSeparator(' | ')
;
?>
<?=$this->doctype(Zend_View_Helper_Doctype::XHTML1_TRANSITIONAL)?>
<?php if(false) {?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php } /*Trick IDE*/ ?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?= $this->headTitle() ?>

<?= $this->headLink()->prependStylesheet('/css/main.css') ?>

<?= $this->headStyle() ?>
<script src="/js/prototype.js" type="text/javascript" language="javascript"></script>
<script src="/js/common.js" type="text/javascript" language="javascript"></script>
<?= $this->headScript() ?>
</head>

<body>
<div id="content">
<?= $this->layout()->content ?>

</div><!-- /#content -->
</body>
</html>
