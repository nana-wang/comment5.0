<?php

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
$this->title = Yii::t('rbac-admin', 'Create Permission');
$this->params['breadcrumbs'][] = [
        'label' => Yii::t('rbac-admin', 'Permissions'),
        'url' => [
                'index'
        ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">


	<?php

echo $this->render('_form', [
        'model' => $model,
        'menu' => $menu
]);
?>

</div>
