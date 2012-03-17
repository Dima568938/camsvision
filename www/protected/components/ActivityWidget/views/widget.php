<div class="activity">
    <div style='background-color: red;'>
        <h4> <?=$title?></h4>
    </div>


    <?php foreach($activity as $item) {
        //$profile = $profiles[$item->uid];
        $title = $item->chat->title;
        $user = $item->user;
    ?>

        <div class="activity-item" id="c<?php echo $item->id; ?>">

            <img src="<?=$user->photo?>" alt="">
            <div class="user">
                <?php echo CHtml::link( $user->first_name.' '.$user->last_name, 'http://vk.com/id'.$user->uid); ?>
            </div>

            <div class="title">
                <?php echo CHtml::link($item->chat->title, $item->chat->getUrl($item->chat->id)); ?>
            </div>

            <div class="type">
                <?php echo CHtml::encode('type: '.$item->type); ?>
            </div>
        </div>
        <br/>
    <?php } ?>
-----------------------------------------------------------------
</div><!-- activity -->