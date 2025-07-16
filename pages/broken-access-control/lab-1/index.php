<?php
$page_title = "Broken Access Control Lab 1 - Vertical Privilege Escalation";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$message = '';
$user_role = $_SESSION['user_role'] ?? 'user';
$is_login = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user'; // VULNERABLE - Role can be manipulated
   
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '" . sha1($password) . "'";
    try {
        $result = $pdo->query($query);
        if ($result && $result->rowCount() > 0) {
            $user = $result->fetch(PDO::FETCH_ASSOC);
            $role = $user['role'];
            
            if($role === 'user') {
                $_SESSION['user_role'] = $role;
                $is_login = true;
            } else {
                $message = "You are not allowed to access this page.";
                $is_login = false;
            }
        } else {
            $message = "Invalid credentials";
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
                                <li class="breadcrumb-item"><a href="../">Broken Access Control</a></li>
                                <li class="breadcrumb-item active">Lab 1</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 1: Vertical Privilege Escalation</h1>
                            <span class="lab-difficulty difficulty-medium">Medium</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">User Login</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($message): ?>
                                    <div class="alert alert-info" role="alert">
                                        <?php echo $message; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($is_login && !$is_admin): ?>
                                    <div class="alert alert-success" role="alert">
                                        Login successful! Role: <?php echo htmlspecialchars($user_role); ?>
                                    </div>
                                <?php else: ?>
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="text" class="form-control" id="email" name="email" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password:</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    
                                    <input type="hidden" name="role" value="user">
                                    
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </form>
                                <?php endif; ?>
                                <div class="mt-3">
                                    <p><strong>Test Credentials:</strong> john@example.com / john12345</p>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($user_role == 'admin'): ?>
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">🔐 Admin Panel</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">Privilege Escalation Successful!</h4>
                                    <p>You have successfully escalated privileges to admin level.</p>
                                </div>
                                
                                <h6>Admin Functions:</h6>
                                <ul>
                                    <li>View all user data</li>
                                    <li>Modify system settings</li>
                                    <li>Access sensitive information</li>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">💡 Lab Objectives</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>✅ Escalate to admin role</li>
                                    <li>✅ Access admin panel</li>
                                    <li>✅ Manipulate hidden fields</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Escalation Techniques</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="techniquesAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hidden">
                                                Hidden Field Manipulation
                                            </button>
                                        </h2>
                                        <div id="hidden" class="accordion-collapse collapse" data-bs-parent="#techniquesAccordion">
                                            <div class="accordion-body">
                                                Inspect the form and modify the hidden role field to "admin"
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