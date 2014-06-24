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
        <header>

        </header>
        <div class="side-panel">
            <ul>
                <li>Home</li>
                <li>Users</li>
                <li>Logout</li>
            </ul>

        </div>

            <section>
            <?php echo $content; ?>
            </section>

    </div>

    </body>
</html>