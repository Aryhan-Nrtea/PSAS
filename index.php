<?php require_once('./config.php'); ?>
 <!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<style>
  
 #header {
    height: 60vh; /* Full viewport height */
    width: 100%; /* Full width */
    position: relative; /* Relative positioning */
    margin: 0 auto; /* Center the header */
    display: flex; /* Use flexbox for alignment */
    flex-direction: column; /* Stack children vertically */
    justify-content: center; /* Center items vertically */
    align-items: center; /* Center items horizontally */
    padding-top: 10rem; /* Maintain the padding you have */
    background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white for contrast */
    background-image: 
            linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), /* Overlay color */
            url(<?= validate_image($_settings->info("cover")) ?>); /* Background image */
    background-size: cover; /* Cover the entire area */
    background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Prevent the image from repeating */
       
}
    h1 {
        color: white;
        font-size: 3rem;
        margin-top: -3%;
        text-align: center;
    }
    .btn {
        background-color: #d30707;
        color: white;
        margin-top: 6%;
        border-radius: 50px; /* Rounded pill style */
    }
    .image-container {
    width: 90%; /* Set a fixed width */
    margin: 0 auto; /* Center the container */
    margin-top: -3%;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center; /* Center the image */
    margin-bottom: 3%;
    
}
    


  #top-Nav a.nav-link.active {
      color: #001f3f;
      font-weight: 900;
      position: relative;
  }
  #top-Nav a.nav-link.active:before {
    content: "";
    position: absolute;
    border-bottom: 2px solid #001f3f;
    width: 33.33%;
    left: 33.33%;
    bottom: 0;
  }
</style>
<?php require_once('inc/header.php') ?>
  <body class="layout-top-nav layout-fixed layout-navbar-fixed" style="height: auto;">
    <div class="wrapper">
     <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home';  ?>
     <?php require_once('inc/topBarNav.php') ?>
     <?php if($_settings->chk_flashdata('success')): ?>
      <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
      </script>
      <?php endif;?>    
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper pt-5 bg-white">
        <?php if($page == "home" || $page == "about_us"): ?>
          <div class="image-container">
          <div id="header" class="shadow mb-4">
              <div class="d-flex justify-content-center h-100 w-100 align-items-center flex-column px-3" >
                  <h1 class="w-100 text-center" style="color:white; font-size: 3rem; margin-top: -10%;" ><?php echo $_settings->info('name') ?></h1>
                  <a href="./?page=projects" class="btn btn-lg btn-light rounded-pill w-25" style="background-color: #d30707; color: white; margin-top: 5%;" id="enrollment"><b>Explore Program Studies</b></a>
              </div>
          </div>
        </div>
        <?php endif; ?>
          
                  
        <!-- Main content -->
        <section class="content">
          <div class="container">
            <?php 
              if(!file_exists($page.".php") && !is_dir($page)){
                  include '404.html';
              }else{
                if(is_dir($page))
                  include $page.'/index.php';
                else
                  include $page.'.php';

              }
            ?>
          </div>
        </section>
        <!-- /.content -->
  <div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-right"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
  </div>
      </div>
      <!-- /.content-wrapper -->
      <?php require_once('inc/footer.php') ?>
  </body>
</html>
