<?php





class SiteController extends CController
{
    public $layout='//layouts/main';


    //TODO: нужно убрать
    public $_imgUploadFolder = 'uploads/';
    public $_thumbsUploadFolder = 'uploads/thumbs/';
    public $_imgWidth = 300; // максимальная ширина изображения
    public $_imgHeight = 300;
    public $_thumbWidth = 70;
    public $_thumbHeight = 70;


    /**
     * проверка viewer_id на подлинность
     *
     * @param $viewerId
     *
     * @return bool
     */
    public function isViewerIdValid($viewerId)
    {
        $apiSecret = Yii::app()->params['apiSecret'];
        $apiId = Yii::app()->params['apiId'];
        if(isset($_GET['auth_key'])){
            $authKey = $_GET['auth_key'];
            if($authKey==md5($apiId.'_'.$viewerId.'_'.$apiSecret)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    /**
     * Возвращает информацию о пользователе из первого запроса к Api
     *
     * первый запрос к API VK (указывается в настройках приложения):
     * method=getProfiles&uids={viewer_id}&fields={uid,first_name,last_name,photo,photo_medium,photo_big,sex,city}&format=json&v=2.0 - пример запроса
     *
     * используемый запрос:
     *
     * method=execute&code=
     * var a =  API.getProfiles({"uids":{viewer_id}, "fields":"uid,first_name,last_name,photo,photo_medium,photo_big,sex,country,city",
     * "format":"json", "v":"2.0"});

     * var country_id = a@.country;
     * var city_id = a@.city;
     * var api_id = 2834906;

     * var country_name = API.getCountries({"api_id":api_id,"v":"2.0","cids":country_id,"format":"json"})@.name;
     * var city_name = API.getCities({"api_id":api_id,"v":"2.0","cids":city_id,"format":"json"})@.name;

     * return {"first_name":a@.first_name,"last_name":a@.last_name,"photo":a@.photo,"photo_big":a@.photo_big,"photo_medium":a@.photo_medium,"sex":a@.sex,"country":country_name,"city":city_name};
     * &format=json
     *
     *
     * @return array
     */
    public function getPersonalInfo()
    {
        if(!defined(YII_NO_VK)){
            if(!isset($_GET['api_result'])||($_GET['api_result']=='')){
                echo 'Не получен результат первого запроса к VK API';
                Yii::app()->end();
            }
            //получаем данные пользователя из первого запроса к аpi
            $apiResult = json_decode($_GET['api_result'],TRUE);

        }else{
            // тестовая строка
           $apiResult = json_decode('{"response":[{"uid":154469603,"first_name":"Дмитрий","last_name":"Волошин","photo":"http://www.volvo-club.eu/forum/download/file.php?avatar=666_1287592143.gif","photo_medium":"http://www.google.ru/images/nav_logo101.png","photo_big":"http://www.google.ru/images/nav_logo101.png","sex":2}]}', true);
        }
        $info['firstName'] = $apiResult['response']['first_name'][0];
        $info['lastName'] = $apiResult['response']['last_name'][0];
        $info['photo'] = $apiResult['response']['photo'][0];
        $info['photoMedium'] = $apiResult['response']['photo_medium'][0];
        $info['photoBig'] = $apiResult['response']['photo_big'][0];
        $info['sex'] = isset($apiResult['response']['sex'][0]) ? $apiResult['response']['sex'][0] : 0;
        $info['country'] = isset($apiResult['response']['country'][0]) ? $apiResult['response']['country'][0] : 0;
        $info['city'] =  isset($apiResult['response']['city'][0]) ? $apiResult['response']['city'][0] : 0;
        return $info;
    }

    /**
     * Авторизуем пользователя и записываем данные о нем в сессию
     *
     * @param int $viewerId
     *
     * @return bool
     */
    public function authenticate($viewerId = 1)
    {
        $persInfo = SiteController::getPersonalInfo();
        $fName = $persInfo['firstName'];
        $lName = $persInfo['lastName'];
        $photo = $persInfo['photo'];
        $photoMedium = $persInfo['photoMedium'];
        $photoBig = $persInfo['photoBig'];
        $sex = $persInfo['sex'];
        $country = $persInfo['country'];
        $city = $persInfo['city'];

        $identity = new UserIdentity($viewerId,$fName,$lName, $photo,$photoMedium,$photoBig,$sex, $country, $city);
        // сохраняем данные пользователя в сессию
        $identity->setState('fname',$fName);
        $identity->setState('lname',$lName);
        $identity->setState('photo',$photo);
        $identity->setState('photo_medium',$photoMedium);
        $identity->setState('photo_big',$photoBig);
        $identity->setState('sex',$sex);
        $identity->setState('country',$country);
        $identity->setState('city',$city);

        if($identity->authenticate()) {
            $duration=3600*24*1; // продолжительность сессии - 1 сутки
            Yii::app()->user->login($identity,$duration);
            return true;
        }else{
            return false;
        }
    }


	public function actionIndex()
	{
        if(isset($_GET['viewer_id'])){
            $viewerId = $_GET['viewer_id'];

            Yii::app()->user->logout();

            if(!SiteController::isViewerIdValid($viewerId)&&!defined('YII_NO_VK')){
               echo  CHtml::encode('Неправильный id пользователя');
                Yii::app()->end();
            }

            if(SiteController::authenticate($viewerId)) {
                Yii::app()->user->stayOnline(); // помечаем пользователя - онлайн
            }else{
                echo 'Ошибка авторизации';
                Yii::app()->end();
            }
        }else{
            if(Yii::app()->user->isGuest){
                // пользователь не авторизован
                echo 'Id пользователя не задан';
                Yii::app()->end();
            } else{
                // пользователь уже авторизован - продолжаем работу
                Yii::app()->user->stayOnline(); // помечаем пользователя - онлайн
            }
        }

        //перенаправление на указанный в хеше адрес
        if(isset($_GET['hash'])&&($_GET['hash']!='')){
            $hash=$_GET['hash'];
            Yii::app()->request->redirect($hash);
        }

        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile('http://vk.com/js/api/xd_connection.js?2');
        $cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/vk/init_vk.js' );




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
        $orgIds = $this->getChatOrgIds($chatRecords);  //массив идентификаторов авторов чатов

        $profiles = Yii::app()->VKapi->getPreparedProfiles($orgIds); //получаем профили авторов

        $this->render('index',array(
            'dataProvider'=>$dataProvider,
            'profiles'=>$profiles,
        ));


        //$this->render('index');
	}
    /**
     * Вывод маленькой копии изображения загруженого пользователем
     *
     * @param $img - имя файла и путь
     * @param $title - замещающий текст
     * @param $width - ширина
     * @param $height - высота
     * @param $class - класс
     *
     * @return CHtml::image
     */
    public function chatThumb($img, $width='150',$height='150', $class = 'chat-image')
    {
        $img = $this->_thumbsUploadFolder.$img;
        $title = 'Image';
        if(isset($img) && file_exists($img))
            return CHtml::image(Yii::app()->getBaseUrl(true).'/'.$img, $title,
                array(
                    'width'=>$width,
                    'height'=>$height,
                    'class'=>$class,
                ));
        else
            return CHtml::image(Yii::app()->getBaseUrl(true).'/images/pics/no-image.png','Нет картинки',
                array(
                    'width'=>$width,
                    'height'=>$height,
                    'class'=>$class
                ));
    }

    /**
     * Возвращает массив идентификаторов организаторов чатов
     *
     * @param $activRecords - list of activ records of chat
     *
     * @return массив вида m[1] == $orgId
     */
    public function getChatOrgIds($activRecords){

        if(count($activRecords)){
            foreach($activRecords as $item){
                $uids[] = $item->org_id;
            }
            $result = $uids;
        }else{
            $result = array();
        }
        return $result;
    }

    public function actionContacts()
	{
        $this->render('contacts', array('params' => 'test'));
	}

    public function actionCommunication()
    {
        $this->render('communication', array('params' => 'test'));
    }


    public function actionError()
	{
		echo 'Error 404';
	}

}