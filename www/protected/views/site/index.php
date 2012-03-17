<?php
$cs = Yii::app()->clientScript;

//$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/vk/init_vk.js' );

$script = '$(function() {'."\n";
$script .= 'VK.callMethod("setTitle", "'.$this->pageTitle.'");'."\n";
$script .= 'VK.callMethod("setLocation","'.Yii::app()->params['mainPageUrl'].'");'."\n";
$script .= 'VK.addCallback("onLocationChanged", function(res){locationChange("'.Yii::app()->baseUrl.'"+res)});'."\n";
$script .= 'resizeWindow();'."\n";
$script .= '});'."\n";

//$cs->registerScript('vk_init_local', $script, CClientScript::POS_BEGIN);
?>

<h1>Главная:</h1>

<?php

//$this->widget('activity');

//echo 'пользователей онлайн - '.User::model()->onlineUserCount.'<br/>';
//echo 'имя пользователя - '.Yii::app()->user->fname;
//$profile = Yii::app()->VKapi->getProfiles(Yii::app()->user->id.',2');
//print_r ($profile);
?>




<?php $this->beginWidget('system.web.widgets.CClipWidget', array('id'=>'Рекомендуемые')); ?>
    My tab 1 ...

<?php $this->widget('application.components.RecomendedChatListWidget.RecomendedChatListWidget');?>

<?php $this->endWidget(); ?>

<?php $this->beginWidget('system.web.widgets.CClipWidget', array('id'=>'Популярные')); ?>
My tab 2 ...

<?php $this->widget('application.components.PopularChatListWidget.PopularChatListWidget');?>

<?php $this->endWidget(); ?>

<?php $this->beginWidget('system.web.widgets.CClipWidget', array('id'=>'Новые')); ?>
My tab 3 ...

<?php $this->widget('application.components.NewChatListWidget.NewChatListWidget');?>

<?php $this->endWidget(); ?>

<?php
$tabParameters = array();
foreach($this->clips as $key=>$clip)
    $tabParameters['tab'.(count($tabParameters)+1)] = array('title'=>$key, 'content'=>$clip);
?>

<?php $this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters)); ?>

<?php $this->widget('application.components.ActivityWidget.ActivityWidget');?>
