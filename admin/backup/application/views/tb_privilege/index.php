<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Privileges Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('tb_privilege/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>P Id</th>
						<th>P Service</th>
						<th>P Partnerid</th>
						<th>P Status</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_privileges as $t){ ?>
                    <tr>
						<td><?php echo $t['p_id']; ?></td>
						<td><?php echo $t['p_service']; ?></td>
						<td><?php echo $t['p_partnerid']; ?></td>
						<td><?php echo $t['p_status']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_privilege/edit/'.$t['p_id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_privilege/remove/'.$t['p_id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
