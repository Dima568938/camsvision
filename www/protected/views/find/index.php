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

<?php/*
$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
    'name'=>'city',
    'source'=>array('ac1', 'ac2', 'ac3'),
    // additional javascript options for the autocomplete plugin
    'options'=>array(
        'minLength'=>'2',
    ),
    'htmlOptions'=>array(
        'style'=>'height:20px;'
    ),
));*/
?>






<div class="form"  style="width:400px; ">

    <?php $form = $this->beginWidget('CActiveForm', array(
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

    <?// echo CHtml::dropDownList($model, "country", array('0'=>'Россия', '1'=>'США'));?>

    <div class="row">
        <?php echo $form->labelEx($model,'query'); ?>
        <?php

        $this->widget('CAutoComplete',
            array(
                'model'=>'chat',
                'name'=>'FindForm[query]',
                'url'=>array('find/autocomplete'),
                'minChars'=>2,
            )
        );


        //echo $form->textField($model,'query',array('size'=>40,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'query'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'sex'); ?>
        <?php echo $form->dropDownList($model,'sex',array(User::SEX_UNKNOWN=>'не важно',
                                                            User::SEX_MALE=>'муж',
                                                            User::SEX_FEMALE=>'жен',
                                                        )); ?>
        <?php echo $form->error($model,'sex'); ?>
    </div>

    <div class="row">
        <?php
        //TODO: перенести в контроллер
        $models = Country::model()->findAll(
        array('order' => 'country_name_ru'));

        // при помощи listData создаем массив вида $ключ=>$значение
        $list = CHtml::listData($models, 'id_country', 'country_name_ru');?>

        <?php echo $form->labelEx($model,'country'); ?>
        <?php //echo $form->dropDownList($model,'country',$list, array('onchange'=>'alert(this.value)')); ?>
        <?php echo $form->dropDownList($model,'country',$list, array('empty'=>'Выберите страну',
            'ajax' => array('update' => '#city_select',
                'url' => $this->createUrl('find/getCity'),
                'data' => 'js:"id="+this.value',
                'cache' => false,
    ))); ?>
    </div>

    <div class="row" id="city_select">
        <?php echo $form->labelEx($model,'city'); ?>
        <?php echo $form->dropDownList($model,'city',array(),array('empty'=>'Выберите страну')); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'category'); ?>
        <?php echo $form->dropDownList($model,'category',array(User::SEX_UNKNOWN=>'не важно',
        1=>'кат 1',
        2=>'кат 2',
    )); ?>
        <?php echo $form->error($model,'category'); ?>
    </div>

    <? //CHtml::dropDownList('dropdown', $dropdown,
  //  CHtml::listData(City::model()->findAll(array('order' => 'title')), 'id', 'title'),

  /*  array('ajax' => array('update' => '#dropdown_description',

        'url' => $this->createUrl('dropdown/description'),

        'data' => 'js:"id="+this.value',

        'cache' => false,

    ),

        'empty' => 'выбрать')); */?>


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
