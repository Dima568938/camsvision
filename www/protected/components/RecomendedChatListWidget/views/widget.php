Виджет
<div class="chat-list">

    <?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
    'viewData'=>array('profiles'=>$profiles),
    'template'=>"{items}\n{pager}",
)); ?>
-----------------------------------------------------------------
</div><!-- activity -->