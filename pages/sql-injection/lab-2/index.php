<?php
$page_title = "SQL Injection Lab 2 - Blind SQL Injection";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$message = '';
$login_time = '';
$is_login = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $start_time = microtime(true);
    
    // #BEFORE VULNERABLE CODE - Blind SQL Injection 
    // $query = "SELECT * FROM users WHERE email = '$email' AND password = '" . sha1($password) . "'";
    
    $stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = ? AND password = ? LIMIT 1");
    
    try {
        $stmt->execute([$email,sha1($password)]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $end_time = microtime(true);
        $login_time = number_format(($end_time - $start_time) * 1000, 2);
        
        if ($user) {
            $message = "Login successful!";
            $is_login = true; 
        } else {
            $message = "Invalid credentials.";
        }
    } catch (PDOException $e) {
        $end_time = microtime(true);
        $login_time = number_format(($end_time - $start_time) * 1000, 2);
        $message = "Login failed.";
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
                                <li class="breadcrumb-item active">Lab 2</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 2: Blind SQL Injection</h1>
                            <span class="lab-difficulty difficulty-medium">Medium</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Blind SQL Injection Login</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($message): ?>
                                    <div class="alert alert-info" role="alert">
                                        <?php echo htmlspecialchars($message); ?>
                                        <?php if ($login_time): ?>
                                            <br><small>Response time: <?php echo $login_time; ?>ms</small>
                                        <?php endif; ?>
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

                                <?php endif;?>
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
                                    <li>✅ Identify blind SQL injection</li>
                                    <li>✅ Use boolean-based techniques</li>
                                    <li>✅ Analyze response timing</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Techniques</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="techniquesAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#boolean">
                                                Boolean-based Blind
                                            </button>
                                        </h2>
                                        <div id="boolean" class="accordion-collapse collapse" data-bs-parent="#techniquesAccordion">
                                            <div class="accordion-body">
                                                <code>admin@example.com' AND (SELECT COUNT(*) FROM users) > 0 --</code>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#timing">
                                                Time-based Blind
                                            </button>
                                        </h2>
                                        <div id="timing" class="accordion-collapse collapse" data-bs-parent="#techniquesAccordion">
                                            <div class="accordion-body">
                                                <code>admin@example.com' AND (SELECT SLEEP(5)) --</code>
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