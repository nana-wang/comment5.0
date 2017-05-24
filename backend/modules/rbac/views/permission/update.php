<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use mdm\admin\AdminAsset;
use mdm\admin\AutocompleteAsset;
/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */
$this->title = Yii::t('rbac-admin', 'Update Permission') . ': ' . $model->name;
$this->params['breadcrumbs'][] = [
        'label' => Yii::t('rbac-admin', 'Permissions'),
        'url' => [
                'index'
        ]
];
$this->params['breadcrumbs'][] = [
        'label' => $model->name,
        'url' => [
                'view',
                'id' => $model->name
        ]
];
$this->params['breadcrumbs'][] = Yii::t('rbac-admin', 'Update');
?>
<div class="auth-item-update">

	<?php
echo $this->render('_form', 
        [
                'model' => $model,
                'optionid' => $optionid,
                'menu' => $menu,
                'permission_id' => $permission_id
        ]);
?>
</div>