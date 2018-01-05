<?php 
	$this->load->view('include/header');			
	if(isset($_view) && $_view){
		?>
<!-- page content -->
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper settingPage">
    <!-- Main content -->
    <section class="content">
      <?php if($this->session->flashdata("message")){?>
        <div class="alert alert-info alert-dismissible"  role="alert">      
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <?php echo $this->session->flashdata("message")?>
        </div>
      <?php } ?>
      <!-- Default box -->
      <div class="box box-success" >
        <div class="box-header with-border">
          <h3 class="box-title">Settings </h3>
        </div>
        <div class="box-body" style="background: rgb(249, 250, 252);">
<?php 
		$this->load->view($_view); ?>
		</div>
	  </div>
	 </section>
  </div>
<?php
	}
	$this->load->view('include/footer');
	//$this->load->view('include/script');		
?>                    