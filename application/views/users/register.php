<?php

?>
<div class="row ajaxInner">
    <?php if (isset($success)): ?>
        <div class="col-md-4 col-md-offset-3">
            <span class="label label-success">Your account has been created</span>
        </div>
    <?php endif; ?>
    <?php if (!isset($success)): ?>
        <div class="page-header">
            <h1>Register <small>For a new account</small></h1>
        </div>
    <div class="col-md-6 col-md-offset-3" role="form">
        <form action="" method="POST" class="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first" class="">First:</label>
                <input type="text" name="first" id="first" class="form-control"  placeholder="Enter First Name" />
        <?php if (isset($first_error)): ?>
                <span class="help-block label label-danger"><?php echo $first_error; ?></span>
        <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="last" class="">Last::</label>
                <input type="text" name="last" id="last" class="form-control"  placeholder="Enter Last Name" />
                <?php if (isset($last_error)): ?>
                    <span class="help-block label label-danger"><?php echo $last_error; ?></span>
                <?php endif; ?>
            </div>
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
            <?php echo \Framework\Html::input(array(
                "type" => "file",
                "name" => "photo",
                "label" => array(
                    "title" => "Photo"
                ),
                "wrapper" => array(
                    "class" => "form-group"
                ),
            )); ?>
            <div class="form-group">
                <input type="submit" name="register" value="register" class="btn btn-primary" />
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>