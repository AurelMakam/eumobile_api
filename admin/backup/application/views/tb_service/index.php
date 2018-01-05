<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Service Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('tb_service/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>Col Id</th>
						<th>Col Name</th>
						<th>Col Description</th>
						<th>Col Status</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_service as $t){ ?>
                    <tr>
						<td><?php echo $t['col_id']; ?></td>
						<td><?php echo $t['col_name']; ?></td>
						<td><?php echo $t['col_description']; ?></td>
						<td><?php echo $t['col_status']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_service/edit/'.$t['col_id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_service/remove/'.$t['col_id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
