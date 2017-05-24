<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */

// $this->title = $model->id;
$this->title = '评论详细信息';
$this->params['breadcrumbs'][] = [
    'label' => '评论',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comment-view">
	<div class="row">
		<div class="col-sm-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">评论表单管理</h3>
					<span class="label label-primary pull-right"></span>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="container">
						<div class="row clearfix">
							<div class="col-md-12 column">
								<div class="tabbable" id="tabs-689681">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#panel-583733" data-toggle="tab">表单类型</a></li>
										<li><a href="#panel-979239" data-toggle="tab">表单设定</a></li>
										<li><a href="#panel-9792324" data-toggle="tab">生成管理</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="panel-583733">
											<p>
											
											
											<div class="alert alert-success alert-dismissible">
												<button type="button" class="close" data-dismiss="alert"
													aria-hidden="true">×</button>
												<h4>
													<i class="icon fa fa-check"></i> ok !
												</h4>
												操作完成!
											</div>
											<div class="alert alert-warning alert-dismissible">
												<button type="button" class="close" data-dismiss="alert"
													aria-hidden="true">×</button>
												<h4>
													<i class="icon fa fa-warning"></i> error !
												</h4>
												操作失败!
											</div>
											</p>
											<p>
											
											
											<div class="box box-default collapsed-box">
												<div class="box-header with-border">
													<h3 class="box-title">编辑/新增</h3>
													<div class="box-tools pull-right">
														<button class="btn btn-box-tool" data-widget="collapse">
															<i class="fa fa-plus"></i>
														</button>
													</div>
													<!-- /.box-tools -->
												</div>
												<!-- /.box-header -->
												<div class="box-body">
													<div class="form-group col-md-12">
														<label>表单类型名称</label> <input type="text"
															class="form-control" value="表单类型名称"
															placeholder="表单类型名称 ...">
													</div>
													<div class="checkbox">
														<label> <input type="checkbox" name="optionsRadios"
															id="optionsRadios1" value="option1" checked=""> 文字
														</label>
													</div>
													<div class="checkbox">
														<label> <input type="checkbox" name="optionsRadios"
															id="optionsRadios2" value="option2"> 数字
														</label>
													</div>
													<div class="checkbox">
														<label> <input type="checkbox" name="optionsRadios"
															id="optionsRadios2" value="option2"> 图片
														</label>
													</div>
													<hr>
													<div class="form-group col-md-3">
														<label> 排序方法</label> <select class="form-control">
															<option>--请选择--</option>
															<option value="最新优先">最新优先</option>
															<option value="热门优先">热门优先</option>
														</select>
													</div>
													<div class="form-group col-md-3">
														<label> 发布方法</label> <select class="form-control">
															<option>--请选择--</option>
															<option value="需审批">需审批</option>
															<option value="自动">自动</option>
														</select>
													</div>
													<div class="form-group col-md-3">
														<label> 修改权限</label> <select class="form-control">
															<option>--请选择--</option>
															<option value="可修改">可修改</option>
															<option value="不可修改">不可修改</option>
														</select>
													</div>
													<div class="form-group col-md-3">
														<label> 用户评论数设定</label> <select class="form-control">
															<option>--请选择--</option>
															<option value="一人一条">一人一条</option>
															<option value="一人多条">一人多条</option>
														</select>
													</div>
													<div class="form-group col-md-3">
														<label> 是否可回复</label> <select class="form-control">
															<option>--请选择--</option>
															<option value="是">是</option>
															<option value="否">否</option>
														</select>
													</div>
													<div class="form-group col-md-3">
														<label> 是否匿名评论</label> <select class="form-control">
															<option>--请选择--</option>
															<option value="是">是</option>
															<option value="否">否</option>
														</select>
													</div>
													<div class="form-group col-md-3">
														<label>&nbsp;</label>
														<button type="submit" class="btn btn-block btn-success ">增加</button>
													</div>
												</div>
												<!-- /.box-body -->
											</div>
											<!-- /.box -->
											<div class="box box-default">
												<div class="box-header with-border">
													<h3 class="box-title">列表信息</h3>
												</div>
												<div class="box-body">
													<div class="grid-view" id="w0">
														<div class="summary">
															第<b>1-2</b>条，共<b>2</b>条数据.
														</div>
														<table class="table table-striped table-bordered">
															<thead>
																<tr>
																	<th style="text-align: center;">ID</th>
																	<th style="text-align: center;">类型名称</th>
																	<th style="text-align: center;">操作</th>
																</tr>
															</thead>
															<tbody>
																<tr style="text-align: center;" data-key="1">
																	<td style="text-align: center;">111</td>
																	<td>124.205.222.98</td>
																	<td><a data-pjax="0" aria-label="查看" title="查看"
																		href="javascript:void(0);" data="22222" class="view"><span
																			class="glyphicon glyphicon-hand-left"></span></a></td>
																</tr>
																<tr style="text-align: center;" data-key="2">
																	<td>2222</td>
																	<td>124.205.222.98</td>
																	<td><a data-pjax="0" aria-label="查看" title="查看"
																		data="11111" href="javascript:void(0);" class="view"><span
																			class="glyphicon glyphicon-hand-left"></span></a></td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
												<!-- /.box-body -->
												<!-- Loading (remove the following to stop the loading)-->
												<div class="overlay" style="">
													<i class="fa fa-refresh fa-spin"></i>
												</div>
												<!-- end loading -->
											</div>
											<!-- /.box -->
											<div class="form-group col-md-12"></div>
										</div>
										<div class="tab-pane" id="panel-979239">
											<p>
											
											
											<div class="alert alert-success alert-dismissible">
												<button type="button" class="close" data-dismiss="alert"
													aria-hidden="true">×</button>
												<h4>
													<i class="icon fa fa-check"></i> ok !
												</h4>
												操作完成!
											</div>
											<div class="alert alert-warning alert-dismissible">
												<button type="button" class="close" data-dismiss="alert"
													aria-hidden="true">×</button>
												<h4>
													<i class="icon fa fa-warning"></i> error !
												</h4>
												操作失败!
											</div>
											</p>
											<p>
											
											
											<div class="box box-default collapsed-box">
												<div class="box-header with-border">
													<h3 class="box-title">新建/编辑</h3>
													<div class="box-tools pull-right">
														<button data-widget="collapse" class="btn btn-box-tool">
															<i class="fa fa-plus"></i>
														</button>
													</div>
													<!-- /.box-tools -->
												</div>
												<!-- /.box-header -->
												<div class="box-body">
													<form class="form-horizontal" role="form">
														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">名称</label>
															<div class="col-sm-10">
																<input type="text" class="form-control" value="數字空格"
																	placeholder="栏目名称 ...">
															</div>
														</div>
														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">所属类型</label>
															<div class="col-sm-10">
																<div class=" col-md-12">
																	<div class="radio">
																		<label> <input type="radio" name="optionsRadios"
																			id="optionsRadios1" value="option1" checked=""> 图片
																		</label>
																	</div>
																	<div class="radio">
																		<label> <input type="radio" name="optionsRadios"
																			id="optionsRadios2" value="option2"> 文字
																		</label>
																	</div>
																	<div class="radio">
																		<label> <input type="radio" name="optionsRadios"
																			id="optionsRadios2" value="option2"> 标签
																		</label>
																	</div>
																</div>
															</div>
														</div>
														<hr>
														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">规则设置</label>
														</div>
														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">提示文字</label>
															<div class="col-sm-10">
																<input type="text" class="form-control" value="提示文字"
																	placeholder="提示文字 ...">
															</div>
														</div>

														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">文字上限</label>
															<div class="col-sm-10">
																<input type="text" class="form-control" value="文字上限"
																	placeholder="文字上限 ...">
															</div>
														</div>
														<hr>
														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">支持格式</label>
														</div>
														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">上传图片上限</label>
															<div class="col-sm-10">
																<input type="text" class="form-control" value="上传图片上限"
																	placeholder="上传图片上限 ...">
															</div>
														</div>
														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">&nbsp;</label>
															<div class="col-sm-10">
																<div class=" col-md-12">
																	<div class="radio">
																		<label> <input type="radio" name="optionsRadios"
																			id="optionsRadios1" value="option1" checked="">贴纸
																		</label>
																	</div>
																	<div class="radio">
																		<label> <input type="radio" name="optionsRadios"
																			id="optionsRadios2" value="option2"> 表情
																		</label>
																	</div>
																</div>
															</div>
														</div>
														<hr>
														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">是否必填</label>
														</div>
														<div class="form-group">
															<label for="firstname" class="col-sm-2 control-label">&nbsp;</label>
															<div class="col-sm-10">
																<div class=" col-md-12">
																	<div class="radio">
																		<label> <input type="radio" name="optionsRadios"
																			id="optionsRadios1" value="option1" checked="">是
																		</label>
																	</div>
																	<div class="radio">
																		<label> <input type="radio" name="optionsRadios"
																			id="optionsRadios2" value="option2">否
																		</label>
																	</div>
																</div>
															</div>
														</div>
														<hr>
													</form>
												</div>
												<!-- /.box-body -->
											</div>
											<div class="box box-default">
												<div class="box-header with-border">
													<h3 class="box-title">列表信息</h3>
												</div>
												<div class="box-body">
													<div class="summary">
														第<b>1-2</b>条，共<b>2</b>条数据.
													</div>
													<table class="table table-striped table-bordered">
														<thead>
															<tr>
																<th style="text-align: center;">ID</th>
																<th style="text-align: center;">类型名称</th>
																<th style="text-align: center;">操作</th>
															</tr>
														</thead>
														<tbody>
															<tr data-key="1" style="text-align: center;">
																<td style="text-align: center;">111</td>
																<td>124.205.222.98</td>
																<td><a class="view" data="22222"
																	href="javascript:void(0);" title="查看" aria-label="查看"
																	data-pjax="0"><span
																		class="glyphicon glyphicon-hand-left"></span></a></td>
															</tr>
															<tr data-key="2" style="text-align: center;">
																<td>2222</td>
																<td>124.205.222.98</td>
																<td><a class="view" href="javascript:void(0);"
																	data="11111" title="查看" aria-label="查看" data-pjax="0"><span
																		class="glyphicon glyphicon-hand-left"></span></a></td>
															</tr>
														</tbody>
													</table>
												</div>
												<!-- /.box-body -->
												<!-- Loading (remove the following to stop the loading)-->
												<div style="" class="overlay">
													<i class="fa fa-refresh fa-spin"></i>
												</div>
												<!-- end loading -->
											</div>


											<div class="form-group col-md-2">
												<label>&nbsp;</label>
												<button type="submit" class="btn btn-block btn-success ">保存</button>
											</div>

											</p>
										</div>
										<div class="tab-pane" id="panel-9792324">
											<p>
											
											
											<div class="alert alert-success alert-dismissible">
												<button type="button" class="close" data-dismiss="alert"
													aria-hidden="true">×</button>
												<h4>
													<i class="icon fa fa-check"></i> ok !
												</h4>
												操作完成!
											</div>
											<div class="alert alert-warning alert-dismissible">
												<button type="button" class="close" data-dismiss="alert"
													aria-hidden="true">×</button>
												<h4>
													<i class="icon fa fa-warning"></i> error !
												</h4>
												操作失败!
											</div>
											</p>
											<p>
											
											
											<blockquote>
												<p>说明</p>
												<small><cite title="Source Title"> 权限:不同区域管理员可对本区域内的表单进行管理 </cite></small>
												<small><cite title="Source Title"> 频道:不同频道可生成不同表单</cite></small>
												<small><cite title="Source Title"> 认证:评论系统API会自动根据不同授权处理相应数据</cite></small>
											</blockquote>
											<div class="col-md-12">
												<div class="box box-default">
													<div class="box-header with-border">
														<h3 class="box-title">操作区域</h3>
													</div>
													<div class="box-body">
														<form role="form">
															<!-- text input -->

															<div class="form-group col-md-12"></div>
															<div class="form-group col-md-12">
																<label>使用区域</label> <select class="form-control">
																	<option>--请选择--</option>
																	<option value="香港01">香港01</option>
																	<option value="香港01活动">香港01活动</option>
																	<option value="多维社区">多维社区</option>
																	<option value="多维中国频道">多维中国频道</option>
																	<option value="香港01哲学">香港01哲学</option>
																</select>
															</div>
															<div class="form-group col-md-12">
																<label>表单类型</label> <select class="form-control">
																	<option>--请选择--</option>
																	<option value="香港01">香港01_频道</option>
																	<option value="香港01活动">香港01活动_频道</option>
																	<option value="多维社区">多维社区_频道</option>
																	<option value="多维中国频道">多维中国频道_频道</option>
																	<option value="香港01哲学">香港01哲学_频道</option>
																</select>
															</div>
															<div class="form-group col-md-12">
																<label>CSS配置</label>
																<textarea class="form-control" rows="8"
																	placeholder="回复内容">123123123213131</textarea>
															</div>
															<div class="form-group col-md-12">
																<label>模板</label>
																<textarea class="form-control" rows="8"
																	placeholder="回复内容">23131312321313</textarea>
															</div>

															<!-- textarea -->
															<div class="form-group col-md-12">
																<label>生成代码</label>
																<textarea class="form-control" rows="8"
																	placeholder="回复内容">123123131313131</textarea>
															</div>
															<div class="box-footer">
																<div class="form-group col-md-3">
																	<button type="submit" class="btn btn-block btn-success">生成</button>
																</div>
																<div class="form-group col-md-3">
																	<button type="submit" class="btn btn-block btn-danger">清空</button>
																</div>
															</div>
													
													</div>
													<!-- /.box-body -->
													<!-- Loading (remove the following to stop the loading)-->
													<div class="overlay">
														<i class="fa fa-refresh fa-spin"></i>
													</div>
													<!-- end loading -->
												</div>
												<!-- /.box -->
											</div>

											</p>

										</div>
									</div>
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
</div>

