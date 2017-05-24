<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
  <div class="col-sm-12">
    <div class="box box-primary">
      <div class="box-header with-border"> <span class="label label-primary pull-right"></span>
        <ul class="nav nav-pills">
          <li class="active"><a href="index.php?r=user%2Findex"> 用户列表 </a></li>
          <li> <?= Html::a('创建新用户', ['create'], ['class' => '']) ?></li>
        </ul>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [

                    'id',
                    'username',
                    // 'auth_key',
                    // 'password_hash',
                    // 'password_reset_token',
                    'email',
                    // 'status',
                     'created_at:datetime',
                     'login_at:datetime',

                    [
                        'class' => 'backend\widgets\grid\ActionColumn',
                        'template' => '{view} {update} {assign}{delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', [
                                    'update',
                                    'id' => $model->id,
                                ], [
                                    'title' => Yii::t('yii', 'Update'),
                                    'aria-label' => Yii::t('yii', 'Update'),
                                    'data-pjax' => '0',
                                ]);
                            },
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', [
                                    'view',
                                    'id' => $model->id,
                                ], [
                                    'title' => Yii::t('yii', 'View'),
                                    'aria-label' => Yii::t('yii', 'View'),
                                    'data-pjax' => '0',
                                ]);
                            },
                            'assign' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-hand-left"></span>', [
                                    '/rbac/assignment/view',
                                    'id' => $model->id,
                                ], [
                                    'title' => '分配',
                                    'aria-label' => '分配',
                                    'data-pjax' => '0',
                                ]);
                            },
                            'delete' => function ($url, $model) {
                            	return Html::a('<span class="glyphicon glyphicon-trash"></span>', [
                            			'delete',
                            			'id' => $model->id,
                            			], [
                            			'title' => '删除',
                            			'aria-label' => '删除',
                            			'data-pjax' => '0',
										'data-confirm'=>Yii::t('backend', 'Sure Delete'),
                            			]);
                            },
                        ],
                    ]
                ],
            ]); ?>
        <!-- /.box-body --> 
      </div>
      <!-- /.box --> 
    </div>
    <!-- /.col --> 
  </div>
</div>