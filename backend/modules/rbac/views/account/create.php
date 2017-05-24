<?php

/*
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */
$this->title = "账户群组管理";
$this->params['breadcrumbs'][] = [
        'label' => '创建账户',
        'url' => [
                'index'
        ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">
	<?php

echo $this->render('_form', [
        'model' => $model
]);
?>
</div>
