<?php

class Admin extends CActiveRecord
{
	const STATUS_DRAFT=1;
	const STATUS_PUBLISHED=2;
	const STATUS_ARCHIVED=3;


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{admin}}';
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
			'user' => array(self::BELONGS_TO, 'User', 'uid'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'Id',

		);
	}

	/**
	 * @return string the URL that shows the detail of the post
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl('chat/view', array(
			'id'=>$this->id,
			'title'=>$this->title,
		));
	}

}