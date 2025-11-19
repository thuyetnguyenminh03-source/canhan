<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Security log file paths
$passwordLogFile = __DIR__ . '/admin_password_changes.log';
$securityLogFile = __DIR__ . '/admin_security.log';

// Function to read log files safely
function readLogFile($filePath, $limit = 50) {
    if (!file_exists($filePath) || !is_readable($filePath)) {
        return [];
    }
    
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return [];
    }
    
    // Get last $limit lines
    $lines = array_slice($lines, -$limit);
    return array_reverse($lines); // Show newest first
}

// Function to get login attempts from database
function getLoginAttempts($pdo, $limit = 20) {
    try {
        $stmt = $pdo->prepare("
            SELECT al.*, au.username 
            FROM admin_login_attempts al
            LEFT JOIN admins au ON al.admin_id = au.id
            ORDER BY al.attempt_time DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Function to get security statistics
function getSecurityStats($pdo) {
    try {
        $stats = [];
        
        // Total login attempts today
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM admin_login_attempts 
            WHERE DATE(attempt_time) = CURDATE()
        ");
        $stmt->execute();
        $stats['today_attempts'] = $stmt->fetchColumn();
        
        // Failed login attempts today
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM admin_login_attempts 
            WHERE DATE(attempt_time) = CURDATE() AND success = 0
        ");
        $stmt->execute();
        $stats['today_failed'] = $stmt->fetchColumn();
        
        // Unique IPs with failed attempts today
        $stmt = $pdo->prepare("
            SELECT COUNT(DISTINCT ip_address) as count 
            FROM admin_login_attempts 
            WHERE DATE(attempt_time) = CURDATE() AND success = 0
        ");
        $stmt->execute();
        $stats['today_failed_ips'] = $stmt->fetchColumn();
        
        // Total admin users
        $stats['total_admins'] = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
        
        // Admins with recent password changes (last 30 days)
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM admins 
            WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $stmt->execute();
        $stats['recent_password_changes'] = $stmt->fetchColumn();
        
        return $stats;
    } catch (PDOException $e) {
        return [
            'today_attempts' => 0,
            'today_failed' => 0,
            'today_failed_ips' => 0,
            'total_admins' => 0,
            'recent_password_changes' => 0
        ];
    }
}

// Function to get blocked IPs
function getBlockedIPs($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT ip_address, COUNT(*) as attempts, MAX(attempt_time) as last_attempt
            FROM admin_login_attempts 
            WHERE success = 0 AND attempt_time >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
            GROUP BY ip_address
            HAVING attempts >= 5
            ORDER BY attempts DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Get data for dashboard
$pdo = getDBConnection();
$loginAttempts = getLoginAttempts($pdo, 20);
$securityStats = getSecurityStats($pdo);
$blockedIPs = getBlockedIPs($pdo);
$passwordLogs = readLogFile($passwordLogFile, 20);

// Handle clear logs action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_logs'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid security token.';
    } else {
        $logType = $_POST['log_type'] ?? '';
        
        if ($logType === 'password') {
            file_put_contents($passwordLogFile, '');
            $success = 'Password change logs cleared successfully.';
        } elseif ($logType === 'security') {
            file_put_contents($securityLogFile, '');
            $success = 'Security logs cleared successfully.';
        }
    }
}

// Generate new CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Dashboard - Portfolio Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .security-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .security-card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .warning-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .success-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        .log-entry {
            font-family: 'Courier New', monospace;
            font-size: 0.85em;
            padding: 8px;
            margin-bottom: 5px;
            background: #f8f9fa;
            border-left: 3px solid #007bff;
            border-radius: 3px;
        }
        .log-entry.failed {
            border-left-color: #dc3545;
            background: #fff5f5;
        }
        .log-entry.success {
            border-left-color: #28a745;
            background: #f8fff8;
        }
        .blocked-ip {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .table-dark {
            background: #2c3e50;
            color: white;
        }
        .badge {
            font-size: 0.8em;
        }
    </style>
</head>
<body class="bg-light">
    <?php include '_menu.php'; ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">
                    <i class="fas fa-shield-alt"></i> Security Dashboard
                </h2>
            </div>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Security Statistics -->
        <div class="row mb-4">
            <div class="col-md-2 mb-3">
                <div class="card security-card stat-card">
                    <div class="card-body text-center">
                        <i class="fas fa-sign-in-alt fa-2x mb-2"></i>
                        <h4><?php echo $securityStats['today_attempts']; ?></h4>
                        <p class="mb-0">Login Attempts Today</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card security-card warning-card">
                    <div class="card-body text-center">
                        <i class="fas fa-times-circle fa-2x mb-2"></i>
                        <h4><?php echo $securityStats['today_failed']; ?></h4>
                        <p class="mb-0">Failed Attempts Today</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card security-card">
                    <div class="card-body text-center" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                        <i class="fas fa-ban fa-2x mb-2"></i>
                        <h4><?php echo $securityStats['today_failed_ips']; ?></h4>
                        <p class="mb-0">Suspicious IPs Today</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card security-card success-card">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h4><?php echo $securityStats['total_admins']; ?></h4>
                        <p class="mb-0">Total Admin Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card security-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <div class="card-body text-center">
                        <i class="fas fa-key fa-2x mb-2"></i>
                        <h4><?php echo $securityStats['recent_password_changes']; ?></h4>
                        <p class="mb-0">Password Changes (30d)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card security-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt fa-2x mb-2"></i>
                        <h4><?php echo count($blockedIPs); ?></h4>
                        <p class="mb-0">Blocked IPs</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Recent Login Attempts -->
            <div class="col-md-6 mb-4">
                <div class="card security-card h-100">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-history"></i> Recent Login Attempts
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($loginAttempts)): ?>
                            <p class="text-muted text-center">No recent login attempts found.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Time</th>
                                            <th>User</th>
                                            <th>IP Address</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($loginAttempts as $attempt): ?>
                                            <tr>
                                                <td><small><?php echo date('m/d H:i', strtotime($attempt['attempt_time'])); ?></small></td>
                                                <td><small><?php echo htmlspecialchars($attempt['username'] ?: 'Unknown'); ?></small></td>
                                                <td><small><?php echo htmlspecialchars($attempt['ip_address']); ?></small></td>
                                                <td>
                                                    <?php if ($attempt['success']): ?>
                                                        <span class="badge bg-success">Success</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Failed</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Blocked IPs -->
            <div class="col-md-6 mb-4">
                <div class="card security-card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-ban"></i> Potentially Blocked IPs
                            <small class="float-end">5+ failed attempts in 1 hour</small>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($blockedIPs)): ?>
                            <p class="text-muted text-center">No blocked IPs detected.</p>
                        <?php else: ?>
                            <?php foreach ($blockedIPs as $blocked): ?>
                                <div class="blocked-ip">
                                    <strong><?php echo htmlspecialchars($blocked['ip_address']); ?></strong>
                                    <span class="float-end badge bg-danger"><?php echo $blocked['attempts']; ?> attempts</span>
                                    <br>
                                    <small class="text-muted">Last attempt: <?php echo date('Y-m-d H:i', strtotime($blocked['last_attempt'])); ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Password Change Logs -->
            <div class="col-md-6 mb-4">
                <div class="card security-card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-key"></i> Recent Password Changes
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($passwordLogs)): ?>
                            <p class="text-muted text-center">No recent password changes.</p>
                        <?php else: ?>
                            <?php foreach ($passwordLogs as $log): ?>
                                <?php 
                                $isFailed = strpos($log, 'FAILED') !== false;
                                $logClass = $isFailed ? 'failed' : 'success';
                                ?>
                                <div class="log-entry <?php echo $logClass; ?>">
                                    <?php echo htmlspecialchars($log); ?>
                                </div>
                            <?php endforeach; ?>
                            
                            <form method="POST" class="mt-3">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <input type="hidden" name="log_type" value="password">
                                <button type="submit" name="clear_logs" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure you want to clear password change logs?')">
                                    <i class="fas fa-trash"></i> Clear Password Logs
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Security Actions -->
            <div class="col-md-6 mb-4">
                <div class="card security-card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tools"></i> Security Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="change-admin-password.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-lock text-primary"></i> Change Admin Password
                                <span class="float-end"><i class="fas fa-arrow-right"></i></span>
                            </a>
                            <a href="check-admin-status.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-users text-success"></i> Check Admin Status
                                <span class="float-end"><i class="fas fa-arrow-right"></i></span>
                            </a>
                            <a href="login.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-sign-in-alt text-warning"></i> Test Login Security
                                <span class="float-end"><i class="fas fa-arrow-right"></i></span>
                            </a>
                            <div class="list-group-item">
                                <i class="fas fa-shield-alt text-info"></i> Security Level: 
                                <span class="badge bg-success">Enhanced</span>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="text-muted mb-3">Quick Security Check</h6>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                    <div class="small">CSRF Protection</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                    <div class="small">Password Hashing</div>
                                </div>
                            </div>
                            <div class="col-6 mt-2">
                                <div class="border rounded p-2">
                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                    <div class="small">Login Logging</div>
                                </div>
                            </div>
                            <div class="col-6 mt-2">
                                <div class="border rounded p-2">
                                    <i class="fas fa-check-circle text-success fa-2x"></i>
                                    <div class="small">IP Monitoring</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
        
        // Show last refresh time
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            console.log('Security Dashboard refreshed at: ' + now.toLocaleTimeString());
        });
    </script>
</body>
</html>