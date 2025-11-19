<?php
require_once __DIR__ . '/bootstrap.php';

// Check if admin is logged in
if (!current_admin()) {
    header('Location: login.php');
    exit();
}



$error = '';
$success = '';

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    try {
        verify_csrf_or_die();
    } catch (Exception $e) {
        $error = 'Invalid security token. Please try again.';
    }
    
    if (empty($error)) {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validate inputs
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'All fields are required.';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match.';
        } elseif (strlen($new_password) < 8) {
            $error = 'New password must be at least 8 characters long.';
        } elseif (!preg_match('/[A-Z]/', $new_password)) {
            $error = 'New password must contain at least one uppercase letter.';
        } elseif (!preg_match('/[a-z]/', $new_password)) {
            $error = 'New password must contain at least one lowercase letter.';
        } elseif (!preg_match('/[0-9]/', $new_password)) {
            $error = 'New password must contain at least one number.';
        } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
            $error = 'New password must contain at least one special character.';
        } else {
            try {
                // Get current admin data
                $stmt = $pdo->prepare("SELECT id, password_hash FROM admins WHERE username = ?");
                $stmt->execute([$_SESSION['admin_user']]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$admin) {
                    $error = 'Admin user not found.';
                } elseif (!password_verify($current_password, $admin['password_hash'])) {
                    $error = 'Current password is incorrect.';
                } else {
                    // Hash the new password
                    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    // Update password in database
                     $stmt = $pdo->prepare("UPDATE admins SET password_hash = ?, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$new_password_hash, $admin['id']]);
                    
                    // Log the password change
                    $logMessage = sprintf(
                        "[%s] Admin password changed for user: %s (ID: %d) from IP: %s\n",
                        date('Y-m-d H:i:s'),
                        $_SESSION['admin_user'],
                        $admin['id'],
                        $_SERVER['REMOTE_ADDR']
                    );
                    file_put_contents(__DIR__ . '/admin_password_changes.log', $logMessage, FILE_APPEND | LOCK_EX);
                    
                    $success = 'Password changed successfully! You will be logged out for security.';
                    
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    // Redirect to logout after 3 seconds
                    header("Refresh: 3; URL=logout.php");
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Admin Password - Portfolio Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        .strength-weak { background-color: #dc3545; }
        .strength-medium { background-color: #ffc107; }
        .strength-strong { background-color: #28a745; }
        .password-requirements {
            font-size: 0.85em;
            color: #6c757d;
            margin-top: 10px;
        }
        .requirement-met { color: #28a745; }
        .requirement-unmet { color: #dc3545; }
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
</head>
<body class="bg-light">
    <?php include '_menu.php'; ?>
    
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0"><i class="fas fa-lock"></i> Change Admin Password</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="passwordForm">
                            <?php echo csrf_field(); ?>
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="fas fa-key"></i> Current Password
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleCurrent">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">
                                    <i class="fas fa-shield-alt"></i> New Password
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleNew">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength" id="passwordStrength"></div>
                                <div class="password-requirements" id="passwordRequirements">
                                    <div><i class="fas fa-times requirement-unmet"></i> At least 8 characters</div>
                                    <div><i class="fas fa-times requirement-unmet"></i> One uppercase letter</div>
                                    <div><i class="fas fa-times requirement-unmet"></i> One lowercase letter</div>
                                    <div><i class="fas fa-times requirement-unmet"></i> One number</div>
                                    <div><i class="fas fa-times requirement-unmet"></i> One special character</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-check-circle"></i> Confirm New Password
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text" id="confirmMatch" style="display: none;">
                                    <i class="fas fa-check text-success"></i> Passwords match
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="generatePassword">
                                    <i class="fas fa-magic"></i> Generate Strong Password
                                </button>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-lock"></i> Change Password
                                </button>
                                <a href="dashboard.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Password Security Tips</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small mb-0">
                            <li>Use a unique password not used elsewhere</li>
                            <li>Include a mix of uppercase, lowercase, numbers, and symbols</li>
                            <li>Avoid common words or personal information</li>
                            <li>Consider using a password manager</li>
                            <li>Change passwords regularly</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const element = document.querySelector(`#passwordRequirements div:nth-child(${Object.keys(requirements).indexOf(req) + 1}) i`);
                if (requirements[req]) {
                    element.classList.remove('fa-times', 'requirement-unmet');
                    element.classList.add('fa-check', 'requirement-met');
                } else {
                    element.classList.remove('fa-check', 'requirement-met');
                    element.classList.add('fa-times', 'requirement-unmet');
                }
            });
            
            // Calculate strength
            Object.values(requirements).forEach(met => {
                if (met) strength++;
            });
            
            // Update strength bar
            const strengthBar = document.getElementById('passwordStrength');
            strengthBar.className = 'password-strength';
            
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength <= 3) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
            
            return strength;
        }
        
        // Password visibility toggles
        document.getElementById('toggleCurrent').addEventListener('click', function() {
            const input = document.getElementById('current_password');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        document.getElementById('toggleNew').addEventListener('click', function() {
            const input = document.getElementById('new_password');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        document.getElementById('toggleConfirm').addEventListener('click', function() {
            const input = document.getElementById('confirm_password');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Password input events
        document.getElementById('new_password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
        
        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchDiv = document.getElementById('confirmMatch');
            
            if (confirmPassword.length > 0) {
                matchDiv.style.display = 'block';
                if (newPassword === confirmPassword) {
                    matchDiv.innerHTML = '<i class="fas fa-check text-success"></i> Passwords match';
                    matchDiv.className = 'form-text text-success';
                } else {
                    matchDiv.innerHTML = '<i class="fas fa-times text-danger"></i> Passwords do not match';
                    matchDiv.className = 'form-text text-danger';
                }
            } else {
                matchDiv.style.display = 'none';
            }
        }
        
        // Generate strong password
        document.getElementById('generatePassword').addEventListener('click', function() {
            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
            let password = '';
            
            // Ensure at least one character from each category
            password += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)];
            password += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)];
            password += '0123456789'[Math.floor(Math.random() * 10)];
            password += '!@#$%^&*()_+-=[]{}|;:,.<>?'[Math.floor(Math.random() * 23)];
            
            // Fill the rest randomly
            for (let i = 4; i < 16; i++) {
                password += chars[Math.floor(Math.random() * chars.length)];
            }
            
            // Shuffle the password
            password = password.split('').sort(() => Math.random() - 0.5).join('');
            
            document.getElementById('new_password').value = password;
            document.getElementById('confirm_password').value = password;
            checkPasswordStrength(password);
            checkPasswordMatch();
            
            // Show the password temporarily
            const newInput = document.getElementById('new_password');
            const confirmInput = document.getElementById('confirm_password');
            const newToggle = document.getElementById('toggleNew');
            const confirmToggle = document.getElementById('toggleConfirm');
            
            newInput.type = 'text';
            confirmInput.type = 'text';
            newToggle.querySelector('i').classList.remove('fa-eye');
            newToggle.querySelector('i').classList.add('fa-eye-slash');
            confirmToggle.querySelector('i').classList.remove('fa-eye');
            confirmToggle.querySelector('i').classList.add('fa-eye-slash');
            
            // Hide after 5 seconds
            setTimeout(() => {
                newInput.type = 'password';
                confirmInput.type = 'password';
                newToggle.querySelector('i').classList.remove('fa-eye-slash');
                newToggle.querySelector('i').classList.add('fa-eye');
                confirmToggle.querySelector('i').classList.remove('fa-eye-slash');
                confirmToggle.querySelector('i').classList.add('fa-eye');
            }, 5000);
        });
    </script>
</body>
</html>