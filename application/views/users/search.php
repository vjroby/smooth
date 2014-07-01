<?php
$pages = array(1);
?>
<div class="row">
    <div class="col-md-12">
        <form action="" role="form" method="POST" class="form-inline">
            <div class="form-group">
                <label for="query" class="sr-only" >Query:</label>
                <input class="form-control" type="text" name="query" id="query" placeholder="Enter Query" value="<?php echo $query; ?>"/>
            </div>
            <div class="form-group">
                <?php echo \Framework\Html::select(array(
                    "data" => array(
                            "created" => "created",
                            "modified" => "modified",
                            "first" => "first",
                            "last" => "last",
                    ),
                    "class" => "form-control",
                    "id" => "order",
                    "name" => "order",
                    "label" => array(
                        "title" => "Query",
                        "class" => "sr-only"
                    ),
                )); ?>
            </div>
            <div class="form-group">
                <?php echo \Framework\Html::select(array(
                    "data" => array(
                        "asc" => "Asceding",
                        "desc"  => "Descending"
                    ),
                    "class" => "form-control",
                    "id" => "direction",
                    "name" => "direction",
                    "label" => array(
                        "title" => "Direction",
                        "class" => "sr-only"
                    ),
                )); ?>
            </div>
            <div class="form-group">
                <?php echo \Framework\Html::select(array(
                    "data" => $pages,
                    "class" => "form-control",
                    "id" => "page",
                    "name" => "page",
                    "label" => array(
                        "title" => "Page",
                        "class" => "sr-only"
                    ),
                )); ?>
            </div>
            <div class="form-group">
                <?php  echo \Framework\Html::input(array(
                    'type' => 'submit' , 'name' => 'search', 'value' => 'search',
                    'class' => 'btn btn-primary'
                ));?>
            </div>

        </form>
    </div>
    <div class="col-md-12 clearfix" style="margin-bottom: 10px;">

    </div>
    <div class="col-md-12">
       <?php if ($users != false): ?>
           <table class="table">
               <tr>
                   <td>Name</td>
                   <td>Actions</td>
               </tr>
               <?php foreach($users as $u): ?>
               <tr>
                   <td><?php echo $u->first.' '.$u->last; ?></td>
                   <?php if (User::hasFriend($user['id'], $u->id)): ?>
                       <td><a class="btn btn-danger" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/unfriend/<?php echo $u->id; ?>">unfriend</a></td>
                   <?php endif; ?>
                   <?php if (!User::hasFriend($user['id'], $u->id)): ?>
                       <td><a class="btn btn-primary" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/friend/<?php echo $u->id; ?>">friend</a></td>
                   <?php endif; ?>
               </tr>
               <?php endforeach; ?>
           </table>
       <?php endif; ?>
    </div>
</div>

 