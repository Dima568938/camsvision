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
<h1>Чаты:</h1>

  <? // print_r($dataProvider->getData());


//print_r($profiles[1]);


?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    'viewData'=>array('profiles'=>$profiles),
	'template'=>"{items}\n{pager}",
)); ?>

