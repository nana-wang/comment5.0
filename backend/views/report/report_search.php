<?php
use common\helpers\Html;
use yii\grid\GridView;
?>
<div class="box box-default">
	<div class="box-body">
		<?= GridView::widget([
	        'dataProvider' => $dataProvider,
	    	'emptyText' =>Yii::t('backend','Operation'), //没有数据
	        'columns' => [
	            'id',
	            'report_idtype',
	            'report_from_uid',
	            'report_uid',
				'report_content_title',
				'report_content',
				[
				'attribute' => 'report_create',
				'label'=>Yii::t('backend','Report Time'), //举报时间
				'value'=>
				function($model){
					return  date('Y-m-d H:i',$model->report_create);
				},
				],
				[
				'attribute' => 'report_status',
				'label'=>Yii::t('backend','Report State'),//'举报状态'
				'content'=>
				function($model){
					$audit = Yii::t('backend','Audit');
					$auditpass = Yii::t('backend','Audit Pass');
					$auditfail = Yii::t('backend','Audit Fail');
					if($model->report_status==1){
	                    return '<span class="label pull-left bg-red">'.$audit.'</span>' ;
					}elseif($model->report_status==2){
						return '<span class="label pull-left bg-yellow">'.$auditpass.'</span>' ;
					}elseif($model->report_status==3){
						return '<span class="label pull-left bg-blue">'.$auditfail.'</span>' ;
					}
				},
				],
				['class' => 'yii\grid\ActionColumn',
	            'header' => Yii::t('backend','Operation'), //'操作'
				'template' => '{delete} {view}',
				'buttons' => [
				'view' => function ($url, $model) {
					$view = Yii::t('backend','Views');
					return '<a  class="view" data="'.$model->id.'" href="javascript:void(0);" title="'.$view.'" aria-label="'.$view.'"><span class="glyphicon glyphicon-eye-open"></span></a>';
				}
			    ],
			    'urlCreator' => function ($action, $model, $key, $index) {
						if ($action === 'delete') {
							return ['repdel', 'id' => $model->id];
						} 
					}
	            ],
	        ],
	    ]); ?>
	</div>		
</div>