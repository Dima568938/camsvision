<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Dima
 * Date: 09.02.12
 * Time: 23:23
 * To change this template use File | Settings | File Templates.
 */

?>

<div class="user">
    <img src="<?=$profile['photo']?>" alt="">
    <div class="name">
    <?php echo CHtml::link( $profile['first_name'].' '.$profile['last_name'], 'http://vk.com/id'.$model->uid); ?>
    </div>


    <div class="nav">
        Дата создания : <?php echo date("F j, Y",strtotime($model->create_time)); ?>
    </div>
</div>
<br/>
    <br/>