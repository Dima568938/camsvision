<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Diman
 * Date: 19.10.11
 * Time: 20:02
 * To change this template use File | Settings | File Templates.
 */
class MembersWidget extends CWidget {

    public $title = 'Участники';
    public $cid = 0;
    public $membersCount = 0;

    public function run() {

        $params = array(
            'order'=>'create_time DESC',
            'condition'=>'cid=:cid',
            'params'=>array(':cid'=>$this->cid),);
        $members = Member::model()->findAll($params);

        $this->membersCount = count($members);

        $this->render('widget',array(
            'title'=>$this->title,
            'members'=>$members,
            'profiles'=>$this->getVKProfiles($members),
            'membersCount'=>$this->membersCount,
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

