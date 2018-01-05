<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Tb Partnerkey Edit</h3>
            </div>
			<?php echo form_open('tb_partnerkey/edit/'.$tb_partnerkey['id']); ?>
			<div class="box-body">
				<div class="row clearfix">
					<div class="col-md-6">
						<label for="col_partnerid" class="control-label">Col Partnerid</label>
						<div class="form-group">
							<input type="text" name="col_partnerid" value="<?php echo ($this->input->post('col_partnerid') ? $this->input->post('col_partnerid') : $tb_partnerkey['col_partnerid']); ?>" class="form-control" id="col_partnerid" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_key" class="control-label">Col Key</label>
						<div class="form-group">
							<input type="text" name="col_key" value="<?php echo ($this->input->post('col_key') ? $this->input->post('col_key') : $tb_partnerkey['col_key']); ?>" class="form-control" id="col_key" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_date" class="control-label">Col Date</label>
						<div class="form-group">
							<input type="text" name="col_date" value="<?php echo ($this->input->post('col_date') ? $this->input->post('col_date') : $tb_partnerkey['col_date']); ?>" class="has-datetimepicker form-control" id="col_date" />
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