<div class="conference">
    <img src="<?=$profile['photo']?>" alt="">
    <div class="name">
        <?php echo CHtml::link( $profile['first_name'].' '.$profile['last_name'], 'http://vk.com/id'.$data->uid); ?>
    </div>


	<div class="nav">
		Дата создания : <?php echo date("F j, Y",strtotime($data->create_time)); ?>
	</div>
</div>
<br/>
