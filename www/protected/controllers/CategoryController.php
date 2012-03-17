<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Diman
 * Date: 20.11.11
 * Time: 4:06
 * To change this template use File | Settings | File Templates.
 */
 
//TODO: реализовать CRUD для категорий, сделать вывод категорий в TreeView

class CategoryController extends CController
{
    //public $layout='column2';

    private $_model;

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
                'actions'=>array('index','view'),
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

    /**
     * Displays a particular model.
     */
    public function actionView()
    {
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        //подключаем lightbox
        $cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/lightbox/js/jquery.lightbox-0.5.min.js', CClientScript::POS_END);
        $cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/lightbox/init_lightbox.js' ,CClientScript::POS_END);
        $cs->registerScriptFile(Yii::app()->request->baseUrl.'http://vkontakte.ru/js/api/xd_connection.js?2');
        $cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/vk/init_vk.js' );
        $cs->registerCssFile(Yii::app()->request->baseUrl.'/js/lightbox/css/jquery.lightbox-0.5.css');

        $conference=$this->loadModel();
        $conference->addView(Yii::app()->user->id); // добавить просмотр пользователем
        //$activity = $this->newActivity($chat);;

        if(Yii::app()->request->isAjaxRequest){
            $a=$_POST['ajax'];
            echo CHtml::encode($a);
            Yii::app()->end();
        }else {
            $this->render('view',array(
                'model'=>$conference,
                // 'activity'=>$activity,
            ));
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Conference();
        if(isset($_POST['Post']))
        {
            $model->attributes=$_POST['Post'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        $model=$this->loadModel();
        if(isset($_POST['Post']))
        {
            $model->attributes=$_POST['Post'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete()
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $this->loadModel()->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $criteria=new CDbCriteria(array(
            'order'=>'id DESC',
        ));

        $dataProvider=new CActiveDataProvider('Category', array(
            'criteria'=>$criteria,
        ));

        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new Conference('search');
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
                $this->_model=Conference::model()->findByPk($_GET['id'], $condition);
            }
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $this->_model;
    }

}