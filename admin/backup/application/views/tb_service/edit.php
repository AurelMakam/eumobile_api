<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Tb Service Edit</h3>
            </div>
			<?php echo form_open('tb_service/edit/'.$tb_service['col_id']); ?>
			<div class="box-body">
				<div class="row clearfix">
					<div class="col-md-6">
						<label for="col_name" class="control-label">Col Name</label>
						<div class="form-group">
							<input type="text" name="col_name" value="<?php echo ($this->input->post('col_name') ? $this->input->post('col_name') : $tb_service['col_name']); ?>" class="form-control" id="col_name" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_description" class="control-label">Col Description</label>
						<div class="form-group">
							<input type="text" name="col_description" value="<?php echo ($this->input->post('col_description') ? $this->input->post('col_description') : $tb_service['col_description']); ?>" class="form-control" id="col_description" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_status" class="control-label">Col Status</label>
						<div class="form-group">
							<input type="text" name="col_status" value="<?php echo ($this->input->post('col_status') ? $this->input->post('col_status') : $tb_service['col_status']); ?>" class="form-control" id="col_status" />
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