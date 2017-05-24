<?php
use yii\helpers\Html;


/* @var $this \yii\web\View */
/* @var $content string */

backend\assets\AppAsset::register($this);

?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags()?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head()?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody()?>
<div class="wrapper">
    <?=$this->render('header.php')?>
    <?=$this->render('left.php')?>
    <?=$this->render('content.php', ['content' => $content])?>
</div>
<?php if (isset($this->blocks['js'])): ?>
    <?= $this->blocks['js']?>
<?php endif; ?>
<?php $this->endBody()?>
<script>
//按功能模块加载不同js
function account_location(){
	window.location.href="http://focus.dwnews.com/admin/index.php?r=comment%2Ffourm";
}
</script>
</body>
</html>
<?php $this->endPage()?>

