<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\admin\AdminAsset;
use yii\helpers\Json;
use yii\helpers\Url;
/*
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */
$this->title = '创建账户';
$this->params['breadcrumbs'][] = [
        'label' => '账户列表',
        'url' => [
                'index'
        ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">
	<p>
        <?= Html::a(Yii::t('rbac-admin', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary btn-flat'])?>
        <?php
        echo Html::a(Yii::t('rbac-admin', 'Delete'), 
                [
                        'delete',
                        'id' => $model->name
                ], 
                [
                        'class' => 'btn btn-danger',
                        'data-confirm' => Yii::t('rbac-admin', 
                                'Are you sure to delete this item?'),
                        'data-method' => 'post'
                ]);
        ?>
    </p>
    <?php
    echo DetailView::widget(
            [
                    'model' => $model,
                    'attributes' => [
                            'name',
                            'description:ntext'
                    ]
            ]);
    ?>
</div>