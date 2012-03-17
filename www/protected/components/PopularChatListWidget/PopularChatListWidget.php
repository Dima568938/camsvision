<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Diman
 * Date: 19.10.11
 * Time: 20:02
 * To change this template use File | Settings | File Templates.
 */
 
class PopularChatListWidget extends CWidget {

    public $rowCount = 10; // колличество отображаемоых записей об активности

    public function run() {

        $criteria=new CDbCriteria(array(
            'order'=>'viewer_count DESC',
        ));

        $dataProvider=new CActiveDataProvider('Chat', array(
            'pagination'=>array(
                'pageSize'=>3,
            ),
            'criteria'=>$criteria,
        ));

        $this->render('widget',array(
            'dataProvider'=>$dataProvider,
        ));
    }


}

