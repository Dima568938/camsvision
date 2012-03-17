<?php


return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Final Conference',

    'defaultController'=>'site',
    
	// preloading 'log' component
	//'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	// подключение компонентов
	'components'=>array(
		'user'=>array(
            'class' => 'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		'db'=>array(
			'connectionString' => 'mysql:host=mysql52.hoster.ru;dbname=db63907m',
			'emulatePrepare' => true,
			'username' => 'm63907',
			'password' => 'x8ZgBRGj',
			'charset' => 'utf8',
            'tablePrefix' => 's2_',
			//
			'enableProfiling'=>true,
			//
			'enableParamLogging' => true,
		),

        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'rules'=>array(
                'main'=>'site/index',
                'chat'=>'chat/index',
                'conf/id=<id:\d+>'=>'chat/view',
                'flash/id=<id:\d+>'=>'chat/flash',
                '<_c:(communication|info|contacts)>'=>'site/<_c>',

                //'news/<year:\d{4}>/<title>'=>'news/contacts',
/*
                '<_c:(post|comment)>/<id:\d+>/<_a:(create|update|delete)>' => '<_c>/<_a>',
                '<_c:(post|comment)>/<id:\d+>' => '<_c>/read',
                '<_c:(main|news)>' => '<_c>/index',*/
            ),
        ),

        'VKapi'=>array(
            'class'=>'VKapi',
        ),

        // ловит ошибки PHP
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
	),

    // обработка запроса от родительского фрейма
    'onBeginRequest' => create_function('$event',"/*
        //авторизуем пользователя
        if(Yii::app()->user->isGuest && !isset(\$_GET['viewer_id'])){
            //echo 'Ошибка авторизации. viewer_id не указан';
            //Yii::app()->end();
           // Yii::app()->request->redirect(Yii::app()->params['appUrl']);
        }else{
            if(isset(\$_GET['viewer_id'])){
                \$apiSecret = Yii::app()->params['apiSecret'];
                \$viewer_id = \$_GET['viewer_id'];
                \$apiId = Yii::app()->params['apiId'];
                //проверка viewer_id на подлинность
                //if(\$_GET['auth_key']!=md5(\$apiId.'_'.\$viewer_id.'_'.\$apiSecret)){
                   // echo 'Войдите через iframe!'.'<br/>';
                   // Yii::app()->end();
                   // Yii::app()->request->redirect(Yii::app()->params['appUrl']);
                //}
                Yii::app()->user->logout();
                \$identity = new UserIdentity(\$_GET['viewer_id']);

                // первый запрос к API VK (в настройках приложения)
                // method=getProfiles&uids={viewer_id}&fields={uid,first_name,last_name,photo,photo_medium,photo_big,sex,city}&format=json&v=2.0

                //получаем данные пользователя из первого запроса к аpi
                \$api_result = json_decode(\$_GET['api_result'],TRUE);
                \$fname =\$api_result['response'][0]['first_name'];
                \$lname =\$api_result['response'][0]['last_name'];
                \$photo =\$api_result['response'][0]['photo'];
                \$photo_medium =\$api_result['response'][0]['photo_medium'];
                \$photo_big =\$api_result['response'][0]['photo_big'];

                // сохраняем данные пользователя в сессию
                \$identity->setState('fname',\$fname);
                \$identity->setState('lname',\$lname);
                \$identity->setState('photo',\$photo);
                \$identity->setState('photo_medium',\$photo_medium);
                \$identity->setState('photo_big',\$photo_big);

                if(\$identity->authenticate()) {
                    \$duration=3600*24*1; // продолжительность сессии - 1 день
                    Yii::app()->user->login(\$identity,\$duration);
                }
            }else{
                // Вход выполнен
                Yii::app()->user->stayOnline(); // обновляем время последнего действия пользователя
            }
        }
        //перенаправление на указанный в хеше адрес
        if(\$_GET['hash']!=null){
            \$hash=\$_GET['hash'];
            Yii::app()->request->redirect(\$hash);
        }
        return true; */"
    ),
	// параметры приложения Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
        'postsPerPage'=>10,
        'apiId'=>2692158, // id приложения
        'apiSecret'=>'PR1xD0jBuSN0jyMqTZTY',
        'appUrl'=>'http://www.vk.com/app2692158', // адрес приложения в соцсети
		'adminEmail'=>'vodim08@ya.ru',
        'minHeight'=>'650px',
        'mainPageUrl'=>'/main',
        'userOnlineTime'=>10, //время в минутах, в течении которого пользователь считается online
	),
);