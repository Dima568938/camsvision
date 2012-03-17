<?php

class Activity extends CActiveRecord
{
    const TYPE_LIKE = 1;
    const TYPE_COMMENT = 2;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{activity}}';
	}

	public function rules()
	{
		return array(
			array('uid, cid, type', 'required'),
		//	array('title', 'length', 'max'=>100),

		//	array('title, status', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
            'user' => array(self::BELONGS_TO, 'User', 'uid'),
			'chat' => array(self::BELONGS_TO, 'Chat', 'cid' ),
		);
	}

    //добавляет активность пользователя(type=1-лайк или type=2-коммент)
    public function addActivity($uid, $cid, $type)
    {
        if(isset($uid) && isset($cid) && isset($type)){
            $activity = new Activity;
            $activity->uid = $uid;
            $activity->cid = $cid;
            $activity->type = $type;
            $activity->create_time = 1;
            if($activity->save()) return true;
        }
        return false;
    }

	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
		);
	}

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            $activity = $this->model()->find('uid=? and cid=?', array($this->uid, $this->cid));
            if($activity == null)
            {
                $this->create_time=date('Y-m-d H:i:s',time());
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


}