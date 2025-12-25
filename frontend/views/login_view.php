<?php
extract($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="autocomplete" content="off">
    <title>Login | Leave Request</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../assets/js/password_validation.js" defer></script>
    <script src="../assets/js/login.js" defer></script>
</head>
<body>
    <div class="main-container">
        <!-- Illustration Container -->
        <div class="illustration-container">
            <div class="shape-1"></div>
            <div class="shape-2"></div>
            <img src="../assets/img/login-illustration.svg" alt="Illustration" class="illustration">
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <div class="shape-1"></div>
            <div class="shape-2"></div>
            <div class="login-card">
                <?php if ($message): ?>
                    <div class="message <?php echo htmlspecialchars($messageType); ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <?php if (!$showChangePasswordForm): ?>
                    <h2>Welcome Back</h2>
                    <p>Please log in to your account</p>
                    <form method="POST" action="login.php" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                        
                        <div class="input-group">
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required 
                                autocomplete="off"
                                autocorrect="off"
                                autocapitalize="off"
                                spellcheck="false"
                            >
                            <label for="email">Email</label>
                        </div>

                        <div class="input-group">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                autocomplete="off"
                            >
                            <label for="password">Password</label>
                            <i class="fas fa-eye password-toggle" id="toggle-password" onclick="togglePasswordVisibility('password', this)"></i>
                        </div>

                        <div class="options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="remember_me">
                                <span class="checkmark"></span>
                                Remember Me
                            </label>
                        </div>

                        <button type="submit">Login</button>
                        <div class="links">
                            <a href="forgot_password.php">Forgot Password?</a>
                        </div>
                    </form>
                <?php else: ?>
                    <h2>Change Your Password</h2>
                    <p>You must change your password on first login</p>
                    <form method="POST" action="login.php" autocomplete="off">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                        <input type="hidden" name="change_password" value="1">
                        
                        <div class="input-group">
                            <input 
                                type="password" 
                                id="new_password" 
                                name="new_password" 
                                required
                                autocomplete="new-password"
                            >
                            <label for="new_password">New Password</label>
                            <i class="fas fa-eye password-toggle" id="toggle-new-password"></i>
                        </div>

                        <div class="input-group">
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                required
                                autocomplete="new-password"
                            >
                            <label for="confirm_password">Confirm Password</label>
                            <i class="fas fa-eye password-toggle" id="toggle-confirm-password"></i>
                            <div id="password-mismatch-error" class="error-message" style="display: none;">
                                Passwords do not match
                            </div>
                        </div>

                        <button type="submit" id="change-password-btn" disabled>Change Password</button>
                    </form>

                    <!-- Password Requirements Modal -->
                    <div id="password-modal" class="password-modal">
                        <div class="modal-content">
                            <div class="modal-arrow"></div>
                            <h4>Password Requirements</h4>
                            <ul>
                                <li class="requirement" id="req-length">
                                    <span class="icon"></span>
                                    At least 8 characters
                                </li>
                                <li class="requirement" id="req-uppercase">
                                    <span class="icon"></span>
                                    1 uppercase letter
                                </li>
                                <li class="requirement" id="req-number">
                                    <span class="icon"></span>
                                    1 number
                                </li>
                                <li class="requirement" id="req-special">
                                    <span class="icon"></span>
                                    1 special character
                                </li>
                            </ul>
                            <div class="strength-bar-container">
                                <div id="strength-bar" class="strength-bar"></div>
                                <label id="strength-text" class="strength-text">Weak</label>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>