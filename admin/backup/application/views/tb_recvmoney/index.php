<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Recvmoney Listing</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>ID</th>
						<th>PartnerId</th>
						<th>Dest Phone</th>
						<th>Amount</th>
						<th>Fees</th>
						<th>Idtype</th>
						<th>Idnumber</th>
						<th>Dest Name</th>
						<th>Sender Name</th>
						<th>Sender Phone</th>
						<th>Date</th>
						<th>TransactionId</th>
						<th>Status</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_recvmoney as $t){ ?>
                    <tr>
						<td><?php echo $t['id']; ?></td>
						<td><?php echo $t['partnerId']; ?></td>
						<td><?php echo $t['dest_phone']; ?></td>
						<td><?php echo $t['amount']; ?></td>
						<td><?php echo $t['fees']; ?></td>
						<td><?php echo $t['idtype']; ?></td>
						<td><?php echo $t['idnumber']; ?></td>
						<td><?php echo $t['dest_name']; ?></td>
						<td><?php echo $t['sender_name']; ?></td>
						<td><?php echo $t['sender_phone']; ?></td>
						<td><?php echo $t['date']; ?></td>
						<td><?php echo $t['transactionId']; ?></td>
						<td><?php echo $t['status']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_recvmoney/edit/'.$t['id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_recvmoney/remove/'.$t['id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
