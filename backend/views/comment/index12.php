<?php
use common\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评论';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.table th, .table td {
	text-align: center;
	vertical-align: middle;
}
</style>
<div class="comment-index">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-default collapsed-box">
						<div id="edit_loading" style="display: none;" class="overlay">
							<i class="fa fa-refresh fa-spin"></i>
						</div>
						<div class="box-header with-border">
							<h3 class="box-title">评论检索</h3>
							<div class="box-tools pull-right">
								<button data-widget="collapse" class="btn btn-box-tool"
									type="button">
									<i class="fa fa-plus"></i>
								</button>
							</div>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="form-group col-md-3">
								<label>发布与回复</label> <select id="sensitive_level_id_s"
									class="form-control" name="sensitive_level_id_s">
									<option value="2">发布</option>
									<option value="3">回复</option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label>状态</label> <select id="sensitive_level_id_s"
									class="form-control" name="sensitive_level_id_s">
									<option value="0">审核中</option>
									<option value="2">已发布</option>
									<option value="3">已隐藏</option>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label>IP</label> <select id="ipType" name="ipType"
									class="form-control">
									<option value="">---IP范围---</option>
									<option value="bip">ip段</option>
									<option value="sip">ip</option>
								</select>
							</div>
							<div id="han_add" class="form-group col-md-3">
								<label>关键字</label> <input type="text" name="uelike" value=""
									placeholder="检索关键字" class="form-control" id="uelike">
							</div>
							<div id="han_add" class="form-group col-md-3">
								<label>时间范围</label> <input type="text" name="uelike" value=""
									placeholder="时间范围" class="form-control" id="uelike">
							</div>
							<div id="han_add" class="form-group col-md-3">
								<label>昵称检索</label> <input type="text" name="uelike" value=""
									placeholder="昵称检索" class="form-control" id="uelike">
							</div>
							<div class="form-group col-md-3">
								<label>举报检索</label> <select id="ipType" name="ipType"
									class="form-control">
									<option value="">---是否被举报---</option>
									<option value="bip">是</option>
									<option value="sip">否</option>
								</select>
							</div>
							<button class="btn btn-block btn-info" id="sensitive_search_name"
								type="button">检索</button>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">列表信息</h3>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<!--
              <?=GridView::widget(['dataProvider' => $dataProvider,'columns' => ['id',['attribute' => 'user_id','value' => function ($model){return $model->user->username;}],'type','type_id',['attribute' => 'content','value' => function ($model){return \yii\helpers\Markdown::process($model->content);},'format' => 'html'],'up','down','created_at:datetime',['class' => 'backend\widgets\grid\ActionColumn','template' => '{view} {delete} {ban}','buttons' => ['ban' => function ($url, $model, $key){return Html::a(Html::icon('ban'), ['/user/ban'], ['title' => '封禁用户','data-method' => 'post','data-params' => ['id' => $model->user_id]]);}]]]]);?>
            -->

							<table class="table table-striped">
								<thead>
									<tr>
										<th style="width: 50px; word-break: break-all"><a
											data-sort="id"
											href="/admin/index.php?r=comment%2Findex&amp;sort=id"
											class="desc"></a></th>
										<!-- 
										<th style="width: 70px; word-break: break-all"><a
											data-sort="user_id"
											href="/admin/index.php?r=comment%2Findex&amp;sort=user_id">评论人</a></th>
										
										<th style="width: 80px; word-break: break-all"><a
											data-sort="type"
											href="/admin/index.php?r=comment%2Findex&amp;sort=type">评论标题</a></th>
											 -->
										<th style="width: auto; word-break: break-all"><a
											data-sort="type_id"
											href="/admin/index.php?r=comment%2Findex&amp;sort=type_id">评论内容</a></th>
										<th style="width: 90px; word-break: break-all"><a
											data-sort="content"
											href="/admin/index.php?r=comment%2Findex&amp;sort=content">频道区域</a></th>

										<th style="width: 90px; word-break: break-all"><a
											data-sort="content"
											href="/admin/index.php?r=comment%2Findex&amp;sort=content">使用类型</a></th>

										<th style="width: 80px; word-break: break-all"><a
											data-sort="content"
											href="/admin/index.php?r=comment%2Findex&amp;sort=content">评论类型</a></th>
										<th style="width: 50px; word-break: break-all"><a
											data-sort="content"
											href="/admin/index.php?r=comment%2Findex&amp;sort=content">状态</a></th>
										<th style="width: 100px; word-break: break-all"><a
											data-sort="created_at"
											href="/admin/index.php?r=comment%2Findex&amp;sort=created_at">创建时间</a></th>
										<th style="width: 100px; word-break: break-all">操作</th>
									</tr>
								</thead>
								<tbody>
									<tr data-key="35">
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><input
											type="checkbox" class="kv-row-checkbox" name="selection[]"
											value="2"></td>
										<td
											style="width: auto; word-break: break-all; text-align: center; vertical-align: middle;"><h5
												style="font-style: italic; color: #f000;">
												<a class="btn btn-warning btn-flat btn-xs">@评论人:frank</a>
												评论主题：马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班<br><hr>
											</h5>
											<p style="text-indent: 10pt;">马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事</p></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">香港01</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">电话评分</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><small
											class="label label-success">发布</small></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><small
											class="label label-danger">待审核中</small></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">2016-08-22
											10:09:22</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><a
											data-pjax="0" aria-label="查看" title="查看"
											href="/admin/index.php?r=comment%2Fview&amp;id=35"
											class="btn btn-default btn-xs"><span
												class="glyphicon glyphicon-eye-open"></span></a> <a
											data-pjax="0" data-method="post" data-confirm="您确定要删除此项吗？"
											aria-label="删除" title="删除"
											href="/admin/index.php?r=comment%2Fdelete&amp;id=35"
											class="btn btn-default btn-xs"><span
												class="glyphicon glyphicon-trash"></span></a> <a
											data-params="{&quot;id&quot;:1}" data-method="post"
											title="封禁用户" href="/admin/index.php?r=user%2Fban"><i
												class="fa fa-ban"></i></a></td>
									</tr>
									<tr style="text-align: center; vertical-align: middle;"
										data-key="34">
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><input
											type="checkbox" class="kv-row-checkbox" name="selection[]"
											value="2"></td>
										<td
											style="width: auto; word-break: break-all; text-align: center; vertical-align: middle;"><h5
												style="font-style: italic; color: #f000;">
												<a class="btn btn-warning btn-flat btn-xs">@评论人:frank</a>
												&nbsp;&nbsp;#<a href="">主贴：马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班</a>#
												<hr>
												<br> 评论主题：马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班<br>
											</h5>
											<p style="text-indent: 10pt;">马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事</p></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">香港哲学</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">活动评论</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><small
											class="label label-warning">回复</small></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><small
											class="label label-danger">待审核中</small></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">2016-08-22
											10:09:22</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><a
											data-pjax="0" aria-label="查看" title="查看"
											href="/admin/index.php?r=comment%2Fview&amp;id=34"
											class="btn btn-default btn-xs"><span
												class="glyphicon glyphicon-eye-open"></span></a> <a
											data-pjax="0" data-method="post" data-confirm="您确定要删除此项吗？"
											aria-label="删除" title="删除"
											href="/admin/index.php?r=comment%2Fdelete&amp;id=34"
											class="btn btn-default btn-xs"><span
												class="glyphicon glyphicon-trash"></span></a> <a
											data-params="{&quot;id&quot;:1}" data-method="post"
											title="封禁用户" href="/admin/index.php?r=user%2Fban"><i
												class="fa fa-ban"></i></a></td>
									</tr>
									<tr style="text-align: center; vertical-align: middle;"
										data-key="33">
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><input
											type="checkbox" class="kv-row-checkbox" name="selection[]"
											value="2"></td>
										<td
											style="width: auto; word-break: break-all; text-align: center; vertical-align: middle;"><h5
												style="font-style: italic; color: #f000;">
												<a class="btn btn-warning btn-flat btn-xs">@评论人:frank</a>
												&nbsp;&nbsp;#<a href="">主贴：马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班</a>#
												<hr>
												<br> 评论主题：马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班<br>
											</h5>
											<p style="text-indent: 10pt;">马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事</p></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">黑胶唱片</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">文章评论</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><small
											class="label label-success">发布</small></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><small
											class="label label-danger">自动隐藏</small></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">2016-08-22
											10:09:22</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><a
											data-pjax="0" aria-label="查看" title="查看"
											href="/admin/index.php?r=comment%2Fview&amp;id=33"
											class="btn btn-default btn-xs"><span
												class="glyphicon glyphicon-eye-open"></span></a> <a
											data-pjax="0" data-method="post" data-confirm="您确定要删除此项吗？"
											aria-label="删除" title="删除"
											href="/admin/index.php?r=comment%2Fdelete&amp;id=33"
											class="btn btn-default btn-xs"><span
												class="glyphicon glyphicon-trash"></span></a> <a
											data-params="{&quot;id&quot;:1}" data-method="post"
											title="封禁用户" href="/admin/index.php?r=user%2Fban"><i
												class="fa fa-ban"></i></a></td>
									</tr>
									<tr style="text-align: center; vertical-align: middle;"
										data-key="32">
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><input
											type="checkbox" class="kv-row-checkbox" name="selection[]"
											value="2"></td>
										<td
											style="width: auto; word-break: break-all; text-align: center; vertical-align: middle;"><h5
												style="font-style: italic; color: #f000;">
												<a class="btn btn-warning btn-flat btn-xs">@评论人:frank</a>
												&nbsp;&nbsp;#<a href="">主贴：马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班</a>#
												<hr>
												<br> 评论主题：马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班<br>
											</h5>
											<p style="text-indent: 10pt;">马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事马蓉从未来探班让宝强挺伤心的离婚关我屁事</p></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">多维评论</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">文章评论</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><small
											class="label label-success">发布</small></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><small
											class="label label-success">正常发布</small></td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;">2016-08-22
											10:09:22</td>
										<td
											style="width: 30px; text-align: center; vertical-align: middle;"><a
											data-pjax="0" aria-label="查看" title="查看"
											href="/admin/index.php?r=comment%2Fview&amp;id=32"
											class="btn btn-default btn-xs"><span
												class="glyphicon glyphicon-eye-open"></span></a> <a
											data-pjax="0" data-method="post" data-confirm="您确定要删除此项吗？"
											aria-label="删除" title="删除"
											href="/admin/index.php?r=comment%2Fdelete&amp;id=32"
											class="btn btn-default btn-xs"><span
												class="glyphicon glyphicon-trash"></span></a> <a
											data-params="{&quot;id&quot;:1}" data-method="post"
											title="封禁用户" href="/admin/index.php?r=user%2Fban"><i
												class="fa fa-ban"></i></a></td>
									</tr>

								</tbody>
							</table>

						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
		</div>

		<!-- /.box -->
	</div>
</div>
