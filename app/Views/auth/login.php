<?php
// session_start();
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']); // Hapus error setelah ditampilkan
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Dapoyumya</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/style.css') ?>">

</head>

<body>

    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>

        <?php if (isset($errors['login'])): ?>
            <div class="error-main">
                <p><?= htmlspecialchars($errors['login']) ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('user/account') ?>">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="error">
                        <p><?= htmlspecialchars($errors['email']) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="input-group password">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i id="togglePassword" class="fa fa-eye"></i>
                <?php if (isset($errors['password'])): ?>
                    <div class="error">
                        <p><?= htmlspecialchars($errors['password']) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <p class="recover"><a href="#">Recover Password</a></p>

            <input type="submit" class="btn" value="Sign In" name="signin">
        </form>



        <div class="links">
            <p>Don't have account yet?</p>
            <a href="<?= base_url('register') ?>">Sign Up</a>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle icon eye / eye-slash
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>