<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\admin\AdminAsset;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = [
        'label' => 'Permissions',
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
				<span class="label label-primary pull-right"></span>
				<ul class="nav nav-pills">
					<li><a href="index.php?r=rbac%2Fpermission%2Findex"> <i
							class="glyphicon glyphicon-chevron-left font-12"></i> 返回列表
					</a></li>
				</ul>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div class="auth-item-view">
					<p>
        <?= Html::a(Yii::t('rbac-admin', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary'])?>
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
                            'description:ntext',
                            'ruleName',
                            'data:ntext'
                    ]
            ]);
    ?>
    <div class="box-body">
						<div class="row">
							<div class="form-group  col-md-5">
            <?= Yii::t('rbac-admin', 'Avaliable') ?>:
            <input id="search-avaliable" class="form-control"> <a
									href="#" id="btn-refresh"><span
									class="glyphicon glyphicon-refresh"></span></a><br> <select
									id="list-avaliable" multiple size="20" class="form-control">
								</select>
								<p></p>
							</div>
							<div class="form-group col-md-2 ">
								<div class="" style="margin-top: 150px;">
									<label for="dwblacklistsearch-blacklist_uid"
										class="control-label"></label> <a href="#" id="btn-add"
										class="btn btn-block btn-info btn-flat">&gt;&gt;</a> <a
										href="#" id="btn-remove"
										class="btn btn-block btn-danger btn-flat">&lt;&lt;</a>
								</div>
							</div>
							<div class="form-group  col-md-5 pull-right">
            <?= Yii::t('rbac-admin', 'Assigned') ?>:
            <input id="search-assigned" class="form-control"><br> <select
									id="list-assigned" multiple size="20" class="form-control">
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	</div>
	<!-- /.col -->
</div>
<?php
AdminAsset::register($this);
$properties = Json::htmlEncode(
        [
                'roleName' => $model->name,
                'assignUrl' => Url::to([
                        'assign'
                ]),
                'searchUrl' => Url::to([
                        'search'
                ])
        ]);
$js = <<<JS
yii.admin.initProperties({$properties});

$('#search-avaliable').keydown(function () {
    yii.admin.searchRole('avaliable');
});
$('#search-assigned').keydown(function () {
    yii.admin.searchRole('assigned');
});
$('#btn-add').click(function () {
    yii.admin.addChild('assign');
    return false;
});
$('#btn-remove').click(function () {
    yii.admin.addChild('remove');
    return false;
});

yii.admin.searchRole('avaliable', true);
yii.admin.searchRole('assigned', true);
JS;
$this->registerJs($js);
