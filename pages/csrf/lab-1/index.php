<?php
$page_title = "CSRF Lab 1 - Login Form CSRF";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$message = '';
$is_login = false;

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!$csrf_token || $csrf_token !== $_SESSION['csrf_token']) {
        $message = 'CSRF token is invalid or missing.';
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // VULNERABLE CODE - Do not use in production!
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '" . sha1($password) . "'";
        
        try {
            $result = $pdo->query($query);
            if ($result && $result->rowCount() > 0) {
                $user = $result->fetch(PDO::FETCH_ASSOC);
                $message = "Login successful! Welcome, " . $user['email'];
                $is_login = true;
            } else {
                $message = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
        }
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
                                <li class="breadcrumb-item"><a href="../">CSRF</a></li>
                                <li class="breadcrumb-item active">Lab 1</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 1: CSRF via Login Form</h1>
                            <span class="lab-difficulty difficulty-easy">Easy</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Vulnerable Login Form</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($message): ?>
                                    <div class="alert alert-info" role="alert">
                                        <?php echo $message; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!$is_login): ?>
                                <form method="post">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="text" class="form-control" id="email" name="email" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password:</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </form>
                                
                                <div class="mt-4">
                                    <p><strong>Test Credentials:</strong> admin@example.com / admin</p>
                                </div>
                                <?php endif; ?>
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
                                    <li>✅ Identify CSRF vulnerability</li>
                                    <li>✅ Create malicious form</li>
                                    <li>✅ Understand token absence</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ CSRF Attack Vector</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="attackAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#html">
                                                Malicious HTML
                                            </button>
                                        </h2>
                                        <div id="html" class="accordion-collapse collapse" data-bs-parent="#attackAccordion">
                                            <div class="accordion-body">
                                                <code>&lt;form method="post" action="<?php echo BASE_URL; ?>/pages/csrf/lab-1/"&gt;<br>
                                                &lt;input type="hidden" name="email" value="admin@example.com"&gt;<br>
                                                &lt;input type="hidden" name="password" value="password"&gt;<br>
                                                &lt;/form&gt;</code>
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