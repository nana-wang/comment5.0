<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '登录';

// $fieldOptions1 = [
//     'options' => ['class' => 'form-group has-feedback'],
//     'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
// ];

// $fieldOptions2 = [
//     'options' => ['class' => 'form-group has-feedback'],
//     'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
// ];
?>

<div class="login-box">
    <div class="login-box-body" style="width:400px;">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="row">
            <div class="form-group">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary', 'id' => 'login-buttons']) ?>

                <?= Html::a('去注册', ['site/regist'], ['class' => 'btn btn-warning']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>


    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
<?php
$js = <<<JS
jQuery("#login-buttons").click(function(){
    var email = $('#users-username').val() || ''; 
    var password = $('#users-password').val() || '';
    $.ajax({
        type: "POST",
        url: "index.php?r=site/login_form",
        data: {
            'email':email,'password':password
        },
        success: function(data){
            data = eval('(' + data + ')');
            if( data.status == true){
                // alert('登录成功！');
                location.href="http://focus.dwnews.com/index.php?r=login/loginok";
            }else{
                alert(data.data);
                return;
            }  
        }
    }); 
});
JS;

$this->registerJs($js);
?>