<div class="conference">
	<div class="title">
        Конференция: <?php echo CHtml::link(CHtml::encode($data->conference->title), Conference::model()->getUrl($data->cid)); ?>
	</div>
	<div class="author">
		Пользователь : <?php echo CHtml::link(CHtml::encode($data->uid), $data->uid);?>
	</div>

	<div class="nav">
        <br/>
		Дата создания : <?php echo date("F j, Y",strtotime($data->create_time)); ?>
	</div>
</div>
<br/>
