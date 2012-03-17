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
    <div class="category">
    	<div class="title">
    		<?php echo CHtml::link(CHtml::encode($model->title), $model->getUrl($model->id)); ?>
    	</div>

    	<div class="nav">
            Число конференций: <?php echo $model->conferencesCount?>
            <br/>

    	</div>
    </div>
    <br/>


<div id="vk_comments"></div>