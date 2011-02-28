<?=$this->doctype(Zend_View_Helper_Doctype::XHTML1_TRANSITIONAL)?>
<?php if(false) {?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php } /*Trick IDE*/ ?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?= $this->headTitle($this->layout()->site)->setSeparator(' | ') ?>

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
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-7867887-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
