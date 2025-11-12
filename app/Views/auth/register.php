<?php

// session_start();
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Dapoyumya</title>

    <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/style.css') ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <div class="container" id="signup">
        <h1 class="form-title">Register</h1>

        <?php
        if (isset($errors['user_exist'])) {
            echo '<div class="error-main">
                    <p>' . $errors['user_exist'] . '</p>
                    </div>';
            unset($errors['user_exist']);
        }
        ?>
        <form method="POST" action="<?= base_url('user/account') ?>">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" id="name" placeholder="Name" required>
                <?php
                if (isset($errors['name'])) {
                    echo ' <div class="error">
                    <p>' . $errors['name'] . '</p>
                </div>';
                }
                ?>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <?php
                if (isset($errors['email'])) {
                    echo '<div class="error">
                    <p>' . $errors['email'] . '</p>
                    </div>';
                    unset($errors['email']);
                }
                ?>
            </div>
            <div class="input-group password">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password">
                <i id="eye" class="fa fa-eye"></i>
                <?php
                if (isset($errors['password'])) {
                    echo '<div class="error">
                    <p>' . $errors['password'] . '</p>
                    </div>';
                    unset($errors['password']);
                }
                ?>
            </div>
            <div class="input-group password"> <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <i id="eye_confirm" class="fa fa-eye"></i>
                <?php
                if (isset($errors['confirm_password'])) {
                    echo '<div class="error">
                    <p>' . $errors['confirm_password'] . '</p>
                    </div>';
                    unset($errors['confirm_password']);
                }
                ?>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signup">
        </form>
        <p class="or">
            ----------or--------
        </p>
        <div class="links">
            <p>Already Have Account ?</p>
            <a href="<?= base_url('login') ?>">Login</a>
        </div>
    </div>

    <script>
        // Ambil elemen untuk password pertama
        const togglePassword = document.getElementById('eye');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // ganti tipe input
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // ganti ikon mata
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Ambil elemen untuk password konfirmasi
        const togglePasswordConfirm = document.getElementById('eye_confirm');
        const passwordFieldConfirm = document.getElementById('confirm_password');

        togglePasswordConfirm.addEventListener('click', function() {
            // ganti tipe input
            const type = passwordFieldConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordFieldConfirm.setAttribute('type', type);

            // ganti ikon mata
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
<?php
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
?>