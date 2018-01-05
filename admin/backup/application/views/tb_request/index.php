<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Request Listing</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>R Id</th>
						<th>R Date</th>
						<th>R Time</th>
						<th>R S Id</th>
						<th>R P Id</th>
						<th>R Session Key</th>
						<th>R Amount</th>
						<th>R Status</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_request as $t){ ?>
                    <tr>
						<td><?php echo $t['r_id']; ?></td>
						<td><?php echo $t['r_date']; ?></td>
						<td><?php echo $t['r_time']; ?></td>
						<td><?php echo $t['r_s_id']; ?></td>
						<td><?php echo $t['r_p_id']; ?></td>
						<td><?php echo $t['r_session_key']; ?></td>
						<td><?php echo $t['r_amount']; ?></td>
						<td><?php echo $t['r_status']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_request/edit/'.$t['r_id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_request/remove/'.$t['r_id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
