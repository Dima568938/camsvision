<h1>Edit Conference</h1>

<?php if(Yii::app()->user->hasFlash('confSubmitted')): ?>
<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('confSubmitted'); ?>
</div>
<?php else: ?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php endif; ?>

