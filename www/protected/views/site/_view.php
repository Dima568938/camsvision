<div class="conference">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->getUrl($data->id)); ?>
	</div>
    <? echo $this->chatThumb($data->img, $this->_thumbWidth, $this->_thumbHeight) ?>
	<div class="author">
        <? //var_dump($profiles)?>
		Автор : <?php echo $profiles[$data->org_id]['first_name'].' '.$profiles[$data->org_id]['last_name']?>
	</div>
	<div class="content">
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
			echo $data->description;
			$this->endWidget();
		?>
	</div>
	<div class="nav">
        Число участников: <?php echo $data->subscribersCount?>
        <br/>
		Дата создания : <?php echo date("F j, Y",strtotime($data->create_time)); ?>
	</div>
</div>
<br/>
