<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Timeout Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('tb_timeout/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>Col Partner Id</th>
						<th>Col Use Timeout</th>
						<th>Col Timeout</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_timeout as $t){ ?>
                    <tr>
						<td><?php echo $t['col_partner_id']; ?></td>
						<td><?php echo $t['col_use_timeout']; ?></td>
						<td><?php echo $t['col_timeout']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_timeout/edit/'.$t['col_partner_id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_timeout/remove/'.$t['col_partner_id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
