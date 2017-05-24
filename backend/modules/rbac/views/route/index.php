<?php
use yii\helpers\Html;
use mdm\admin\AdminAsset;
use yii\helpers\Json;
use yii\helpers\Url;

/*
 * @var yii\web\View $this
 */
$this->title = Yii::t('rbac-admin', 'Routes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?= Html::a(Yii::t('rbac-admin', 'Create route'), ['create'], ['class' => 'btn btn-block btn-default'])?></h3>
			</div>
			<!-- /.box-header -->
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
								class="btn btn-block btn-info btn-flat">&gt;&gt;</a> <a href="#"
								id="btn-remove" class="btn btn-block btn-danger btn-flat">&lt;&lt;</a>
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
    yii.admin.searchRoute('avaliable');
});
$('#search-assigned').keydown(function () {
    yii.admin.searchRoute('assigned');
});
$('#btn-add').click(function () {
    yii.admin.assignRoute('assign');
    return false;
});
$('#btn-remove').click(function () {
    yii.admin.assignRoute('remove');
    return false;
});
$('#btn-refresh').click(function () {
    yii.admin.searchRoute('avaliable',1);
    return false;
});

yii.admin.searchRoute('avaliable', 0, true);
yii.admin.searchRoute('assigned', 0, true);
JS;
$this->registerJs($js);
