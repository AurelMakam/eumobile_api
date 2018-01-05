<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Partner Listing</h3>
            	<div class="box-tools">
                    <a href="<?php echo site_url('tb_partner/add'); ?>" class="btn btn-success btn-sm">Add</a> 
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>Col Id</th>
						<th>Col Status</th>
						<th>Col Name</th>
						<th>Col Ip</th>
						<th>Col Date</th>
						<th>Col Login</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_partner as $t){ ?>
                    <tr>
						<td><?php echo $t['col_id']; ?></td>
						<td><?php echo $t['col_status']; ?></td>
						<td><?php echo $t['col_name']; ?></td>
						<td><?php echo $t['col_ip']; ?></td>
						<td><?php echo $t['col_date']; ?></td>
						<td><?php echo $t['col_login']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_partner/edit/'.$t['col_id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_partner/remove/'.$t['col_id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                <div class="pull-right">
                    <?php echo $this->pagination->create_links(); ?>                    
                </div>                
            </div>
        </div>
    </div>
</div>
