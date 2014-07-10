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
        <button class="menu"><img src="<?php echo \Framework\Smooth::baseUrl(); ?>/images/hamburger.svg" alt="Menu"></button>
        <h1 class="logo">Smooth Framework</h1>
        <section class="app-bar-actions">
            <!-- Put App Bar Buttons Here -->
        </section>
    </div>
</header>


<?php
//TODO integrate the navigation somehow
 //$this->element('navigation', array('user' => isset($user) ? $user : null,'muie' => 'laba')) ?>

<main>
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
</main>

<script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/jquery-1.11.1.js" ></script>
<script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/bootstrap.min.js" ></script>
<script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/app.js" ></script>
<script type="text/javascript" src="<?php echo \Framework\Smooth::baseUrl(); ?>/js/main.js" ></script>
</body>
</html>