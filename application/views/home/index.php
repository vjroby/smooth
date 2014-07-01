<div class="col-md-12">
<div class="page-header">
    <h1>Welocome to Smooth Framework</h1><small>Enjoy your experience.</small>
</div>
</div>
<div class="col-md-12">
    <?php if (isset($messages)): ?>
        <?php foreach($messages as $message): ?>
            <span><?php echo $message->body; ?></span><br/>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="col-md-4 col-md-offset-3">
    <form action="<?php echo \Framework\Smooth::baseUrl(true); ?>/messages/add" method="POST" role="form">
        <?php
        echo Framework\Html::textarea(array(
            "name" => "body",
            "class" => "form-control",
            "wrapper" => array(
                "type" => "div",
                "class" => "form-group"
            )
        ));

        echo \Framework\Html::input(array(
            "type" => "submit",
            "name" => "share",
            "value" => "Share",
            "class" => "btn btn-primary",
            "wrapper" => array(
                "type" => "div",
                "class" => "form-group"
            )
        )); ?>
    </form>
</div>
