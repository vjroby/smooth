<?php $file = $user->fileimage; ?>
<div class="row ajaxInner">
    <div class="col-md-4 col-md-offset-3">
        <h1>Success!</h1>
    </div>
    <?php if ($file): ?>
        <img height="100" src="<?php echo \Framework\Smooth::baseUrl(); ?>/uploads/<?php echo $file->name; ?>" alt=""/>
    <?php endif; ?>
    <?php
    echo $user->first;
    //echo $user->first.' '.$user->last;

    ?>
</div>