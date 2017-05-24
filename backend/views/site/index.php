<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = '评论系统5.0 管理后台';
?>
<style>
fieldset {
	padding: .35em .625em .75em;
	margin: 0 2px;
	border: 1px solid silver
}

legend {
	padding: .5em;
	border: 0;
	width: auto
}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<!-- /.box-header -->
			<div class="box-header with-border">
				<h3 class="box-title">账户群组</h3>
				<!-- /.box-tools -->
			</div>
			<div class="box-body">
				<div class="container-fluid">
					<table class="table table-hover">
						<tbody>
							<?php echo $html;?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- /.box-body -->
			<!-- Loading (remove the following to stop the loading)-->
			<div class="overlay" id="show_loging" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<!-- end loading -->
		</div>
		<!-- /.box -->
	</div>
	<!-- /.col -->
</div>
