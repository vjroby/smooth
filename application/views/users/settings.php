<div class="row">
    <div class="page-header">
        <h1>Settings <small>for your account.</small></h1>
    </div>
    <?php if (isset($success)): ?>
        <div class="col-md-4 col-md-offset-3">
            <span class="label label-success">Your account has been created</span>
        </div>
    <?php endif; ?>
    <div class="col-md-6 col-md-offset-3">
        <form action="POST" role="form">
                <?php  echo \Framework\Html::input(array(
                    'type' => 'text' , 'name' => 'first', 'value' => $user['first'],
                    'class' => 'form-control',
                    'label' => array(
                        'title' =>'First name:'
                    ),
                    'wrapper' => array(
                        'class' => 'form-group'
                    ),
                ));?>
        </form>
    </div>
</div>