<div class="form"  style="width:400px; overflow: hidden;">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'find-form',
    'action'=> Yii::app()->createUrl("find/index"),
	'enableAjaxValidation'=>true,
    'clientOptions'=>array(
      'validateOnSubmit'=>true,
      'validationUrl' => Yii::app()->createUrl("find/index"),
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


    <div class="row" id="output22">
output
    </div>



	<div class="row buttons">

        <?php  echo CHtml::submitButton('Искать');


       /* echo CHtml::ajaxSubmitButton(,
        CController::createUrl('chat/ajaxUpdate',array('id'=>$model->id)),
        array(
            'type'=>'POST',
            'update'=> '#output'
        ),
        array(
            'type'=>'submit'
            //html options
        )); */?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

