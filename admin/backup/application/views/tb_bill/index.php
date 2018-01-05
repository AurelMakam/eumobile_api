<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Bill Listing</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>Col Id</th>
						<th>Col Partnerid</th>
						<th>Col Billnumber</th>
						<th>Col Billamount</th>
						<th>Col Billdate</th>
						<th>Col Billduedate</th>
						<th>Col Customermobile</th>
						<th>Col Customername</th>
						<th>Col Billlabel</th>
						<th>Col Paymentdate</th>
						<th>Col Status</th>
						<th>Col Payment Trans Id</th>
						<th>Col Paymentcomment</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_bill as $t){ ?>
                    <tr>
						<td><?php echo $t['col_id']; ?></td>
						<td><?php echo $t['col_partnerid']; ?></td>
						<td><?php echo $t['col_billnumber']; ?></td>
						<td><?php echo $t['col_billamount']; ?></td>
						<td><?php echo $t['col_billdate']; ?></td>
						<td><?php echo $t['col_billduedate']; ?></td>
						<td><?php echo $t['col_customermobile']; ?></td>
						<td><?php echo $t['col_customername']; ?></td>
						<td><?php echo $t['col_billlabel']; ?></td>
						<td><?php echo $t['col_paymentdate']; ?></td>
						<td><?php echo $t['col_status']; ?></td>
						<td><?php echo $t['col_payment_trans_id']; ?></td>
						<td><?php echo $t['col_paymentcomment']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_bill/edit/'.$t['col_id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_bill/remove/'.$t['col_id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
