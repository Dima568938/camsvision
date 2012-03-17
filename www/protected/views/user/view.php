<?php
$cs = Yii::app()->clientScript;

//$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/vk/init_vk.js', CClientScript::POS_BEGIN);

$script = '$(function() {'."\n";
$script .= 'VK.callMethod("setTitle", "SSConf 2");'."\n";
$script .= 'VK.callMethod("setLocation","'.Yii::app()->request->url.'");'."\n";
$script .= 'VK.Widgets.Comments("vk_comments", {limit: 30, width: "470px", attach: "*", pageUrl:"http://vkontakte.ru/app'.Yii::app()->params['apiId'].'#/conf/id='.$model->id.'" , onChange: function(res){';
$script .= '$.get("http://ssconf2test.plazmedia.net/chat/addActivity?type=1&cid='.$model->id.'", function(res){alert(res)});';
$script .= '}});'."\n";
$script .= 'VK.addCallback("onLocationChanged", function(res){locationChange("'.Yii::app()->baseUrl.'"+res)});'."\n";
$script .= 'resizeWindow();'."\n";
$script .= '});'."\n";

//$cs->registerScript('vk_init_local', $script, CClientScript::POS_BEGIN);

?>
<div class="user">
    <img src="<?=$profile['photo']?>" alt="">
    <div class="name">
        <?php echo CHtml::link( $profile['first_name'].' '.$profile['last_name'], 'http://vk.com/id'.$data->uid); ?>
    </div>


    <div class="nav">
        Дата создания : <?php echo date("F j, Y",strtotime($data->create_time)); ?>
    </div>
</div>
<br/>
    <br/>

<?php  $this->widget('application.components.memberswidget.memberswidget', array(
                     'cid'=>$model->id) ); ?>

<div id="screenshots">
        <?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/1.jpg','цветок!', array('style'=>"width:100px; height:80px;")), Yii::app()->request->baseUrl.'/images/2.jpg'); ?>
</div>

<div id="vk_comments"></div>