<?php
$page_title = "SQL Injection Lab 1 - Admin Login Bypass";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$error_message = '';
$success_message = '';
$is_login = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // $query = "SELECT * FROM users WHERE email = '$email' AND password = '" . sha1($password) . "'";
    $stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = ? LIMIT 1");
    try {
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && sha1($password) === $user['password']) {
            $success_message = "Login successful! Welcome, " . $user['email'];
            if ($user['role'] === 'admin') {
                $success_message .= " (Admin Access Granted)";
                $is_login = true;
            }
        } else {
            $error_message = "Invalid email or password.";
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
                                <li class="breadcrumb-item"><a href="../">SQL Injection</a></li>
                                <li class="breadcrumb-item active">Lab 1</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 1: Admin Login Bypass</h1>
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
                                <?php if ($error_message): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo htmlspecialchars($error_message); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($success_message): ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo $success_message; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!$is_login): ?>
                                <form method="post">
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
                                    <li>✅ Bypass authentication</li>
                                    <li>✅ Access admin account</li>
                                    <li>✅ Understand SQL injection</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Hints</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="hintsAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hint1">
                                                Hint 1: Basic SQL Injection
                                            </button>
                                        </h2>
                                        <div id="hint1" class="accordion-collapse collapse" data-bs-parent="#hintsAccordion">
                                            <div class="accordion-body">
                                                Try using single quotes (') to break out of the SQL query.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hint2">
                                                Hint 2: Authentication Bypass
                                            </button>
                                        </h2>
                                        <div id="hint2" class="accordion-collapse collapse" data-bs-parent="#hintsAccordion">
                                            <div class="accordion-body">
                                                Use OR conditions to make the query always return true.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#solution">
                                                Solution
                                            </button>
                                        </h2>
                                        <div id="solution" class="accordion-collapse collapse" data-bs-parent="#hintsAccordion">
                                            <div class="accordion-body">
                                                Email: <code>admin@example.com' OR '1'='1</code><br>
                                                Password: <code>anything</code>
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