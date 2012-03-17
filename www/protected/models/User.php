<?php

class User extends CActiveRecord
{
    const ROLE_ADMIN = 'administrator';
    const ROLE_MODER = 'moderator';
    const ROLE_USER = 'user';
    const ROLE_BANNED = 'banned';

    const SEX_FEMALE = 1;
    const SEX_MALE = 2;
    const SEX_UNKNOWN = 0;

    const COUNTRY_UNKNOWN = 0;
    const CITY_UNKNOWN = 0;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			// все атрибуты небезопасные
		);
	}

    public function getName()
	{
		return 'User Name!';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'orgChats' => array(self::HAS_MANY, 'Chat', 'org_id'),  //организованные чаты
			'orgChatsCount' => array(self::STAT, 'Chat', 'org_id'), //колличество организованных конференций
            'chat' => array(self::BELONGS_TO, 'Chat', 'org_id'),
            //'memConferences' => array(self::HAS_MANY, 'Member', 'uid'),
			//'memConferencesCount' => array(self::STAT, 'Member', 'uid'),
            //'activities' => array(self::HAS_MANY, 'Activity', 'uid'),    //записи об активности пользователя
            //'activitiesCount' => array(self::STAT, 'Activity', 'uid'),
            //'viewedConferences' => array(self::HAS_MANY, 'View4conf', 'uid'), //просмотренные конференции
            //'viewedConferencesCount' => array(self::STAT, 'View4conf', 'uid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'sex' => 'Пол',
            'country' => 'Страна',
            'city' => 'Город',
			'create_time' => 'Create Time',
			'last_visit' => 'Последний визит',
            'last_action' => 'Last Action Time',
		);
	}

    // возвращает количество пользователей онлайн
    public function getOnlineUserCount()
	{
        $minCount = 100*(Yii::app()->params['userOnlineTime']); // число минут
        $condition = 'last_action > (NOW() - '.$minCount.')'; // last_action > now - 5 min
		return $this->model()->count($condition);
	}

    
    protected function beforeSave()
	{
		if(parent::beforeSave())
		{
  			if($this->isNewRecord)
			{
               // $this->key = md5(rand(100,999999));
				$this->create_time=$this->last_visit=$this->last_action=date('Y-m-d H:i',time());
			}
			else
			    $this->last_action=date('Y-m-d H:i',time());
			return true;
		}
		else
			return false;
	}

    public function primaryKey()
    {
        return 'uid';
        // Для составного первичного ключа следует использовать массив:
        // return array('pk1', 'pk2');
    }

}