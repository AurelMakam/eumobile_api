<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Tb Biller Edit</h3>
            </div>
			<?php echo form_open('tb_biller/edit/'.$tb_biller['col_name']); ?>
			<div class="box-body">
				<div class="row clearfix">
					<div class="col-md-6">
						<div class="form-group">
							<input type="checkbox" name="col_status" value="1" <?php echo ($tb_biller['col_status']==1 ? 'checked="checked"' : ''); ?> id='col_status' />
							<label for="col_status" class="control-label">Col Status</label>
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_code" class="control-label">Col Code</label>
						<div class="form-group">
							<input type="text" name="col_code" value="<?php echo ($this->input->post('col_code') ? $this->input->post('col_code') : $tb_biller['col_code']); ?>" class="form-control" id="col_code" />
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
            	<button type="submit" class="btn btn-success">
					<i class="fa fa-check"></i> Save
				</button>
	        </div>				
			<?php echo form_close(); ?>
		</div>
    </div>
</div>