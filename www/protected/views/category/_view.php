<div class="conference">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->getUrl($data->id)); ?>
	</div>
	<div class="nav">
        Число конференций: <?php echo $data->conferencesCount?>
        <br/>
	</div>
</div>
<br/>
