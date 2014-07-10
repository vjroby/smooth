<?php $file = $user->fileimage; ?>
<div class="row ajaxInner">
    <div class="highlight-module  highlight-module--left   highlight-module--learning  ">
        <div class="highlight-module__container  icon-star ">
            <div class="highlight-module__content   g-wide--push-1 g-wide--pull-1  g-medium--push-1   ">
                <p class="highlight-module__title"> <?php echo $user->first; ?></p>
                <p class="highlight-module__text"> This is your profile page</p>
                <?php if ($file): ?>
                <img height="100" src="<?php echo \Framework\Smooth::baseUrl(); ?>/thumbnails/<?php echo $file->id; ?>" alt=""/>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>