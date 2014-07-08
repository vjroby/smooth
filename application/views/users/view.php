<div class="row ajaxInner">
    <div class="col-md-12">
        <?php if ($users != false): ?>
            <table class="table">
                <tr>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Picture</td>
                    <td>Edit</td>
                    <td>Actions</td>
                </tr>
                <?php foreach($users as $u): ?>
                    <?php $file = $u->fileimage; ?>
                    <tr>
                        <td><?php echo $u->first.' '.$u->last; ?></td>
                        <td><?php echo $u->email; ?></td>
                        <td>
                            <?php if ($file): ?>

                                <img height="100" src="<?php echo \Framework\Smooth::baseUrl(); ?>/thumbnails/<?php echo $file->id; ?>" alt=""/>
                            <?php endif; ?>

                        </td>
                        <td>
                            <a class="btn btn-primary" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/edit/<?php echo $u->id; ?>"> Edit</a>
                        </td>
                        <?php if ($u->live): ?>
                            <td><a class="btn btn-danger" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/delete/<?php echo $u->id; ?>" ajax="no">Delete</a></td>
                        <?php endif; ?>
                        <?php if (!$u->live): ?>
                            <td><a class="btn btn-primary" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/undelete/<?php echo $u->id; ?>" ajax="no">Undelete</a></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>