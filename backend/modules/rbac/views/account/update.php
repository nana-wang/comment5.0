<?php

/*
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */
$this->title = '账户管理';
$this->params['breadcrumbs'][] = [
        'label' => '更新账户',
        'url' => [
                'index'
        ]
];
?>
<div class="auth-item-update">

	<?php
echo $this->render('_form', 
        [
                'model' => $model,
                'optionid' => $optionid
        ]);
?>
</div>
