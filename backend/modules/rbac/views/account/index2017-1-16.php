<?php
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '账户群组管理 ';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title; ?>
<?php $this->endBlock() ?>
<div class="box box-primary">
			<div class="box-header with-border">
				<ul class="nav nav-pills">
						<li class="active"><a href="index.php?r=rbac%2Faccount%2Findex">
								账户列表 </a></li>
						<li><a href="index.php?r=rbac%2Faccount%2Fcreate">新建账户</a></li>
					</ul>
				<span class="label label-primary pull-right"></span>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
		<?= \backend\widgets\grid\TreeGrid::widget([
            'dataProvider' => $dataProvider,
            'keyColumnName' => 'id',
            'parentColumnName' => 'pid',
            'parentRootValue' => 0, //first parentId value
            'pluginOptions' => [
                'initialState' => '',
            ],
            'columns' => [
                'name',
                [
                        'attribute' => '操作',
                        'content' => function($dataProvider){
                        $str =  Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['update', 'id' => $dataProvider->id], ['class' => 'btn btn-xs btn-default', 'data-toggle' => 'tooltip', 'title' => '编辑账户']).'&nbsp;&nbsp;';
                        $str .=  Html::a('<i class="glyphicon glyphicon-eye-close font-12"></i>', ['stop', 'id' => $dataProvider->id], ['class' => 'btn btn-xs btn-default', 'data-toggle' => 'tooltip', 'title' => '停用账户','data-confirm'=>"您确定要停用此项吗？"]).'&nbsp;&nbsp;';
                        $str .=  Html::a('<i class="glyphicon glyphicon-remove font-12"></i>', ['delete', 'id' => $dataProvider->id], ['class' => 'btn btn-xs btn-default', 'data-toggle' => 'tooltip', 'title' => '删除账户','data-confirm'=>"您确定要删除此项吗？"]);
                       
                        return $str;
                         },
                ],
            ],
        ]); ?>					
			</div>
			<!-- /.box-body -->
			<!-- Loading (remove the following to stop the loading)-->
			<div class="overlay" id="show_loging" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<!-- end loading -->
		</div>