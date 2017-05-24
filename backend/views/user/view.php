<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['/admin/assignment']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<span class="label label-primary pull-right"></span>
				<ul class="nav nav-pills">
					<li><a href="index.php?r=user%2Findex"> <i
							class="glyphicon glyphicon-chevron-left font-12"></i> 返回列表
					</a></li>
				</ul>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email',
            [
                'attribute' => 'status',
                'value' => $model->getStatusList()[$model->status]
            ],
            'created_at:datetime',
            'login_at:datetime',
        ],
    ]) ?>
    
				</div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
	<!-- /.col -->
</div>
