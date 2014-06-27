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


            <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <?php if (isset($user)): ?>
                                <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/logout">Logout</a></li>
                                <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/profile">Profile</a></li>
                                <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/search">Search</a></li>
                            <?php endif; ?>
                            <?php if (!isset($user)): ?>
                                <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/login">Login</a></li>
                                <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/register">Register</a></li>
                            <?php endif; ?>

                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="row" style="margin-top: 58px">



            <section>
                <?php echo $content; ?>
            </section>

        </div>
    </div>
</body>
</html>