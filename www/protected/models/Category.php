<?php

class Category extends CActiveRecord
{

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{category}}';
	}

	public function rules()
	{
		return array(
		//	array('title, description, key, organizer, max_users,', 'required'),
		//	array('title', 'length', 'max'=>100),

		//	array('title, status', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'conferences' => array(self::HAS_MANY, 'Conferences', 'cid' ),
            'conferencesCount' => array(self::STAT, 'Conference', 'cat_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
		);
	}

    public function getUrl($id)
    {
        return Yii::app()->createUrl('category/view', array(
            'id'=>$id,
        ));
    }
}