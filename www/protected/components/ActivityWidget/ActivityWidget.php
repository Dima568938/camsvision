<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Diman
 * Date: 19.10.11
 * Time: 20:02
 * To change this template use File | Settings | File Templates.
 */
 
class ActivityWidget extends CWidget {

    public $title = 'Активность';
    public $rowCount = 10; // колличество отображаемоых записей об активности

    public function run() {

        $params = array('limit'=>$this->rowCount,
                        'order'=>'create_time DESC');
        $activity = Activity::model()->findAll($params);

        $this->render('widget',array(
            'title'=>$this->title,
            'activity'=>$activity,
           // 'profiles'=>$this->getVKProfiles($activity),
        ));
    }

    /**
     * Возвращает массив VK профилей с ключем по uid-дам
     *
     * @param $activRecords - list of activ records
     *
     * @return массив вида m[uid][name] or m[uid][photo]
     */
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

}

