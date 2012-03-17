<?php


class FindController extends CController
{

	//public $layout='column2';

	private $_model;
    const CHAT_PER_PAGE = 5;

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

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='find-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Вывод списка чатов
     * при поисковом запросе выводится список найденных чатов
     */
    public function actionIndex()
    {
        $model=new FindForm;

        if(Yii::app()->request->isAjaxRequest && (isset($_POST['FindForm']) ||
            (!isset($_POST['FindForm']) && Yii::app()->getGlobalState('FindForm')))){

            if(isset($_POST['FindForm']))
            {
                $model->attributes=$_POST['FindForm'];
                Yii::app()->setGlobalState('FindForm',$_POST['FindForm']);
            }else{
                $model->attributes = Yii::app()->getGlobalState('FindForm');
            }

            $this->performAjaxValidation($model);
            print_r(Yii::app()->getGlobalState('FindForm'));

            if($model->validate())
            {
                $query = $model->query;
                $sex = $model->sex;
                $country = $model->country;
                $city = $model->city;

                $dataProvider = Chat::model()->findChat($query, $sex, $country, $city);

                $dataProvider->pagination = array(
                    'pageSize'=>self::CHAT_PER_PAGE,
                );

                $this->renderPartial('_content',array(
                    'dataProvider'=>$dataProvider,
                ));
            }

        }else{

            Yii::app()->setGlobalState('FindForm',null);
            print_r(Yii::app()->getGlobalState('FindForm'));

            $criteria=new CDbCriteria(array(
                'order'=>'create_time DESC',
            ));

            $dataProvider=new CActiveDataProvider('Chat', array(
                'pagination'=>array(
                    'pageSize'=>self::CHAT_PER_PAGE,
                ),
                'criteria'=>$criteria,
            ));

            $this->render('index',array(
                'dataProvider'=>$dataProvider,
                'model'=>$model,
            ));

        }
    }

    /**
     * Вывод чатов, найденных по поиковому запросу
     * не использую т.к. перезагружаются js скрипты при каждом обновлении
     */
	public function actionAjaxFind()
	{
        if(!Yii::app()->request->isAjaxRequest) throw new CHttpException('Url should be requested via ajax only');
        $model=new  FindForm();

        if (Yii::app()->getGlobalState('FindForm') && !isset($_POST['FindForm'])){
            $model->attributes = Yii::app()->getGlobalState('FindForm');
        }else{
            $model->attributes=$_POST['FindForm'];
            Yii::app()->setGlobalState('FindForm',$_POST['FindForm']);
        }

        $this->performAjaxValidation($model);
        print_r(Yii::app()->getGlobalState('FindForm'));

        if($model->validate())
        {
            $query = $model->query;
            $sex = $model->sex;
            $country = $model->country;
            $city = $model->city;

            $dataProvider = Chat::model()->findChat($query, $sex, $country, $city);

            $dataProvider->pagination = array(
                'pageSize'=>self::CHAT_PER_PAGE,
                'route'=>'find/AjaxFind'
            );
            //print_r($_GET);
            $this->renderPartial('_content',array(
                'dataProvider'=>$dataProvider,
            ), false, true);
        }
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
