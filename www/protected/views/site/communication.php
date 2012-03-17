<?php
$cs = Yii::app()->clientScript;

//$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/vk/init_vk.js' );

$script = '$(function() {'."\n";
$script .= 'VK.callMethod("setTitle", "SSConf 2");'."\n";
$script .= 'VK.callMethod("setLocation","'.Yii::app()->request->url.'");'."\n";
$script .= 'VK.addCallback("onLocationChanged", function(res){locationChange("'.Yii::app()->baseUrl.'"+res)});'."\n";
$script .= 'VK.Widgets.Comments("vk_comments", {limit: 30, width: "470px", attach: "*", pageUrl:"http://vkontakte.ru/app'.Yii::app()->params['apiId'].'#/communication" , onChange: function(data){/*addActivity(1)*/}});'."\n";
$script .= 'resizeWindow();'."\n";
$script .= '});'."\n";

//$cs->registerScript('vk_init_local', $script, CClientScript::POS_BEGIN);


 
echo 'Contacts!!!'. $params;

?>

<div id="vk_comments"></div>