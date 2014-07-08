<div class="row ajaxInner">
    <div class="page-header">
        <h1>Settings <small>for your account.</small></h1>
    </div>
    <?php if (isset($success)): ?>
        <div class="col-md-4 col-md-offset-3">
            <span class="label label-success">The user has been updated!</span>
        </div>
    <?php endif; ?>
    <div class="col-md-6 col-md-offset-3">
        <form action="" method="POST" role="form" enctype="multipart/form-data">
            <?php
            echo \Framework\Html::input(array(
                'type' => 'text' , 'name' => 'first', 'value' => $user->first,
                'class' => 'form-control',
                'label' => array(
                    'title' =>'First name:'
                ),
                'wrapper' => array(
                    'class' => 'form-group'
                ),
            ));
            echo \Framework\Html::input(array(
                'type' => 'text' , 'name' => 'last', 'value' => $user->last,
                'class' => 'form-control',
                'label' => array(
                    'title' =>'Last name:'
                ),
                'wrapper' => array(
                    'class' => 'form-group'
                ),
            ));
            echo \Framework\Html::input(array(
                'type' => 'text' , 'name' => 'email', 'value' => $user->email,
                'class' => 'form-control',
                'label' => array(
                    'title' =>'Email:'
                ),
                'wrapper' => array(
                    'class' => 'form-group'
                ),
            ));
            echo \Framework\Html::input(array(
                'type' => 'password' , 'name' => 'password', 'value' => $user->password,
                'class' => 'form-control',
                'label' => array(
                    'title' =>'Password:'
                ),
                'wrapper' => array(
                    'class' => 'form-group'
                ),
            ));

            echo \Framework\Html::input(array(
                "type" => "checkbox",
                "name" => "live",
                "checked" => $user->live ? 'checked' : 'unchecked',
                "label" => array(
                    "title" => "Live"
                ),
                "wrapper" => array(
                    "class" => "form-group"
                ),
            ));

            echo \Framework\Html::input(array(
                "type" => "checkbox",
                "name" => "admin",
                "checked" => $user->admin ? 'checked' : 'unchecked',
                "label" => array(
                    "title" => "Admin"
                ),
                "wrapper" => array(
                    "class" => "form-group"
                ),
            ));

            echo \Framework\Html::input(array(
                'type' => 'submit' , 'name' => 'save', 'value' => 'Save',
                'class' => 'btn btn-primary',
                'wrapper' => array(
                    'class' => 'form-group'
                ),
                "customTags" => array("ajax" => "no")
            ));

            ?>
        </form>
    </div>
</div>