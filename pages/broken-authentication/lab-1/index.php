<?php
$page_title = "Broken Authentication Lab 1 - Weak Password Policy";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$max_attempts = 3;
$lockout_duration = 10;
$message = '';
$attempts = $_SESSION['login_attempts'] ?? 0;
$first_attempt_time = $_SESSION['first_attempt_time'] ?? 0;
$lockout_start = $_SESSION['lockout_start'] ?? 0;
$current_time = time();

$alert_class = "alert-danger";
$is_login = false;
$is_locked = false;
$lockout_time = 0;
$remaining_time = 0;

if ($lockout_start > 0) {
    $time_since_lockout = $current_time - $lockout_start;
    
    if ($time_since_lockout < $lockout_duration) {
        $is_locked = true;
        $remaining_time = $lockout_duration - $time_since_lockout;
        $message = "Account Locked" . gmdate("i:s", $remaining_time) . " (mm:ss)";
    } else {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['first_attempt_time'] = 0;
        $_SESSION['lockout_start'] = 0;
        $attempts = 0;
        $lockout_start = 0;
    }
}

if ($first_attempt_time > 0 && ($current_time - $first_attempt_time) > $attempt_window) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['first_attempt_time'] = 0;
    $attempts = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$is_locked) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '" . sha1($password) . "'";

    try {
        $result = $pdo->query($query);
        if ($result && $result->rowCount() > 0) {
            $user = $result->fetch(PDO::FETCH_ASSOC);
            $message = "Login successful! Weak password detected: " . htmlspecialchars($password);
            $alert_class = "alert-info";
            $is_login = true;
        } else {
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $attempts = $_SESSION['login_attempts'];

            $remaining_attempts = $max_attempts - $attempts;

            if ($attempts >= $max_attempts) {
                $is_locked = true;
                $_SESSION['lockout_start'] = $current_time;
            } 

            $message = "Invalid credentials. Attempt #" . $_SESSION['login_attempts'];
        }
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0">
            <?php include '../../../template/nav.php'; ?>
        </div>
        
        <div class="col-md-9 col-lg-10 mt-60">
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="../">Broken Authentication</a></li>
                                <li class="breadcrumb-item active">Lab 1</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 1: Weak Password Policy</h1>
                            <span class="lab-difficulty difficulty-easy">Easy</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Login System</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($message): ?>
                                    <div class="alert <?php echo $alert_class;?>" role="alert">
                                        <?php echo $message; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if(!$is_login): ?>
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">email:</label>
                                        <input type="text" class="form-control" id="email" name="email" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password:</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </form>
                                <?php endif; ?>
                                <div class="mt-3">
                                    <small class="text-muted">Login attempts: <?php echo $attempts; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">💡 Lab Objectives</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>✅ Exploit weak passwords</li>
                                    <li>✅ Perform brute force attack</li>
                                    <li>✅ No rate limiting bypass</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Common Passwords</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="passwordsAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#weak">
                                                Weak Passwords
                                            </button>
                                        </h2>
                                        <div id="weak" class="accordion-collapse collapse" data-bs-parent="#passwordsAccordion">
                                            <div class="accordion-body">
                                                Try: 123456, password, admin, test, 12345
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../../template/footer.php'; ?>