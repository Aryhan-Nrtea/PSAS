<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
<body class="hold-transition ">
  <script>
    start_loader()
  </script>
  <style>
    html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        font-family: Arial, sans-serif;
        overflow: hidden;
    }
    body {
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(<?= validate_image($_settings->info("cover")) ?>);
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
    }
    #login {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        margin-top: -3%;
       
        
    }
    .custom-card {
        max-width: 700px; /* Adjust as needed */
        max-height: 100vh;
        width: 100%;
        margin: auto;
        background-color: rgba(255, 255, 255, 0.9); /* Light background for contrast */
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        margin-top: 7%;
        text-align: center;
    }
    #logo-img {
        height: 100px;
        width: 100px;
        border-radius: 50%;
        display: block;
        margin: 20px auto;
    }
    .btn-maroon {
        background-color: #800000;
        width: 100%;
        border: none;
        border-radius: 5px;
        padding: 10px;
    }
    .btn-maroon:hover {
        background-color: #a00000; /* Lighter shade on hover */
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
    .toggle-password {
    cursor: pointer;
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    
    }

    .form-control {
        position: relative; /* Required for absolute positioning of icon */
    }

</style>

 
<div id="login">
    <div class="custom-card">
        <center>
            <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo" id="logo-img">
            <h4 class="text-center"><?= $_settings->info('name') ?></h4>
        </center>
        <hr>
        <h4><center><b>Registration Form</b></center></h4>
        <div class="card-body">

        
                <form action="" id="registration-form" style="overflow-y: auto; overflow-x: hidden; max-height: 300px; display: block">
                    <input type="hidden" name="id">

                <div class="mb-3 row">
                    <label for="fname" class="col-lg-2 col-form-label text-navy text-left">Firstname</label>
                    <div class="col-lg-8">
                        <input type="text" name="firstname" id="firstname" placeholder="Firstname" class="form-control" required autofocus>
                    </div>
                </div>


                <div class="mb-3 row">
                    <label for="middlename" class="col-lg-2 col-form-label text-navy text-left">Middlename</label>
                    <div class="col-lg-8">
                        <input type="text" name="middlename" id="middlename" placeholder="Middlename (optional)" class="form-control">
                    </div>
                </div>


                <div class="mb-3 row">
                    <label for="lastname" class="col-lg-2 col-form-label text-navy text-left">Lastname</label>
                    <div class="col-lg-8">
                        <input type="text" name="lastname" id="lastname" placeholder="Lastname" class="form-control" required>
                    </div>
                </div>


                    <div class="mb-3 row">
                        <div class="col-auto">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="genderMale" name="gender" value="Male" required checked>
                                <label for="genderMale" class="custom-control-label">Male</label>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="genderFemale" name="gender" value="Female">
                                <label for="genderFemale" class="custom-control-label">Female</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                <label for="department_id" class="col-lg-2 col-form-label text-navy text-left">Department</label>
                <div class="col-lg-8">
                    <select name="department_id" id="department_id" class="form-control select2" data-placeholder="Select Department Here" required>
                        <option value="" disabled selected>Select Department</option>
                        <?php 
                        $department = $conn->query("SELECT * FROM `department_list` WHERE status = 1 ORDER BY `name` ASC");
                        while ($row = $department->fetch_assoc()):
                        ?>
                            <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="curriculum_id" class="col-lg-2 col-form-label text-navy text-left">Program</label>
                <div class="col-lg-8">
                    <select name="curriculum_id" id="curriculum_id" class="form-control select2" data-placeholder="Select Program Here" required>
                        <option value="" disabled selected>Select Department First</option>
                        <?php 
                        $curriculum = $conn->query("SELECT * FROM `curriculum_list` WHERE status = 1 ORDER BY `name` ASC");
                        while ($row = $curriculum->fetch_assoc()):
                            $row['name'] = ucwords($row['name']);
                            $cur_arr[$row['department_id']][] = $row;
                        ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="email" class="col-lg-3 col-form-label text-navy text-left">Institutional Email</label>
                <div class="col-lg-8">
                    <input type="email" name="email" id="email" placeholder="Institutional email" class="form-control" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password" class="col-lg-2 col-form-label text-navy text-left">Password</label>
                <div class="col-lg-8">
                    <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
                    <span class="toggle-password" id="toggle-password"><i class="fa fa-eye"></i></span>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="cpassword" class="col-lg-3 col-form-label text-navy text-left">Confirm Password</label>
                <div class="col-lg-8">
                    <input type="password" id="cpassword" placeholder="Confirm Password" class="form-control" required>
                    <span class="toggle-password" id="toggle-cpassword"><i class="fa fa-eye"></i></span>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="img" class="col-lg-3 col-form-label text-navy text-left">Formal Picture</label>
                <div class="col-lg-8">
                    <input type="file" id="img" name="img" class="form-control form-control-border" accept="image/png,image/jpeg" onchange="displayImg(this,$(this))">
                </div>
            </div>

            <div class="text-center">
                <img src="<?= validate_image(isset($avatar) ? $avatar : "") ?>" alt="My Avatar" id="cimg" class="img-fluid student-img" style="max-width: 50%">
            </div>

                                

                                <hr>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-maroon"  style="color: white">Register</button>
                                </div>

                                <div class="form-group text-center">
                                    <a href="<?php echo base_url ?>" style="color: black;">Back to Homepage</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url ?>plugins/select2/js/select2.full.min.js"></script>

<script>

function displayImg(input,_this) {
   

	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
                
	        }

	        reader.readAsDataURL(input.files[0]);
           
	    }else{
            $('#cimg').attr('src', "<?= validate_image(isset($avatar) ? $avatar : "") ?>");
            
        }
	}
    
    document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('toggle-password');
            const passwordField = document.getElementById('password');
            const toggleCPassword = document.getElementById('toggle-cpassword');
            const cpasswordField = document.getElementById('cpassword');

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute using a ternary operator
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;
                // Toggle the icon based on type
                togglePassword.querySelector('i').classList.toggle('fa-eye', type === 'password');
                togglePassword.querySelector('i').classList.toggle('fa-eye-slash', type === 'text');
            });

            toggleCPassword.addEventListener('click', function() {
                // Toggle the type attribute using a ternary operator
                const type = cpasswordField.type === 'password' ? 'text' : 'password';
                cpasswordField.type = type;
                // Toggle the icon based on type
                toggleCPassword.querySelector('i').classList.toggle('fa-eye', type === 'password');
                toggleCPassword.querySelector('i').classList.toggle('fa-eye-slash', type === 'text');
            });
        });
    var cur_arr = $.parseJSON('<?= json_encode($cur_arr) ?>');

  $(document).ready(function(){
    end_loader();
    $('.select2').select2({
        width:"100%"
    })
    $('#department_id').change(function(){
        var did = $(this).val()
        $('#curriculum_id').html("")
        if(!!cur_arr[did]){
            Object.keys(cur_arr[did]).map(k=>{
                var opt = $("<option>")
                    opt.attr('value',cur_arr[did][k].id)
                    opt.text(cur_arr[did][k].name)
                $('#curriculum_id').append(opt)
            })
        }
        $('#curriculum_id').trigger("change")
    })

    // Registration Form Submit
    $('#registration-form').submit(function(e){
        e.preventDefault()
        var _this = $(this)
            $(".pop-msg").remove()
            $('#password, #cpassword').removeClass("is-invalid")
        var el = $("<div>")
            el.addClass("alert pop-msg my-2")
            el.hide()
        if($("#password").val() != $("#cpassword").val()){
            el.addClass("alert-danger")
            el.text("Password does not match.")
            $('#password, #cpassword').addClass("is-invalid")
            $('#cpassword').after(el)
            el.show('slow')
            return false;
        }
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Users.php?f=save_student",
            method:'POST',
            data: new FormData($(this)[0]),
            cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                dataType:'json',
            error:err=>{
                console.log(err)
                el.text("An error occured while saving the data")
                el.addClass("alert-danger")
                _this.prepend(el)
                el.show('slow')
                end_loader()
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.href= "./login.php"
                }else if(!!resp.msg){
                    el.text(resp.msg)
                    el.addClass("alert-danger")
                    _this.prepend(el)
                    el.show('show')
                }else{
                    el.text("An error occured while saving the data")
                    el.addClass("alert-danger")
                    _this.prepend(el)
                    el.show('show')
                }
                end_loader();
                $('html, body').animate({scrollTop: 0},'fast')
            }
        })
    })
  })
</script>
</body>
</html>