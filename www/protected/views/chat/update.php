<h1>Редактировать чат:</h1>


<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'cu-dialog',
    'cssFile'=>'jquery-ui-1.8.18.custom.css',
    'theme'=>'no-theme',
    'themeUrl'=>Yii::app()->request->baseUrl.'/css/ui',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'Редактировать чат',
        'width'=>500,
        'draggable'=>false,
        'resizable'=>false,
        'autoOpen'=>false,
    ),
));

?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>


<?php echo CHtml::link('open dialog', '#', array(
    'onclick'=>'$("#cu-dialog").dialog("open"); return false;',
));

?>