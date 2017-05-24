<?php
use common\helpers\Html;
use yii\grid\GridView;
?>
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title"><?=Yii::t('backend','List Infor');?></h3>
			</div>
			<div class="box-body">
			
			<?= GridView::widget([
	        'dataProvider' => $dataProvider,
	    	'emptyText' =>Yii::t('backend','No Data'),
	        'columns' => [
	            'id',
			[
			'attribute' => 'sensitive_level_id',
			'label'=>Yii::t('backend','Sensitive Level'),
			'value'=>
			function ($model) {
				return \backend\models\DwSensitiveLevel::get_level($model->sensitive_level_id,'sensitive_name');
			},
			],
            'sensitive_name',
			'sensitive_replace',
			[
			'attribute' => 'sensitive_action',
			'label'=>Yii::t('backend','Sensitive Do'),
			'value'=>
			function($model){
				$prohibit = Yii::t('backend','Prohibit');
				$toexamine = Yii::t('backend','To Examine');
				$replace = Yii::t('backend','Replace');
				if($model->sensitive_action==1){
                    return $prohibit;
				}elseif($model->sensitive_action==2){
					//return Html::style('<span class=\"label pull-left bg-red\">审核</small>');
					return $toexamine;
				}elseif($model->sensitive_action==3){
					return $replace; //'替换'
				}
			},
			],
			[
			'attribute' => 'sensitive_time',
			'label'=>Yii::t('backend','Operation Time'), //'操作时间'
			'value'=>
			function($model){
				return  date('Y-m-d H:i',$model->sensitive_time);   //主要通过此种方式实现
			},
			],
            ['class' => 'yii\grid\ActionColumn',
			'header' => Yii::t('backend','Operation'), //'操作'
			'template' => '{delete} {view}',
			'buttons' => [
				'view' => function ($url, $model) {
					$view = Yii::t('backend','Views');
					return '<a  class="view_manage"  onclick="sensitive_view('.$model->id.');" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
				}
				],
			'urlCreator' => function ($action, $model, $key, $index) {
				if ($action === 'delete') {
					return ['sensitive_del', 'id' => $model->id];
				} 
			}],
        ],
    ]); ?>
			</div>
			<!-- /.box-body -->
			<!-- Loading (remove the following to stop the loading)-->
			<div class="overlay" style="display: none;" id="show_loading">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<!-- end loading -->
		</div>
		<!-- /.box -->
