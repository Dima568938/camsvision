<div class="view">

	<b><?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id'=>$data->id)); ?></b>
	<br />
    <br />

	<?php echo CHtml::encode($data->content); ?>
	<br />
    <?php echo CHtml::link('Подробнее ->', array('view', 'id'=>$data->id)); ?>
    <br />
    <br />

	<b>Автор: </b>
	<?php //echo CHtml::encode($data->author->username); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    <b>Дата создания: </b>
	<?php echo CHtml::encode(date('d.m.Y',time($data->create_time))); ?>
	<br />
</div>