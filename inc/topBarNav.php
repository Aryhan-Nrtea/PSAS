
<style>
  .user-img{
        position: absolute;
        height: 27px;
        width: 27px;
        object-fit: cover;
        left: -7%;
        top: -12%;
  }
  .btn-rounded{
        border-radius: 50px;
  }
  #login-nav{
        position:fixed !important;
        top: 0 !important;
        z-index: 1037;
        padding: 1em 1.5em !important;
  }
  #top-Nav{
        top: 4em;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000; /* Ensures it's above other content */
  }
  .text-sm .layout-navbar-fixed .wrapper .main-header ~ .content-wrapper, .layout-navbar-fixed .wrapper .main-header.text-sm ~ .content-wrapper {
        margin-top: calc(3.6) !important;
        padding-top: calc(5em) !important;
  }
 
  .search-container {
    display: flex;
    align-items: center;
    width: 300px; 
    position: relative;
    margin-left: -20%;
  }

  .search-container .fa-search {
    position: absolute;
    margin-left: 10px;
    color: #aaa; 
    font-size: 20px; 
  }

  .search-container input[type="search"] {
    flex: 1; 
    padding-left: 40px; 
    border-radius: 0; 
  }
  #top-Nav {
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  }
  .nav-item {
    text-align: center;
  }



  

     
      </style>
      
      <nav class="main-header navbar navbar-expand navbar-light border-0 navbar-light text-sm fixed-top" id='top-Nav' style="margin-top: -0%; fixed-top">

        
        <div class="container" >
          <a href="./" class="navbar-brand">
            <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Site Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span><?= $_settings->info('short_name') ?></span>
          </a>
          

          <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav" >
              <li class="nav-item">
                <a href="./" class="nav-link <?= isset($page) && $page =='home' ? "active" : "" ?>">Home</a>
              </li>
              <li class="nav-item">
                <a href="./?page=projects" class="nav-link <?= isset($page) && $page =='projects' ? "active" : "" ?>">Program Studies</a>
              </li>
              <li class="nav-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle  <?= isset($page) && $page =='projects_per_department' ? "active" : "" ?>">Department</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                  <?php 
                    $departments = $conn->query("SELECT * FROM department_list where status = 1 order by `name` asc");
                    $dI =  $departments->num_rows;
                    while($row = $departments->fetch_assoc()):
                      $dI--;
                  ?>
                  <li>
                    <a href="./?page=projects_per_department&id=<?= $row['id'] ?>" class="dropdown-item"><?= ucwords($row['name']) ?></a>
                    <?php if($dI != 0): ?>
                    <li class="dropdown-divider"></li>
                    <?php endif; ?>
                  </li>
                  <?php endwhile; ?>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle  <?= isset($page) && $page =='projects_per_curriculum' ? "active" : "" ?>">Program</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                  <?php 
                    $curriculums = $conn->query("SELECT * FROM curriculum_list where status = 1 order by `name` asc");
                    $cI =  $curriculums->num_rows;
                    while($row = $curriculums->fetch_assoc()):
                      $cI--;
                  ?>
                  <li>
                    <a href="./?page=projects_per_curriculum&id=<?= $row['id'] ?>" class="dropdown-item"><?= ucwords($row['name']) ?></a>
                    <?php if($cI != 0): ?>
                    <li class="dropdown-divider"></li>
                    <?php endif; ?>
                  </li>
                  <?php endwhile; ?>
                </ul>
              </li>
              <li class="nav-item">
                <a href="./?page=about" class="nav-link <?= isset($page) && $page =='about' ? "active" : "" ?>">About Us</a>
              </li>
            </ul>

            
          </div>
          <!-- Right navbar links -->
          <div class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                   <div class="search-container">
                   <i class="fa fa-search"></i>
                    <input type="search" id="search-input" class="form-control rounded-0" required placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>">
                  </div>
          </div>
        </div>
        <ul class="navbar-nav" >
        <?php if($_settings->userdata('id') > 0): ?>
              <li class="nav-item">
                <a href="./?page=profile" class="nav-link <?= isset($page) && $page =='profile' ? "active" : "" ?>">Profile</a>
              </li>
              <li class="nav-item">
                <a href="./?page=submit-archive" class="nav-link <?= isset($page) && $page =='submit-archive' ? "active" : "" ?>">Archive</a>
              </li>
              <?php endif; ?>
            </ul>

        <div>
            <?php if($_settings->userdata('id') > 0): ?>
              <span class="mx-2"><img src="<?= validate_image($_settings->userdata('avatar')) ?>" alt="User Avatar" id="student-img-avatar"></span>
              <span class="mx-2 text-dark me-2" style="font-weight: bold;"><?= !empty($_settings->userdata('firstname')) ? $_settings->userdata('firstname') : 'Guest' ?>
              </span>
              <span class="mx-1"><a href="<?= base_url.'classes/Login.php?f=student_logout' ?>"><i class="fa fa-power-off"></i></a></span>
            <?php else: ?>
              
              <a href="./register.php" class="mx-2 text-dark me-2" style="font-weight: bold;">Register</a>
              <a href="./login.php" class="mx-2 text-dark me-2" style="font-weight: bold;">Student Portal</a>
              <a href="./admin" class="mx-2 text-dark me-2" style="font-weight: bold;">Admin Portal</a>

            <?php endif; ?>
            
            
          </div>
      </nav>
      <!-- /.navbar -->
      <script>
        $(function(){
            $('#search-form').submit(function(e){
                e.preventDefault();
                if ($('[name="q"]').val().length == 0) {
                    location.href = './';
                } else {
                    location.href = './?' + $(this).serialize();
                }
            })
            $('#search-input').keydown(function(e){
                if (e.which == 13) {
                    location.href = "./?page=projects&q=" + encodeURI($(this).val());
                }
            });
        });
      </script>