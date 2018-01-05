<div class="row">
    <div class="col-md-12">
      	<div class="box box-info">
            <div class="box-header with-border">
              	<h3 class="box-title">Tb Privilege Edit</h3>
            </div>
			<?php echo form_open('tb_privilege/edit/'.$tb_privilege['p_id']); ?>
			<div class="box-body">
				<div class="row clearfix">
					<div class="col-md-6">
						<label for="p_service" class="control-label">Tb Service</label>
						<div class="form-group">
							<select name="p_service" class="form-control">
								<option value="">select tb_service</option>
								<?php 
								foreach($all_tb_service as $tb_service)
								{
									$selected = ($tb_service['col_id'] == $tb_privilege['p_service']) ? ' selected="selected"' : "";

									echo '<option value="'.$tb_service['col_id'].'" '.$selected.'>'.$tb_service['col_name'].'</option>';
								} 
								?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<label for="p_partnerid" class="control-label">P Partnerid</label>
						<div class="form-group">
							<input type="text" name="p_partnerid" value="<?php echo ($this->input->post('p_partnerid') ? $this->input->post('p_partnerid') : $tb_privilege['p_partnerid']); ?>" class="form-control" id="p_partnerid" />
						</div>
					</div>
					<div class="col-md-6">
						<label for="p_status" class="control-label">P Status</label>
						<div class="form-group">
							<input type="text" name="p_status" value="<?php echo ($this->input->post('p_status') ? $this->input->post('p_status') : $tb_privilege['p_status']); ?>" class="form-control" id="p_status" />
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