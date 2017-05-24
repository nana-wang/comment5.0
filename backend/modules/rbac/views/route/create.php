<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/*
 * @var yii\web\View $this
 * @var mdm\admin\models\Route $model
 * @var ActiveForm $form
 */

$this->title = Yii::t('rbac-admin', 'Create Route');
$this->params['breadcrumbs'][] = [
        'label' => Yii::t('rbac-admin', 'Routes'),
        'url' => [
                'index'
        ]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-users"></i>
				<h5 class="box-title">创建路由</h5>
				<span class="label label-primary pull-right"></span>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<?php $form = ActiveForm::begin(); ?>
            		<?= $form->field($model, 'route')?>
            		<div class="form-group">
            			<?= Html::submitButton(Yii::t('rbac-admin', 'Create'), ['class' => 'btn btn-primary btn-flat'])?>
            		</div>
            	<?php ActiveForm::end(); ?>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
	<!-- /.col -->
</div>
