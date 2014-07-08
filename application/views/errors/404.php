<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Smooth</title>
    <link rel="stylesheet" href="<?php echo \Framework\Smooth::baseUrl(); ?>/css/bootstrap.css"/>
    <link rel="stylesheet" href="<?php echo \Framework\Smooth::baseUrl(); ?>/css/bootstrap-theme.css"/>
    <script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/jquery-1.11.1.js" ></script>
    <script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/bootstrap.min.js" ></script>
    <script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/app.js" ></script>
    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->
</head>
<body>
<div class="big-container container">
    <div class="row">


        <!--        ??php $this->element('navigation', array('user' => isset($user) ? $user : null,'muie' => 'laba')) ?>-->
    </div>
    <div class="row" style="margin-top: 58px">



        <section id="ajaxWrapper">
            <div class="row ajaxInner" style="margin-top: 20px;">
                <div class="col-md-6 col-md-offset-2">
                    <span class="label label-danger">404 Error</span>
                    <?php if(DEBUG ): ?>
                        <?php print_r($e); ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </div>
</div>
</body>
</html>