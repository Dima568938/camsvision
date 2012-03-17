<div class="form" id="output" style="width:400px; overflow: hidden;">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'chat-form',
    'action'=>$model->isNewRecord ? Yii::app()->createUrl("chat/create") : Yii::app()->createUrl("chat/update",array("id"=>$model->id)),
	'enableAjaxValidation'=>true,
    'clientOptions'=>array(
      'validateOnSubmit'=>true,
      'validationUrl' => $model->isNewRecord ? Yii::app()->createUrl("chat/create") : Yii::app()->createUrl("chat/update",array("id"=>$model->id)),
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
                                                           $('#cu-dialog').dialog('close');
                                                          //$('#output').html(ret);
                                                        }
                                                      });
                                                    }

                                                    return false;
                                                }")

)); ?>

	<p class="note">Поля со знаком <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'cat_id'); ?>
        <?php echo $form->textArea($model,'cat_id'); ?>
        <?php echo $form->error($model,'cat_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->radioButtonList($model,'type', array(
        0=>'открытое',
        1=>'закрытое'
    )); ?>
        <?php echo $form->error($model,'type'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model,'fileName'); ?>
        <?php echo $form->hiddenField($model,'fileName'); ?>
        <?php echo $form->error($model,'fileName'); ?>
    </div>


    <div class="row" id="output">
        <?php if (!$model->isNewRecord){
                echo $this->chatThumb($model, 50 ,50);
        }else{
                echo $this->chatThumb('', 50 ,50);
        }?>
    </div>

    <? $this->widget('ext.EAjaxUpload.EAjaxUpload',
    array(
        'id'=>'uploadFile',
        'config'=>array(
            'action'=>Yii::app()->createUrl("chat/upload", array('id'=>$model->id)),
            'allowedExtensions'=>array("jpg","jpeg","png","bmp","gif"),//array("jpg","jpeg","gif","exe","mov" and etc...
            'sizeLimit'=>1*1024*1024,// maximum file size in bytes
            'minSizeLimit'=>10*1024,// minimum file size in bytes
            'onComplete'=>"js:function(id, fileName, responseJSON){
                    $('.chat-thumb').attr('src',eval(responseJSON).uploadDir+eval(responseJSON).filename);
                    $('#Chat_fileName').attr('value', eval(responseJSON).filename);

                    //alert($('#Chat_fileName').attr('value'));
                    //alert(eval(responseJSON).uploadDir+eval(responseJSON).filename);
                }",
            //'messages'=>array(
            //                  'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
            //                  'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
            //                  'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
            //                  'emptyError'=>"{file} is empty, please select files again without it.",
            //                  'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
            //                 ),
            //'showMessage'=>"js:function(message){ alert(message); }"
        )
    )); ?>

	<div class="row buttons">

        <?php  echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить' );


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

