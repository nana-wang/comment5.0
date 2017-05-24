<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\themes\basic\assets\AppAsset;

AppAsset::register($this);
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => isset($this->params['SEO_SITE_KEYWORDS']) ? $this->params['SEO_SITE_KEYWORDS'] : Yii::$app->config->get('SEO_SITE_KEYWORDS')
], 'keywords');
$this->registerMetaTag([
    'name' => 'description',
    'content' => isset($this->params['SEO_SITE_DESCRIPTION']) ? $this->params['SEO_SITE_DESCRIPTION'] : Yii::$app->config->get('SEO_SITE_DESCRIPTION')
], 'description');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link type="image/x-icon" href="<?= Yii::getAlias('@web') ?>favicon.ico" rel="shortcut icon">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>
<?= $this->render('_footer') ?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'commonModal',
    'header' => '<h4>提示</h4>',
    'footer' => \common\helpers\Html::button('确定', ['class' => 'btn btn-info', 'data-dismiss' => 'modal'])
])?>
<?php \yii\bootstrap\Modal::end()?>
<!--回到顶部-->
<?= \common\widgets\scroll\Scroll::widget()?>
<?= Yii::$app->config->get('FOOTER')?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
