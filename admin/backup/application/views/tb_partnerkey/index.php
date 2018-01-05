<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tb Partnerkey Listing</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
						<th>ID</th>
						<th>Col Partnerid</th>
						<th>Col Key</th>
						<th>Col Date</th>
						<th>Actions</th>
                    </tr>
                    <?php foreach($tb_partnerkey as $t){ ?>
                    <tr>
						<td><?php echo $t['id']; ?></td>
						<td><?php echo $t['col_partnerid']; ?></td>
						<td><?php echo $t['col_key']; ?></td>
						<td><?php echo $t['col_date']; ?></td>
						<td>
                            <a href="<?php echo site_url('tb_partnerkey/edit/'.$t['id']); ?>" class="btn btn-info btn-xs"><span class="fa fa-pencil"></span> Edit</a> 
                            <a href="<?php echo site_url('tb_partnerkey/remove/'.$t['id']); ?>" class="btn btn-danger btn-xs"><span class="fa fa-trash"></span> Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                                
            </div>
        </div>
    </div>
</div>
