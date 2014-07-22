<div class="ajaxInner">
    <div class="col-md-12 ">
        <div class="page-header">
            <h1>Welocome to Smooth Framework</h1><small>Enjoy your experience.</small>
        </div>
    </div>
    <div class="col-md-6 col-md-offset-3">

        <?php if (isset($messages)): ?>
            <div class="media">
                <?php foreach($messages as $message): ?>
                    <div class="media-body">
                        <h4 class="media-heading">Message:</h4>
                        <?php echo $message->body; $message_id = $message->id; ?></div>
                    <?php $replys = Message::fetchReplies($message_id) ?>
                    <div class="media-body">
                        <?php foreach($replys as $reply): ?>
                            <div class="media">
                                <h6 class="media-heading">Reply (<?php echo $reply->userName; ?>):</h6>
                                <?php echo $reply->body; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
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
                            "type" => "hidden",
                            "name"  => "message",
                            "value" => $message->id,
                        ));

                        echo \Framework\Html::input(array(
                            "type" => "submit",
                            "name" => "share",
                            "value" => "Reply",
                            "class" => "btn btn-primary",
                            "wrapper" => array(
                                "type" => "div",
                                "class" => "form-group"
                            )
                        )); ?>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if (isset($user)): ?>

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
    <?php endif; ?>

    <?php
    $pass = '$1$amTHZJpN$E/bucsmI8g3mTN8MP/1K31';

    $salt = 'amTHZJpN$';

    $just_pass = 'E/bucsmI8g3mTN8MP/1K31'

    ?>
</div>
