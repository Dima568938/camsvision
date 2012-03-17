<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'chat-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

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
        <?php echo $form->labelEx($model,'start_time'); ?>
        <?php echo $form->textArea($model,'start_time'); ?>
        <?php echo $form->error($model,'start_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'cat_id'); ?>
        <?php echo $form->textArea($model,'cat_id'); ?>
        <?php echo $form->error($model,'cat_id'); ?>
    </div>




	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->