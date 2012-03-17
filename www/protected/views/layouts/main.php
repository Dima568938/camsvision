<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
if (!defined('YII_NO_VK')){
    $cs->registerScriptFile(Yii::app()->request->baseUrl.'http://vk.com/js/api/xd_connection.js?2');
}

?>
<html>
<head>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta http-equiv="Content-Type" content="text/html;charset=<?=Yii::app()->charset?>" />
    <meta http-equiv="Content-Style-Type" content="text/css" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <? if (!(defined('YII_DEBUG') || defined('YII_NO_VK'))){?>
    <script type="text/javascript">
    	//if(parent.frames.length==0){document.location.replace("<?=Yii::app()->params['appUrl']?>");} // если пользователь заходит не через приложение, переадресуем его
    </script>
    <?}?>
</head>
<body>

<div class="container" id="page">
    <div id='header'>
        Шапка!
    </div><!-- header -->

    <div id='mainmenu'>
        <?php
        $this->widget('zii.widgets.CMenu', array(
            'items'=>array(
                // Important: you need to specify url as 'controller/action',
                // not just as 'controller' even if default acion is used.
                array('label'=>'Главная', 'url'=>array('site/index')),
                array('label'=>'Чаты ->', 'url'=>array('chat/index')),
                array('label'=>'Общение ->', 'url'=>array('site/communication')),
                array('label'=>'Поиск ->', 'url'=>array('find/index')),
                array('label'=>'Вход', 'url'=>'#','linkOptions'=>array( 'onclick'=>'alert("HELLOO");')),
            ),
        ));

        echo "id".Yii::app()->user->id.'<br/>';
        echo Yii::app()->user->fname.' '.Yii::app()->user->lname.' '.'<br/>';
        echo Yii::app()->user->country.' '.Yii::app()->user->city.' '.'<br/>';
        ?>
    </div>

    <img src="<?=Yii::app()->user->photo?>" alt="">

    <a href="<?=Yii::app()->createUrl('chat/create');?>">Создать чат</a><br/>

    ----------------------------------------------------------------------------------------------------------------
    <br>
    <?=$content?>
    <br>
    <br>


   <div id="footer">
       Copyright &copy; <?php echo date('Y'); ?> by Dmitriy Voloshin.<br/> All Rights Reserved.
   </div><!-- footer -->
</div><!-- page -->

</body>
</html>