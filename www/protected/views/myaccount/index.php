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
<h1>Пользователь:</h1>


<div class="User">


        <img src="<?=$profile['photo']?>" alt="">
        <div class="name">
            <?php echo CHtml::link( $profile['first_name'].' '.$profile['last_name'], 'http://vk.com/id'.$user->uid); ?>
        </div>


        <div class="nav">
            Дата создания : <?php echo date("F j, Y",strtotime($user->create_time)); ?>
        </div>
</div>


    -----------------------------------------------------------------
