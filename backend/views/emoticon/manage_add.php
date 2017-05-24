<?php
use common\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $model common\models\Article */
/* @var $module string */

$this->title = '添加或批量导入敏感词';
$this->params['breadcrumbs'][] = [
    'label' => '敏感词',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header')?>
<?= $this->title;?>
<?php $this->endBlock()?>
<div class="comment-index">

	<div class="box box-primary">
		<div class="box-body">
			<div id="w0" class="grid-view">
				<div class="summary">
					第<b>1-2</b>条，共<b>2</b>条数据.
				</div>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Id</th>
							<th>敏感词名称</th>
							<th>替换值</th>
							<th>敏感词等级</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<tr data-key="1">
							<td>1</td>
							<td>QQ视频</td>
							<td>****</td>
							<td>网络限制</td>
							<td><a href="/admin/index.php?r=user%2Fview&amp;id=2" title="查看"
								aria-label="查看" data-pjax="0"><span
									class="glyphicon glyphicon-eye-open"></span></a> <a
								href="/admin/index.php?r=user%2Fupdate&amp;id=2" title="更新"
								aria-label="更新" data-pjax="0">&nbsp;|&nbsp;<span
									class="glyphicon glyphicon-pencil"></span></a> <a
								href="/admin/index.php?r=rbac%2Fassignment%2Fview&amp;id=2"
								title="分配" aria-label="分配" data-pjax="0">&nbsp;|&nbsp;<span
									class="glyphicon glyphicon-remove"></span></a> <a
								href="/admin/index.php?r=user%2Fview&amp;id=2" title="添加等级"
								aria-label="添加等级" data-pjax="0">&nbsp;|&nbsp;<span
									class="glyphicon glyphicon-plus"></span></a></td>
						</tr>
						<tr data-key="2">
							<td>2</td>
							<td>法轮功</td>
							<td>******</td>
							<td>政治类</td>
							<td><a href="/admin/index.php?r=user%2Fview&amp;id=2" title="查看"
								aria-label="查看" data-pjax="0"><span
									class="glyphicon glyphicon-eye-open"></span></a> <a
								href="/admin/index.php?r=user%2Fupdate&amp;id=2" title="更新"
								aria-label="更新" data-pjax="0">&nbsp;|&nbsp;<span
									class="glyphicon glyphicon-pencil"></span></a> <a
								href="/admin/index.php?r=rbac%2Fassignment%2Fview&amp;id=2"
								title="分配" aria-label="分配" data-pjax="0">&nbsp;|&nbsp;<span
									class="glyphicon glyphicon-remove"></span></a> <a
								href="/admin/index.php?r=user%2Fview&amp;id=2" title="添加等级"
								aria-label="添加等级" data-pjax="0">&nbsp;|&nbsp;<span
									class="glyphicon glyphicon-plus"></span></a></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
