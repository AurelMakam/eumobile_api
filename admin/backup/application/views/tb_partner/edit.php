<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Tb Partner Edit</h3>
            </div>
			<?php echo form_open('tb_partner/edit/'.$tb_partner['col_id']); ?>
			<div class="box-body">
				<div class="row clearfix">
					<div class="col-md-6">
						<div class="form-group">
							<input type="checkbox" name="col_status" value="1" <?php echo ($tb_partner['col_status']==1 ? 'checked="checked"' : ''); ?> id='col_status' />
							<label for="col_status" class="control-label">Col Status</label>
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_name" class="control-label">Col Name</label>
						<div class="form-group">
							<input type="text" name="col_name" value="<?php echo ($this->input->post('col_name') ? $this->input->post('col_name') : $tb_partner['col_name']); ?>" class="form-control" id="col_name" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_ip" class="control-label">Col Ip</label>
						<div class="form-group">
							<input type="text" name="col_ip" value="<?php echo ($this->input->post('col_ip') ? $this->input->post('col_ip') : $tb_partner['col_ip']); ?>" class="form-control" id="col_ip" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_date" class="control-label">Col Date</label>
						<div class="form-group">
							<input type="text" name="col_date" value="<?php echo ($this->input->post('col_date') ? $this->input->post('col_date') : $tb_partner['col_date']); ?>" class="has-datetimepicker form-control" id="col_date" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_login" class="control-label">Col Login</label>
						<div class="form-group">
							<input type="text" name="col_login" value="<?php echo ($this->input->post('col_login') ? $this->input->post('col_login') : $tb_partner['col_login']); ?>" class="form-control" id="col_login" />
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