<?php

class View4conf extends CActiveRecord
{


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{view4conf}}';
	}

	public function rules()
	{
		return array(
			//array('title, description, key, org_id, max_users,', 'required'),
			//array('title', 'length', 'max'=>100),

			//array('title, status', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
            'coference' => array(self::BELONGS_TO, 'Conference', 'uid'),
            'user' => array(self::BELONGS_TO, 'User', 'uid'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
		);
	}


}