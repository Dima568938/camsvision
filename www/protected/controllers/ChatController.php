<?php

//TODO: доработать создание, редактирование и удаление конференций(в т.ч. чере аякс) (сделать загрузку картинок)
//TODO: реализовать возможность голосования за конференцию
//TODO: сделать вывод рейтинга
//TODO: организовать загрузку фотографий
//Todo: разобраться с правами доступа к контроллерам
//TODO: провести инспекцию кода

class ChatController extends CController
{

	//public $layout='column2';


	private $_model;
    
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to access 'index' and 'view' actions.
				'actions'=>array('index','view','update','create','deletes'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated users to access all actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


    /**
     * Вывод списка всех чатов
     */
    public function actionIndex()
    {
        $criteria=new CDbCriteria(array(
            'order'=>'create_time DESC',
        ));

        $dataProvider=new CActiveDataProvider('Chat', array(
            'pagination'=>array(
                'pageSize'=>5,
            ),
            'criteria'=>$criteria,
        ));

        $chatRecords = $dataProvider->getData();   //извлекаем activeRecords из dataProvider
        $orgIds = Chat::model()->getChatOrgIds($chatRecords);  //массив идентификаторов авторов чатов

        $profiles = Yii::app()->VKapi->getPreparedProfiles($orgIds); //получаем профили авторов

        $this->render('index',array(
            'dataProvider'=>$dataProvider,
            'profiles'=>$profiles,
        ));
    }

    /**
     * Вывод страницы чата
     */
    public function actionView()
	{
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        //подключаем lightbox
        $cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/lightbox/js/jquery.lightbox-0.5.min.js', CClientScript::POS_END);
        $cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/lightbox/init_lightbox.js' ,CClientScript::POS_END);
        $cs->registerScriptFile(Yii::app()->request->baseUrl.'http://vkontakte.ru/js/api/xd_connection.js?2');
        $cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/vk/init_vk.js' );
        $cs->registerCssFile(Yii::app()->request->baseUrl.'/js/lightbox/css/jquery.lightbox-0.5.css');

        $chat=$this->loadModel();
        $chat->addView(Yii::app()->user->id); // добавить просмотр пользователем

        $orgId = $chat->org_id;
        $profiles = Yii::app()->VKapi->getPreparedProfiles($orgId); //получаем профиль автора чата

        if(Yii::app()->request->isAjaxRequest){
            $a=$_POST['ajax'];
            echo CHtml::encode($a);
            Yii::app()->end();
        }else {
            $this->render('view',array(
                'model'=>$chat,
                'profiles'=>$profiles,
            ));
        }
	}

    /**
     * Вывод страницы создания чата
     *
     * изображения загружаются в 'uploads/uid_timestamp_filename'
     *
     */
	public function actionCreate()
	{
        $uid = Yii::app()->user->id;
        $condition='org_id=?';
        $model = Chat::model()->find($condition, array(Yii::app()->user->id));
        if($model===null){ // каждый пользователь может создать только один чат
            $model=new Chat();
            if(isset($_POST['Chat']))
            {
                $model->attributes=$_POST['Chat'];

                $this->performAjaxValidation($model);

                $uid = Yii::app()->user->id;

                $fileName = $model->fileName;
                $model->img = $fileName;

                if($model->save()){

                    /* создаем миниатюру изображения */
                    $ih = new CImageHandler(); //Инициализация
                    Yii::app()->ih
                        ->load($this->getImgDir($uid).$fileName) //Загрузка оригинала картинки
                        ->adaptiveThumb($this->_thumbWidth, $this->_thumbHeight) //Создание превьюшки шириной
                        ->save($this->getThumbsDir($uid).$fileName) //Сохранение превьюшки в папку thumbs
                    ;
                    /* --------------------------------- */

                    if(Yii::app()->request->isAjaxRequest){
                        echo 'Чат создан!';
                        Yii::app()->end();
                    }else{
                        Yii::app()->user->setFlash('chatSubmitted','Чат успешно создан!');
                        $this->redirect(array('view','id'=>$model->id));
                        // $this->refresh();
                    }
                }
            }else{
                $this->render('create',array(
                    'model'=>$model,
                ));
            }


        } else {
            $this->redirect(array('update','id'=>$model->id)); //если пользователь уже создавал чат,
                                                               //переадресуем его на страницу редактирования
        }
	}

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='chat-form')
        {
            echo CActiveForm::validate($model);
            //$err = array('Chat_image'=>array('Выберите изображение'));
            //echo json_encode($err);
            Yii::app()->end();
        }
    }

    /**
     * Вывод страницы редактирования чата
     *
     * при выборе нового изображения для чата, старое удаляется с сервера
     *
     * изображения загружаются в 'uploads/uid_timestamp_filename'
     *
     */
	public function actionUpdate()
	{
		$model = $this->loadModel();

        $this->performAjaxValidation($model);

		if($this->checkUpdatePermission()) // если пользователь является автором - разрешаем редактирование
		{
            if(isset($_POST['Chat'])){
                $model->attributes=$_POST['Chat'];

                print_r($_POST['Chat']);
                $oldImg = $model->img; //сохраняем ссылку на старое изображение
                $uid = Yii::app()->user->id;
                $fileName = $model->fileName ;
                $model->img = $fileName;

                if($model->save()){

                    /* создаем миниатюру изображения */
                    $ih = new CImageHandler(); //Инициализация
                    Yii::app()->ih
                        ->load($this->getImgDir($uid).$fileName) //Загрузка оригинала картинки
                        ->adaptiveThumb($this->_thumbWidth, $this->_thumbHeight) //Создание превьюшки шириной
                        ->save($this->getThumbsDir($uid).$fileName) //Сохранение превьюшки в папку thumbs
                    ;
                    /* --------------------------------- */

                    if(file_exists($this->getImgDir($uid).$oldImg)){
                        unlink($this->getImgDir($uid).$oldImg); //удаляем старое изображение с сервера
                    }
                    if(file_exists($this->getThumbsDir($uid).$oldImg)){
                        unlink($this->getThumbsDir($uid).$oldImg); //удаляем старое изображение с сервера
                    }

                    if(Yii::app()->request->isAjaxRequest){
                        echo 'Сохранено c картинкой!';
                        Yii::app()->end();
                    }else{
                        Yii::app()->user->setFlash('chatSubmitted','Сохранено!');
                        $this->redirect(array('view','id'=>$model->id));
                        // $this->refresh();
                    }

                }
            }else{
                $this->render('update',array(
                    'model'=>$model,
                    'isAuthor'=>$model->isAuthor(Yii::app()->user->id),
                ));
            }
        }else{ //если пользаватель не является автором чата - запрещаем редактирование
            Yii::app()->request->redirect(Yii::app()->createUrl('site/index'));
        }
	}

    /**
     * Сохранение изображение присланного через AJAX
     *
     * изображение сохраняется в каталог с именем состоящим из id автора
     * если каталог не существует - то он создается
     *
     * @param $uid - id пользователя
     * @param $uploadDir - дирректория в которой будут лежать папки пользователей
     *
     * @return JSON result (filename, uploadDir, success)
     */
    public function actionUpload()
    {
        $uid = Yii::app()->user->id;
        $uploadDir = $this->_imgUploadDir.$uid; // 'uploads/979987'

        if(!is_dir($uploadDir)){
            $this->createUserDir($this->_imgUploadDir, $uid); //если директория пользователя не существует - создаем
        }

        Yii::import("ext.EAjaxUpload.qqFileUploader");

        $allowedExtensions = array("jpg","jpeg","png","bmp","gif");//array("jpg","jpeg","gif","exe","mov" and etc...
        $sizeLimit = $this->uploadSizeLimitMB * 1024 * 1024;// maximum file size in bytes
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($uploadDir.'/');

        $result['uploadDir'] = Yii::app()->getBaseUrl(true).'/'.$uploadDir.'/';

        $result=htmlspecialchars(json_encode($result), ENT_NOQUOTES);
//TODO:сделать превьюшку и запись в базе
        //$fileSize=filesize($folder.$result['filename']);//GETTING FILE SIZE
       // $fileName=$result['filename'];//GETTING FILE NAME


        echo $result;// it's array
    }

    /**
     * Создание пользовательской директории на сервере через ftp
     * в пользовательской директории создается папка thumbs для миниатюр изображений
     *
     * @param $uid - id пользователя
     * @param $uploadDir - дирректория в которой будут лежать папки пользователей
     *
     * @return bool
     */
    public function createUserDir($uploadDir, $uid){
        $host = Yii::app()->params['ftp']['host'];
        $login = Yii::app()->params['ftp']['login'];;
        $pass = Yii::app()->params['ftp']['pass'];;
        $ftp = ftp_connect($host, 21, 30); // соединяемся с локальным FTP (21-ый порт, 30 секунд - таймаут)

        if (!empty($ftp)) {

            if (ftp_login($ftp, $login, $pass)) { // авторизуемся анонимным юзером

                if (ftp_chdir($ftp, $uploadDir)) { // меняем текущую директорию на pub

                    // создаем директорию на сервере
                    if (!ftp_mkdir($ftp, $uid)) {
                        echo 'Cannot create directory to FTP';
                        return false;
                    } else {
                        ftp_chdir($ftp, $uid);
                        $thumbs = 'thumbs';
                        if (!ftp_mkdir($ftp, $thumbs)) {
                            echo 'Cannot create directory to FTP';
                            return false;
                        } else {
                            return true;
                        }
                    }

                } else {
                    echo 'Cannot find uploads directory';
                    return false;
                }

            } else {
                echo 'Cannot login at FTP';
                return false;
            }

            ftp_close($ftp);
            return true;
        } else {
            echo 'Cannot connect to FTP';
            return false;
        }
    }

    /**
     * Проверяет имеет ли пользователь права на изменение чата
     *
     * @return bool
     */
    public function checkUpdatePermission(){
        $uid = Yii::app()->user->id;
        if(!isset($this->_model)){ //если модель не загружена
            $this->loadModel();    // загружаем ее
        }
        if($this->_model->isAuthor($uid)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Проверяет имеет ли пользователь права на удаление чата
     *
     * @return bool
     */
    public function checkDeletePermission(){
        $uid = Yii::app()->user->id;
        if(!isset($this->_model)){
            $this->loadModel();
        }
        if($this->_model->isAuthor($uid)){
            return true;
        }else{
            return false;
        }
    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
    //TODO: доработать удаление конференций
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
            if($this->checkDeletePermission()) // если пользователь является автором
            {
                // we only allow deletion via POST request
                $this->loadModel()->delete();

                // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                if(!isset($_GET['ajax']))
                    $this->redirect(array('index'));
            }
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}


	/**
	 * Manages all models.
	 */
    //TODO: сделать админку для конференций
	public function actionAdmin()
	{
		$model=new Chat('search');
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];
		$this->render('admin',array(
			'model'=>$model,
		));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
			{
				$condition='';
				$this->_model=Chat::model()->findByPk($_GET['id'], $condition);
			}
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

}
