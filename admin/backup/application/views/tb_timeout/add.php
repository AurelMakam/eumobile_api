<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Tb Timeout Add</h3>
            </div>
            <?php echo form_open('tb_timeout/add'); ?>
          	<div class="box-body">
          		<div class="row clearfix">
					<div class="col-md-6">
						<div class="form-group">
							<input type="checkbox" name="col_use_timeout" value="1"  id="col_use_timeout" />
							<label for="col_use_timeout" class="control-label">Col Use Timeout</label>
						</div>
					</div>
					<div class="col-md-6">
						<label for="col_timeout" class="control-label">Col Timeout</label>
						<div class="form-group">
							<input type="text" name="col_timeout" value="<?php echo $this->input->post('col_timeout'); ?>" class="form-control" id="col_timeout" />
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