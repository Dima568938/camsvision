
<?php $this->widget('zii.widgets.CListView', array(
    'id'=>'yw58',
    //'ajaxUpdate'=>true,
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
    'ajaxUrl'=>Yii::app()->createUrl('find/index'),
    //'ajaxVar'=>'content',
    //'viewData'=>array(),
    'template'=>"{items}\n{pager}",
)) ?>