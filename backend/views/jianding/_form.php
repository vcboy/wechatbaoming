    <?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Jianding */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="page-header">
    <h1><?=$this->title?></h1>
</div>
<div class="jianding-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'plan_id')->dropDownList(array(''=>'--请选择--')+$planlist,['class'=>'input-big form-control','id'=>'plan_id']) ?>

    <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <? //= $form->field($model, 'sex')->textInput() ?>
    <?= $form->field($model, 'sex')->dropDownList(array(''=>'--请选择--','1'=>'男','0'=>'女'),['class'=>'input-big form-control','id'=>'sex']) ?>


    <?= $form->field($model, 'nation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthday')->textInput(['maxlength' => true,'onfocus' => 'WdatePicker({skin:"whyGreen",dateFmt:"yyyy/MM/dd"})']) ?>

    <?= $form->field($model, 'sfz')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'bkzs')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'bkfx')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'zsdj')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'education')->textarea(['rows' => 6]) ?>
    <div class="form-group field-jianding-education" id="j-education">
        <label class="control-label" for="jianding-education" onclick="addEducation()">教育经历</label>
        <div id="educationtemplate"><div></div>
        时间:<input type="text" id="jianding-education_time" class="form-control" name="Jianding[education]"  size="20" style="width: 150px;">
        学校:<input type="text" id="jianding-education_school" class="form-control" name="Jianding[education]"  style="width: 150px;" >
        专业:<input type="text" id="jianding-education_zy" class="form-control" name="Jianding[education]"  style="width: 150px;" >
        学历学位:<input type="text" id="jianding-education_xlxw" class="form-control" name="Jianding[education]"  style="width: 150px;" >
        </div>

    </div>

    <div class="form-group field-jianding-job">
    <label class="control-label" for="jianding-job">工作经历</label>
    <input type="text" id="jianding-birthday" class="form-control" name="Jianding[education]"  maxlength="32" >

    <div class="help-block"></div>
    </div>

    <?//= $form->field($model, 'job')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'score')->textInput() ?> 

    <div class="form-group">
    <?=  Html::submitButton($model->isNewRecord ? '保存' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?=  Html::a('返回',Url::toRoute("index"),['class'=>'btn btn-primary'])?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    function addEducation(){
        var educontent = $("#educationtemplate").clone();
        $("#j-education").append(educontent);
    }
</script>