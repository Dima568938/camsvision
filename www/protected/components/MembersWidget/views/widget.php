<div class="members">
    <div style='background-color: red;'>
        <h4> <?=$title?> (<?=$membersCount?>)</h4>
    </div>


    <? if ($membersCount):

        foreach($members as $item):
        $profile = $profiles[$item->uid];
        ?>

        <div class="member" id="c<?php echo $item->id; ?>">

            <img src="<?=$profile['photo']?>" alt="">
            <div class="user">
                <?php echo CHtml::link( $user->fname.' '.$user->lname, 'http://vk.com/id'.$user->id); ?>
            </div>

        </div>
        <br/>

        <?endforeach;
    else: ?>
      Пока нет участников <br/>

    <?endif;?>
    ----------------------------------------------------------------------------------------------------------------------
</div><!-- members -->