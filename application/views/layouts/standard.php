<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">-->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Backend management platform">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="196x196" href="<?php echo \Framework\Smooth::baseUrl(); ?>/images/touch/chrome-touch-icon-196x196.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Web Starter Kit">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="<?php echo \Framework\Smooth::baseUrl(); ?>/images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <title>Smooth</title>

    <!-- build:css styles/components/main.min.css -->
    <link rel="stylesheet" href="<?php echo \Framework\Smooth::baseUrl(); ?>/css/h5bp.css">
    <link rel="stylesheet" href="<?php echo \Framework\Smooth::baseUrl(); ?>/css/components/components.css">
    <link rel="stylesheet" href="<?php echo \Framework\Smooth::baseUrl(); ?>/css/main.css">
    <!-- endbuild -->

    <link rel="stylesheet" href="<?php echo \Framework\Smooth::baseUrl(); ?>/css/bootstrap.css"/>
    <link rel="stylesheet" href="<?php echo \Framework\Smooth::baseUrl(); ?>/css/bootstrap-theme.css"/>

    <!--[if gte IE 9]>
    <style type="text/css">
        .gradient {
            filter: none;
        }
    </style>
    <![endif]-->
</head>
<body>
<header class="app-bar promote-layer">
    <div class="app-bar-container">
        <button class="menu">
            <img src="<?php echo \Framework\Smooth::baseUrl(); ?>/images/hamburger.svg" alt="Menu">
        </button>
        <h6 class="logo">Smooth Framework</h6>
        <section class="app-bar-actions">
            <!-- Put App Bar Buttons Here -->
        </section>
    </div>
</header>

<?php $this->element('navigation', array('user' => isset($user) ? $user : null,'muie' => 'laba')) ?>
<div class="navdrawer-bg promote-layer"></div>
<main>
    <section id="ajaxWrapper">
        <?php echo $content; ?>
    </section>
</main>

<script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/jquery-1.11.1.js" ></script>
<script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/app.js" ></script>
<script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/main.js" ></script>
</body>
</html>