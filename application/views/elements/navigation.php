<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if (!is_null($user)): ?>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/logout">Logout</a></li>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/profile">Profile</a></li>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/search">Search</a></li>
                <?php endif; ?>
                <?php if (is_null($user)): ?>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/login">Login</a></li>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/register">Register</a></li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>