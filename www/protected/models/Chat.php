<?php




class Chat extends CActiveRecord
{
	const TYPE_PUBLIC = 0; //открытый чат
	const TYPE_PRIVATE = 1; //закрытый чат


    public $_imgWidth = 300; // максимальная ширина изображения
    public $_imgHeight = 300;
    public $_thumbWidth = 70;
    public $_thumbHeight = 70;
    public $uploadSizeLimitMB = 2; // макс. размер загружаемой картиноки в МВ
    public $_imgUploadDir = 'uploads/';

    public $fileName;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{chat}}';
	}

	public function rules()
	{
		return array(
			array('title', 'required'),
            //array('img', 'required'), // поле изображение является обязательным
			//array('fileName', 'required', 'on'=>'create'),
			array('title', 'length', 'max'=>100),       //длина заголовка
            array('description', 'length', 'max'=>300), //длина описания
           /* array('image', 'file',
                'types'=>'jpg, gif, png, bmp',
                'maxSize' => 1024*1024*5, // 5 MB
                'allowEmpty'=>true,
                'tooLarge'=>'Файл весит больше 5 МВ. Пожалуйста загрузите файл меньшего размера',
            ),*/

            array(' title, description, cat_id ,type, cat_id, fileName', 'safe'),

			array('id, title, description, cat_id ', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'author' => array(self::BELONGS_TO, 'User', 'org_id'),
           // 'author' => array(self::HAS_MANY, 'User', 'uid'),
            'category' => array(self::BELONGS_TO, 'Category', 'cat_id'),
			'subscribers' => array(self::HAS_MANY, 'Subscription', 'cid' ),    //подписчики
            'subscribersCount' => array(self::STAT, 'Subscription', 'cid' ),   //количество подписчиков
            'likeCount' => array(self::STAT, 'Like', 'cid'),
            //'viewsCount' => array(self::STAT, 'View4conf', 'cid' ),  // число просмотров
           // 'votesCount' => array(self::STAT, 'Vote4conf', 'cid' ),
		);
	}

	public function attributeLabels()
	{
		return array(
			'title' => 'Название',
			'description' => 'Описание',
            'image' => 'Изображение',
            'fileName' => 'Изображение',
		);
	}


    /**
     * Добавляет запись о том, что пользователь просмотрел конференцию с данным uid
     *
     * @param $uid
     *
     * @return bool
     */
    public function addView($uid)
    {
       // $view4conf=View4conf::model()->find('uid=? AND cid=?',array($uid, $this->id));

      /*  if($view4conf===null){
            $view4conf = new View4conf;
            $view4conf->uid = $uid;
            $view4conf->cid = $this->id;
            $view4conf->save();
            return true;
        }
*/
        return false;
    }

    /**
     * Вывод изображения загруженого пользователем
     *
     * @param $img - имя файла и путь
     * @param $title - замещающий текст
     * @param $width - ширина
     * @param $height - высота
     * @param $class - класс
     *
     * @return CHtml::image
     */
    public function chatImage($chat, $width='150',$height='150', $class = 'chat-image')
    {
        $orgId = $chat->org_id;
        $img = $this->getImgDir($orgId).$chat->img;
        $title = 'Image';
        if(isset($img) && file_exists($img))
            return CHtml::image(Yii::app()->getBaseUrl(true).'/'.$img, $title,
                array(
                    'width'=>$width,
                    //'height'=>$height,
                    'class'=>$class,
                ));
        else
            return CHtml::image(Yii::app()->getBaseUrl(true).'/images/pics/no-image.png','Нет картинки',
                array(
                    'width'=>$width,
                    //'height'=>$height,
                    'class'=>$class
                ));
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
    public function chatThumb($chat, $width='150',$height='150', $class = 'chat-thumb')
    {
        $orgId = $chat->org_id;
        $img = $this->getThumbsDir($orgId).$chat->img;
        $title = 'Image';
        if(isset($img) && file_exists($img))
            return CHtml::image(Yii::app()->getBaseUrl(true).'/'.$img, $title,
                array(
                    'width'=>$width,
                    //'height'=>$height,
                    'class'=>$class,
                ));
        else
            return CHtml::image(Yii::app()->getBaseUrl(true).'/images/pics/no-image.png','Нет картинки',
                array(
                    'width'=>$width,
                    //'height'=>$height,
                    'class'=>$class
                ));
    }
    /**
     * Возвращает директорию загруженных изображений
     *
     * @return string
     */
    public function getImgDir($uid)
    {
        return $this->_imgUploadDir.$uid.'/';
    }
    /**
     * Возвращает директорию загруженных изображений
     *
     * @return string
     */
    public function getThumbsDir($uid)
    {
        return $this->_imgUploadDir.$uid.'/thumbs/';
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

    /**
     * Проверяет является ли пользователь автором
     *
     * @return bool
     */
    public function isAuthor($uid){
        $orgId = $this->org_id;
        if($uid == $orgId){
            return true;
        }else{
            return false;
        }
    }

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl($id)
	{
		return Yii::app()->createUrl('chat/view', array(
			'id'=>$id,
			//'title'=>$this->title,
		));
	}


    /**
     * Генерирует ключ пользователя
     * ключ генерируется один раз - при создании пользователя
     *
     * @return string
     */
    //TODO: сделать более безопасный ключ
    public function generateCKey()
    {
        $ckey = 'ckey'.rand(1,99999);
        return $ckey;
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);

        $criteria->compare('title',$this->title,true);

        $criteria->compare('description',$this->description,true);

        $criteria->compare('create_time',$this->create_time,true);

        $criteria->compare('cat_id',$this->cat_id);


        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function findChat($query, $sex, $country, $city)
    {

        $criteria=new CDbCriteria();

        $criteria->with = array('author');
        $criteria->together = true;

        $criteria->addSearchCondition('title',$query);
        $criteria->compare('author.sex',$sex);
        $criteria->compare('author.country',$country);
        $criteria->compare('author.city',$city);

        $criteria->order = 't.create_time DESC';

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        ));
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            //$ckey = $this->generateCKey();

            if($this->isNewRecord)
            {
                $uid = Yii::app()->user->id;
                $this->org_id = $uid;
                $this->create_time=date('Y-m-d H:i:s',time());
            }else
                $this->update_time=date('Y-m-d H:i',time());

            return true;

        }else{
            return false;
        }
    }

}