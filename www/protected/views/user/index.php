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
<h1>Пользователи:</h1>


<div class="Users">



    <?php foreach($users as $item) {
    $profile = $profiles[$item->uid];

    $this->renderPartial('_view', array(
        'data'=>$item,
        'profile'=>$profile,
        ));

     } ?>
    -----------------------------------------------------------------
</div><!-- users -->