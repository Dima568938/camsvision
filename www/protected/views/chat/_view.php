<div class="chat">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->title), $data->getUrl($data->id)); ?>
	</div>
    <?php echo $data->chatThumb($data, $data->_thumbWidth, $data->_thumbHeight) ?>
	<div class="author">
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
