<div class="row ajaxInner">
    <div class="col-md-12 ">
        <?php if ($users != false): ?>
            <table class="table-5">
                <colgroup>
                    <col span="1">
                    <col span="1">
                    <col span="1">
                    <col span="1">
                    <col span="1">
                </colgroup>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Picture</th>
                    <th>Edit</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($users as $u): ?>
                    <?php $file = $u->fileimage; ?>
                    <tr>
                        <td data-th="Name"><?php echo $u->first.' '.$u->last; ?></td>
                        <td data-th="Email"><?php echo $u->email; ?></td>
                        <td data-th="Picture" >
                            <?php if ($file): ?>

                                <img height="100" src="<?php echo \Framework\Smooth::baseUrl(); ?>/thumbnails/<?php echo $file->id; ?>" alt=""/>
                            <?php endif; ?>

                        </td>
                        <td data-th="Edit">
                            <a class="button--primary" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/edit/<?php echo $u->id; ?>"> Edit</a>
                        </td>
                        <?php if ($u->live): ?>
                            <td data-th="Actions"><a class="button--danger" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/delete/<?php echo $u->id; ?>" ajax="no">Delete</a></td>
                        <?php endif; ?>
                        <?php if (!$u->live): ?>
                            <td data-th="Actions"><a class="button--primary" href="<?php echo \Framework\Smooth::baseUrl(true); ?>/users/undelete/<?php echo $u->id; ?>" ajax="no">Undelete</a></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>