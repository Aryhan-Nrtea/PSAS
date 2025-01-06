<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en">
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
        margin: auto;
        text-align: center;
    }
    #logo-img {
        height: 100px;
        width: 100px;
        border-radius: 50%;
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
        background-color: #a00000;
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
</style>

<?php if ($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success');
    </script>
<?php endif; ?>

<div id="login">
    <div class="custom-card">
        <center>
        <img src="<?= validate_image($_settings->info('logo')) ?>" alt="Logo" id="logo-img">
        <h4><?php echo $_settings->info('name') ?></h4>
        </center>
        <hr>
        <h4><center><b>Admin Portal</b></center></h4>
        <div class="card-body">
        <form id="login-frm" action="" method="post">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                <div class="input-group-append">
                    <div class="input-group-text"><i class="fas fa-user"></i></div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                <div class="input-group-append">
                    <div class="input-group-text toggle-password" id="toggle-password" style="cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </div>
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

    $(document).ready(function() {
        end_loader();
    });
</script>
</body>
</html>
