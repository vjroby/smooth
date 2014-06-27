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
                            "created" => "created /></div",
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
                    "id" => "query",
                    "name" => "query",
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
</div>

 