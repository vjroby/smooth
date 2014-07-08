<div class="row ajaxInner">
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <td>Thumbnail</td>
                <td>Change</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach($files as $file): ?>
                <tr>
                    <td>  <img height="100" src="<?php echo \Framework\Smooth::baseUrl(); ?>/thumbnails/<?php echo $file->id; ?>" alt=""/></td>
                    <td>
                        <?php if ($file->deleted): ?>
                            <a class="btn btn-primary" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/files/undelete/<?php echo $file->id; ?>" ajax="no">Undelete</a>
                        <?php endif; ?>
                        <?php if (!$file->deleted): ?>
                            <a class="btn btn-primary" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/files/delete/<?php echo $file->id; ?>" ajax="no">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>