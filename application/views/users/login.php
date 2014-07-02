<?php

?>
<div class="row ajaxInner">

        <div class="page-header">
            <h1>Login <small>To have access to wonderful features.</small></h1>
        </div>
        <div class="col-md-6 col-md-offset-3" role="form">
            <form action="<?php echo \Framework\Smooth::baseUrl(); ?>/login" method="POST" class="">
                <div class="form-group">
                    <label for="email" class="">Email:</label>
                    <input type="email" name="email" id="email" class="form-control"  placeholder="Enter email" />
                    <?php if (isset($email_error)): ?>
                        <span class="help-block label label-danger"><?php echo $email_error; ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password" class="">Password:</label>
                    <input type="password" name="password" id="password" class="form-control"  placeholder="Enter password" />
                    <?php if (isset($password_error)): ?>
                        <span class="help-block label label-danger"><?php echo $password_error; ?></span>
                    <?php endif; ?>
                </div>
                <?php
                echo \Framework\Html::input(array(
                    "type" => "submit",
                    "name" => "login",
                    "value" => "login",
                    "class" => "btn btn-primary",
                    "wrapper" => array(
                        "class" => "form-group"
                    ),
                    "customTags" => array("ajax","no")
                ));
                ?>
            </form>
        </div>
</div>