<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper" style="margin-left: 0px;">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>

    <section class="content">
        <?= \common\widgets\AlertPlus::widget()?>
        <?= $content ?>
    </section>
</div>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'alert-info',
    'header' => '<h3>提示</h3>',
    'footer' => \common\helpers\Html::button('确定', ['class' => 'btn btn-info', 'data-dismiss' => 'modal'])
])?>
<?php \yii\bootstrap\Modal::end()?>
<footer class="main-footer">
    <?= Yii::powered()?>
</footer>


<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>