<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" style="height: auto;">
<?php require_once('inc/header.php') ?>
<body class="hold-transition">

<script>
    start_loader();
</script>
<style>
    html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        font-family: Arial, sans-serif;
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
    }
    .custom-card {
        max-width: 500px; /* Adjust as needed */
        width: 100%;
        margin: 20px;
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
        color: white;
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
</style>

<?php if($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>",'success');
    </script>
<?php endif; ?> 

<div id="login">
    <div class="custom-card">
        <center>
            <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo" id="logo-img">
            <h4 class="text-center"><?= $_settings->info('name') ?></h4>
        </center>
        <hr>
        <h4><center><b>Student Portal</b></center></h4>
        <div class="card-body">
            <form id="slogin-form" action="" method="post">
                <div class="input-group mb-3">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                    <div class="input-group-append">
                        <span class="input-group-text toggle-password" id="toggle-password"><i class="fa fa-eye"></i></span>
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn-maroon">Login</button>
                </div>
                <div class="form-group text-center">
                    <a href="<?php echo base_url ?>" style="color: black;">Back to Homepage</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('toggle-password');
        const passwordField = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            togglePassword.querySelector('i').classList.toggle('fa-eye', type === 'password');
            togglePassword.querySelector('i').classList.toggle('fa-eye-slash', type === 'text');
        });
    });

    $(document).ready(function(){
        end_loader();
        $('#slogin-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $(".pop-msg").remove();
            var el = $("<div>").addClass("alert pop-msg my-2").hide();
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Login.php?f=student_login",
                method:'POST',
                data:_this.serialize(),
                dataType:'json',
                error: err => {
                    console.log(err);
                    el.text("An error occurred").addClass("alert-danger").prependTo(_this).show('slow');
                    end_loader();
                },
                success: resp => {
                    if(resp.status === 'success'){
                        location.href = "./";
                    } else {
                        el.text(resp.msg || "An error occurred").addClass("alert-danger").prependTo(_this).show('slow');
                    }
                    end_loader();
                    $('html, body').animate({scrollTop: 0}, 'fast');
                }
            });
        });
    });
</script>
</body>
</html>
