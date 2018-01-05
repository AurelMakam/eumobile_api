<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Biller Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('tb_biller/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>Col Name</th>
						<th>Col Status</th>
						<th>Col Code</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_biller as $t){ ?>
                    <tr>
						<td><?php echo $t['col_name']; ?></td>
						<td><?php echo $t['col_status']; ?></td>
						<td><?php echo $t['col_code']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_biller/edit/'.$t['col_name']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_biller/remove/'.$t['col_name']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
