<?php
$cs = Yii::app()->clientScript;

//$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/vk/init_vk.js' );

$script = '$(function() {'."\n";
$script .= 'VK.callMethod("setTitle", "SSConf 2");'."\n";
$script .= 'VK.callMethod("setLocation","'.Yii::app()->request->url.'");'."\n";
$script .= 'VK.addCallback("onLocationChanged", function(res){locationChange("'.Yii::app()->baseUrl.'"+res)});'."\n";
$script .= 'resizeWindow();'."\n";
$script .= '});'."\n";

//$cs->registerScript('vk_init_local', $script, CClientScript::POS_BEGIN);
?>
<h1>Поиск:</h1>

<?php //echo $this->renderPartial('_find', array('model'=>$findForm)); ?>


<div class="form"  style="width:400px; ">

    <?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>'find-form',
    'action'=> Yii::app()->createUrl("find/AjaxFind"),
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validationUrl' => Yii::app()->createUrl("find/AjaxFind"),
        'ajaxVar' => 'ajax',
        'afterValidate' => "js: function(form, data, hasError) {
                                                    //if no error in validation, send form data with Ajax
                                                    if (! hasError) {
                                                      $.ajax({
                                                        type: 'POST',
                                                        url: form[0].action,
                                                        data: $(form).serialize(),
                                                        success: function(ret) {
                                                          //alert(ret)
                                                          $('#output').html(ret);
                                                        }
                                                      });
                                                    }

                                                    return false;
                                                }")

)); ?>

    <p class="note">Поля со знаком <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'query'); ?>
        <?php echo $form->textField($model,'query',array('size'=>40,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'query'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'sex'); ?>
        <?php echo $form->textField($model,'sex',array('size'=>40,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'sex'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'country'); ?>
        <?php echo $form->textField($model,'country',array('size'=>40,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'country'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'city'); ?>
        <?php echo $form->textField($model,'city',array('size'=>40,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'city'); ?>
    </div>

    <div class="row buttons">
        <?php  echo CHtml::submitButton('Искать');?>
    </div>


    <div class="row" id="output">
        output
        <?php $this->widget('zii.widgets.CListView', array(
       //'id'=>'yw57',
        'dataProvider'=>$dataProvider,
        'itemView'=>'_view',
        'viewData'=>array(),
        'template'=>"{items}\n{pager}",
    )); ?>

    </div>


    <?php $this->endWidget(); ?>

</div><!-- form -->
