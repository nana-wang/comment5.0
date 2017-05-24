<?php

use yii\helpers\Html;
use yii\grid\GridView;
use function GuzzleHttp\json_encode;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建新用户', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </p>
    <div class="box box-primary">
        <div class="box-body">
        <div class="col-sm-4">
          <h5>用户信息</h5>
          <div class="treeview" id="treeview3"><ul class="list-group"><li class="list-group-item node-treeview3" data-nodeid="0" style="color:undefined;background-color:undefined;"><span class="icon expand-icon glyphicon glyphicon-minus"></span><span class="icon node-icon"></span>Parent 1</li><li class="list-group-item node-treeview3" data-nodeid="1" style="color:undefined;background-color:undefined;"><span class="indent"></span><span class="icon expand-icon glyphicon glyphicon-minus"></span><span class="icon node-icon"></span>Child 1</li><li class="list-group-item node-treeview3" data-nodeid="2" style="color:undefined;background-color:undefined;"><span class="indent"></span><span class="indent"></span><span class="icon glyphicon"></span><span class="icon node-icon"></span>Grandchild 1</li><li class="list-group-item node-treeview3" data-nodeid="3" style="color:undefined;background-color:undefined;"><span class="indent"></span><span class="indent"></span><span class="icon glyphicon"></span><span class="icon node-icon"></span>Grandchild 2</li><li class="list-group-item node-treeview3" data-nodeid="4" style="color:undefined;background-color:undefined;"><span class="indent"></span><span class="icon glyphicon"></span><span class="icon node-icon"></span>Child 2</li><li class="list-group-item node-treeview3" data-nodeid="5" style="color:undefined;background-color:undefined;"><span class="icon glyphicon"></span><span class="icon node-icon"></span>Parent 2</li><li class="list-group-item node-treeview3" data-nodeid="6" style="color:undefined;background-color:undefined;"><span class="icon glyphicon"></span><span class="icon node-icon"></span>Parent 3</li><li class="list-group-item node-treeview3" data-nodeid="7" style="color:undefined;background-color:undefined;"><span class="icon glyphicon"></span><span class="icon node-icon"></span>Parent 4</li><li class="list-group-item node-treeview3" data-nodeid="8" style="color:undefined;background-color:undefined;"><span class="icon glyphicon"></span><span class="icon node-icon"></span>Parent 5</li></ul></div>
        </div>
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
                        'template' => '{view} {update} {assign}',
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
                            'assign' => function ( $model) {
                            return '<a data-pjax="0" aria-label="子账户" title="子账户" href="javascript:void(0)" ><span class="glyphicon glyphicon-user get_sub_account"></span></a>';
                            return Html::a('<span class="glyphicon glyphicon-user"></span>', [
                                'javascript:get_sub_account(1);',
                                'id' => $model->id,
                            ], [
                                'title' => '子账户',
                                'aria-label' => '子账户',
                                'data-pjax' => '0',
                            ]);
                            },
                        ],
                    ]
                ],
            ]); 
$js = <<<JS
  		$(function() {
         var defaultData = [
          {
            text: '测试 1',
            href: '#parent1',
            tags: ['4'],
            nodes: [
              {
                text: 'Child 1',
                href: '#child1',
                tags: ['2'],
                nodes: [
                  {
                    text: 'Grandchild 1',
                    href: '#grandchild1',
                    tags: ['0']
                  },
                  {
                    text: 'Grandchild 2',
                    href: '#grandchild2',
                    tags: ['0']
                  }
                ]
              },
              {
                text: 'Child 2',
                href: '#child2',
                tags: ['0']
              }
            ]
          },
          {
            text: 'Parent 2',
            href: '#parent2',
            tags: ['0']
          },
          {
            text: 'Parent 3',
            href: '#parent3',
             tags: ['0']
          },
          {
            text: 'Parent 4',
            href: '#parent4',
            tags: ['0']
          },
          {
            text: 'Parent 5',
            href: '#parent5'  ,
            tags: ['0']
          }
        ];

        var alternateData = [
          {
            text: 'Parent 1',
            tags: ['2'],
            nodes: [
              {
                text: 'Child 1',
                tags: ['3'],
                nodes: [
                  {
                    text: 'Grandchild 1',
                    tags: ['6']
                  },
                  {
                    text: 'Grandchild 2',
                    tags: ['3']
                  }
                ]
              },
              {
                text: 'Child 2',
                tags: ['3']
              }
            ]
          },
          {
            text: 'Parent 2',
            tags: ['7']
          },
          {
            text: 'Parent 3',
            icon: 'glyphicon glyphicon-earphone',
            href: '#demo',
            tags: ['11']
          },
          {
            text: 'Parent 4',
            icon: 'glyphicon glyphicon-cloud-download',
            href: '/demo.html',
            tags: ['19'],
            selected: true
          },
          {
            text: 'Parent 5',
            icon: 'glyphicon glyphicon-certificate',
            color: 'pink',
            backColor: 'red',
            href: 'http://www.tesco.com',
            tags: ['available','0']
          }
        ];
        var json = '[' +
          '{' +
            '"text": "Parent 1",' +
            '"nodes": [' +
              '{' +
                '"text": "Child 1",' +
                '"nodes": [' +
                  '{' +
                    '"text": "Grandchild 1"' +
                  '},' +
                  '{' +
                    '"text": "Grandchild 2"' +
                  '}' +
                ']' +
              '},' +
              '{' +
                '"text": "Child 2"' +
              '}' +
            ']' +
          '},' +
          '{' +
            '"text": "Parent 2"' +
          '},' +
          '{' +
            '"text": "Parent 3"' +
          '},' +
          '{' +
            '"text": "Parent 4"' +
          '},' +
          '{' +
            '"text": "Parent 5"' +
          '}' +
        ']';
                            
        $('#treeview3').treeview({
          levels: 1,
          data: defaultData
        });
  		});
JS;
            $this->registerJs($js);
            ?>
            
        </div>
    </div>
</div>        


