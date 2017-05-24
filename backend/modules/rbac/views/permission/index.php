<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\AuthItem */

$this->title = Yii::t('rbac-admin', 'Permission');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<span class="label label-primary pull-right"></span>
				<ul class="nav nav-pills">
					<li class="active"><a href="index.php?r=rbac%2Faccount%2Findex">
							权限列表 </a></li>
					<li><?= Html::a(Yii::t('rbac-admin', 'Create Permission'), ['create'], ['class' => ''])?></li>
				</ul>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<?php
    Pjax::begin([
            'enablePushState' => false
    ]);
    echo GridView::widget(
            [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                            [
                                    'class' => 'yii\grid\SerialColumn'
                            ],
                            [
                                    'attribute' => 'name',
                                    'label' => Yii::t('rbac-admin', 'Name')
                            ],
                            [
                                    'attribute' => 'description',
                                    'label' => Yii::t('rbac-admin', 
                                            'Description')
                            ],
                            [
                                    'class' => 'backend\widgets\grid\ActionColumn'
                            ]
                    ]
            ]);
    Pjax::end();
    ?>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
</div>