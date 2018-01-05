<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Paidtxn Listing</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>Col Id</th>
						<th>Col Src Number</th>
						<th>Col Src Name</th>
						<th>Col Dest Number</th>
						<th>Col Dest Name</th>
						<th>Col Amount</th>
						<th>Col Fee</th>
						<th>Col Date Time</th>
						<th>Col Reference</th>
						<th>Col Trxn</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_paidtxn as $t){ ?>
                    <tr>
						<td><?php echo $t['col_id']; ?></td>
						<td><?php echo $t['col_src_number']; ?></td>
						<td><?php echo $t['col_src_name']; ?></td>
						<td><?php echo $t['col_dest_number']; ?></td>
						<td><?php echo $t['col_dest_name']; ?></td>
						<td><?php echo $t['col_amount']; ?></td>
						<td><?php echo $t['col_fee']; ?></td>
						<td><?php echo $t['col_date_time']; ?></td>
						<td><?php echo $t['col_reference']; ?></td>
						<td><?php echo $t['col_trxn']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_paidtxn/edit/'.$t['col_id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_paidtxn/remove/'.$t['col_id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
