<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('common', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'username') ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('注册', ['class' => 'btn btn-primary', 'id' => 'signup-buttons']) ?>
                    <?= Html::a('去登录', ['site/login'], ['class' => 'btn btn-warning']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
jQuery("#signup-buttons").click(function(){
    var username = $('#register-username').val() || ''; 
    var email = $('#register-email').val() || '';
    var password = $('#register-password').val() || '';
    $.ajax({
        type: "POST",
        url: "index.php?r=site/regist_form",
        data: {
            'username': username,'email':email,'password':password
        },
        success: function(data){
            data = eval('(' + data + ')');
            if( data.status == true){
                alert(data.data);
                window.location.href   =   "index.php?r=site/login";
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
