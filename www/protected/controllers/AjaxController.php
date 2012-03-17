<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dima
 * Date: 01.02.12
 * Time: 2:10
 * To change this template use File | Settings | File Templates.
 */

class AjaxController extends CController {

    function actionIndex(){
        $request = Yii::app()->request;
        $input = $request->getQuery('input');

        $output = 'test ajax output';

        // если запрос асинхронный, то нам нужно отдать только данные
        if(Yii::app()->request->isAjaxRequest){
            echo CHtml::encode($output);
        }
        else {
            $output = 'Запрос не является AJAX';
            echo CHtml::encode($output);
            /*// если запрос не асинхронный, отдаём форму полностью
            $this->render('form', array(
                'input'=>$input,
                'output'=>$output,
            ));*/
        }
    }

    // добавить активность
    function actionAddActivity(){
        $request = Yii::app()->request;
        $uid = Yii::app()->user->id;
        $output = '';

        if($request->isAjaxRequest && isset($uid)){

            $cid = $request->getQuery('cid');
            $type = $request->getQuery('type');

            if (isset($uid) && isset($cid)&& isset($uid)){
                $output = Activity::model()->AddActivity($uid, $cid, $type)?'Активность успешно добавлена':'Error';
            }else{
                $output = 'Error. No enought parameters';
            }
        } else {
            $output = 'Error. Запрос не является AJAX';
        }
        echo CHtml::encode($output);

    }

}