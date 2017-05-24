<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '配置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">
    <p>
        <?= Html::a('创建新配置', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </p>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'name',
                    [
                        'attribute' => 'type',
                        'value' => function ($model) {
                            return $model->getTypeList()[$model->type];
                        },
                    ],
                    'group',
                    ['class' => 'backend\widgets\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
