<?php


return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Camdivision',
    'language'=>'ru',
    'sourceLanguage'=>'en_US',
    'charset'=>'utf-8',
    'defaultController'=>'site',



	// preloading 'log' component
	//'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'application.extensions.*',
	),

    'preload' => array('log'),

	// подключение компонентов
	'components'=>array(
		'user'=>array(
            'class' => 'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

        'log'=>array    (
            'class'=>'CLogRouter',
            'enabled'=>defined('YII_DEBUG'),
            'routes'=>array(
                /*array(
                    'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters'=>array('127.0.0.1','192.168.1.215'),
                ),*/
                array(
                    'class'=>'ext.db_profiler.DbProfileLogRoute',
                    'countLimit' => 1, // How many times the same query should be executed to be considered inefficient
                    'slowQueryMin' => 0.01, // Minimum time for the query to be slow
                ),
            ),
        ),

        'db'=>array(
            'connectionString' => 'mysql:host=91.218.228.95;dbname=camsvision_vk',
            'emulatePrepare' => true,
            'username' => 'camsvision_vk',
            'password' => 'IGW5OlLd',
            'charset' => 'utf8',
            'tablePrefix' => 'cv_',
            //
            'enableProfiling'=>true,
            //
            'enableParamLogging' => true,
        ),

        /*'db'=>array(
			'connectionString' => 'mysql:host=openserver;dbname=cv_db',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
            'tablePrefix' => 'cv_',
			//
			'enableProfiling'=>true,
			//
			'enableParamLogging' => true,
		),*/

        'urlManager'=>array(
            'urlFormat'=>'path',
           // 'showScriptName'=>false,
            'rules'=>array(
                'main'=>'site/index',
                //'chat'=>'chat/index',
                'chat/id=<id:\d+>'=>'chat/view',
                'flash/id=<id:\d+>'=>'chat/flash',
                '<_c:(communication|info|contacts)>'=>'site/<_c>',

                //'news/<year:\d{4}>/<title>'=>'news/contacts',
/*
                '<_c:(post|comment)>/<id:\d+>/<_a:(create|update|delete)>' => '<_c>/<_a>',
                '<_c:(post|comment)>/<id:\d+>' => '<_c>/read',
                '<_c:(main|news)>' => '<_c>/index',*/
            ),
        ),

        'ih'=>array('class'=>'CImageHandler'),

        'VKapi'=>array(
            'class'=>'VKapi',
        ),

        'ChatList'=>array(
            'class'=>'ChatList',
        ),

        'Time'=>array(
            'class'=>'Time',
        ),

        // ловит ошибки PHP
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
	),

    // обработка запроса от родительского фрейма
    'onBeginRequest' => create_function('$event'," "
    ),
	// параметры приложения Yii::app()->params['paramName']
	'params'=>array(
        'ftp'=>array('host'=>'91.218.228.95',
                'login'=>'cams_vk_vodim',
                'pass'=>'g9kxgPC9'
            ),
		// this is used in contact page
        'postsPerPage'=>10,
        'dateFormat'=> 'd.MM.yyyy - HH:m',
        'apiId'=>2834906, // id приложения
        'apiSecret'=>'sH1AUMo4DB1SgIGIGbvu',
        'appUrl'=>'http://www.vk.com/app2834906', // адрес приложения в соцсети
		'adminEmail'=>'vodim08@ya.ru',
        'minHeight'=>'650px',
        'mainPageUrl'=>'/main',
        'userOnlineTime'=>10, //время в минутах, в течении которого пользователь считается online
	),
);