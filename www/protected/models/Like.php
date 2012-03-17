<?php

class Like extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{like}}';
	}

	public function rules()
	{
		return array(

		);
	}

	public function relations()
	{
		return array(
            'user' => array(self::BELONGS_TO, 'User', 'uid'),
			'chat' => array(self::BELONGS_TO, 'Chat', 'cid' ),
		);
	}

	public function attributeLabels()
	{
		return array(

		);
	}


    /**
     * Добавляет лайк к чату
     * добавляет запись в таблицу активности
     *
     * @param $uid
     * @param $cid
     *
     * @return bool
     *
     */
    public function addLike($uid, $cid)
    {
        if(isset($uid) && isset($cid)){
            $like = $this->model()->find('uid=? AND cid=?',$uid, $cid);
            if($like===null){
                $like = new Activity;
                $like->uid = $uid;
                $like->cid = $cid;
                if($like->save()){
                    Activity::model()->addActivity($uid, $cid, Activity::TYPE_LIKE);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Удаляет лайк
     *
     * @param $uid
     * @param $cid
     *
     * @return bool
     *
     */
    public function deleteLike($uid, $cid)
    {
        if(isset($uid) && isset($cid)){
            $like = $this->model()->find('uid=? AND cid=?',$uid, $cid);
            if($like!=null){
                if($like->delete()) return true;
            }
        }
        return false;
    }

    /**
     * Проверяет ставил ли пользователь лайк
     *
     * @param $uid
     * @param $cid
     *
     * @return bool
     *
     */
    public function isLiked($uid, $cid)
    {
        if(isset($uid) && isset($cid)){
            $like = $this->model()->find('uid=? AND cid=?',$uid, $cid);
            if($like!=null){
                return true;
            }
        }
        return false;
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            $this->create_time=date('Y-m-d H:i:s',time());
            return true;
        }else{
            return false;
        }
    }


}