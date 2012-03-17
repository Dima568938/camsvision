<div class="chat">
	<div class="title">
		<?php echo CHtml::link(CHtml::encode($data->chat->title), $data->chat->getUrl($data->chat->id)); ?>
	</div>
    <?// echo $data->chatThumb($data, $data->_thumbWidth, $data->_thumbHeight) ?>
	<div class="author">
        <? //var_dump($profiles)?>
		Автор : <?php echo $data->chat->author->first_name.' '.$data->chat->author->last_name?>
	</div>
	<div class="content">
		<?php
			$this->beginWidget('CMarkdown', array('purifyOutput'=>true));
			echo $data->chat->description;
			$this->endWidget();
		?>
	</div>
	<div class="nav">
        Число участников: <?php echo $data->chat->subscribersCount?>
        <br/>
		Дата создания : <?php echo date("F j, Y",strtotime($data->create_time)); ?>
	</div>
</div>
<br/>
