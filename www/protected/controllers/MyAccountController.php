<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Diman
 * Date: 24.11.11
 * Time: 0:41
 * To change this template use File | Settings | File Templates.
 */


class MyAccountController extends CController
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

    /**
     * Displays a particular model.
     */


    public function actionMyAccount()
    {
        $user = $this->loadModel();
        $profile['first_name'] = Yii::app()->user->fname;
        $profile['last_name'] = Yii::app()->user->lname;
        $profile['photo'] = Yii::app()->user->photo;

        if(Yii::app()->request->isAjaxRequest){
            $a=$_POST['ajax'];
            echo CHtml::encode($a);
            Yii::app()->end();
        }else {
            $this->render('myaccount',array(
                'model'=>$user,
                'profile'=>$profile
            ));
        }
    }

    public function actionUpdate()
    {
        $model = $this->loadModel();
        //$uid = Yii::app()->user->id;
        //$orgId = $model->author->uid;


        if($this->checkUpdatePermission()) // если пользователь является автором
        {
            if(isset($_POST['Conference'])){
                $model->attributes=$_POST['Conference'];
                if($model->save()){
                    Yii::app()->user->setFlash('confSubmitted','Сохранено!');
                    $this->refresh();
                }
            }

            $this->render('update',array(
                'model'=>$model,
                'isAuthor'=>true,
            ));
        }else{
            Yii::app()->request->redirect(Yii::app()->createUrl('site/index'));
        }
    }

    /**
     * Проверяет имеет ли пользователь права на изменение конференции
     *
     * @return bool
     */
    public function checkUpdatePermission(){
        $uid = Yii::app()->user->id;
        if(!isset($this->_model)){ //если модель не загружена
            $this->loadModel();    // загружаем ее
        }
        if($this->_model->isAuthor($uid)){
            return true;
        }else{
            return false;
        }
    }


    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $user = $this->loadModel();
        $profile['first_name'] = Yii::app()->user->fname;
        $profile['last_name'] = Yii::app()->user->lname;
        $profile['photo'] = Yii::app()->user->photo;

        $this->render('index',array(
            'user'=>$user,
            'profile'=>$profile,
        ));
    }

    public function getVKProfiles($activRecords){

        if(count($activRecords)){
            $uids = array();
            foreach($activRecords as $item){
                $uids[] = $item->uid;
            }
            $uidsStr = implode(',',$uids);
            $result = Yii::app()->VKapi->getPreparedProfiles($uidsStr);
        }else{
            $result = array();
        }
        return $result;
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
            $uid = Yii::app()->user->id;
            $condition='';
            $this->_model = User::model()->findByPk($uid, $condition);

        }
        return $this->_model;
    }

}