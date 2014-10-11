<nav class="navdrawer-container promote-layer" role="navigation">
            <ul class="">
                <?php if (!is_null($user)): ?>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/logout" ajax="no">Logout</a></li>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/home">Home</a></li>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/profile">Profile</a></li>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/search">Search</a></li>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/settings">Settings</a></li>
                    <?php if ($user->admin): ?>
                        <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/view">View Users</a></li>
                        <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/files/view">View Files</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (is_null($user)): ?>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/login">Login</a></li>
                    <li class=""><a href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/register">Register</a></li>
                <?php endif; ?>

            </ul>
</nav>