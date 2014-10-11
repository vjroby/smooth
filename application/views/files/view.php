<div class="row ajaxInner">
    <div class="col-md-12">
        <table class="table-2">
            <col span="1">
            <col span="1">
            <thead>
            <tr>

                <th>Thumbnail</th>
                <th>Change</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($files as $file): ?>
                <tr>
                    <td data-th="Thumbnail">  <img height="100" src="<?php echo \Framework\Smooth::baseUrl(); ?>/thumbnails/<?php echo $file->id; ?>" alt=""/></td>
                    <td data-th="Change">
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