<?php




class Recomended extends CActiveRecord
{
	const TYPE_PUBLIC = 0; //открытый чат
	const TYPE_PRIVATE = 1; //закрытый чат


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{recomended}}';
	}

	public function rules()
	{
		return array(

		);
	}

	public function relations()
	{
		return array(
			'chat' => array(self::BELONGS_TO, 'Chat', 'cid'),
		);
	}

	public function attributeLabels()
	{
		return array(

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


    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            //$ckey = $this->generateCKey();

            if($this->isNewRecord)
            {
                $this->create_time=date('Y-m-d H:i:s',time());
            }else
                $this->update_time=date('Y-m-d H:i',time());

            return true;

        }else{
            return false;
        }
    }

}