<?php
$page_title = "SQL Injection Lab 3 - URL Parameter Injection";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$user_data = null;
$error_message = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // VULNERABLE CODE - URL Parameter SQL Injection
    // $query = "SELECT * FROM user_profiles WHERE id = $id";
    $stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE id = ?");
    
    try {
        $stmt->execute([$id]);
        if ($stmt) {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
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
                                <li class="breadcrumb-item active">Lab 3</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 3: URL Parameter Injection</h1>
                            <span class="lab-difficulty difficulty-easy">Easy</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">User Profile Viewer</h5>
                            </div>
                            <div class="card-body">
                                <p>Click on a user ID to view their profile:</p>
                                
                                <div class="mb-3">
                                    <a href="?id=33" class="btn btn-outline-primary me-2">My Profile</a>
                                    
                                </div>
                                
                                <?php if ($error_message): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo htmlspecialchars($error_message); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($user_data): ?>
                                    <div class="alert alert-success" role="alert">
                                        <h5>User Profile:</h5>
                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($user_data['name']); ?></p>
                                        <p><strong>Bio:</strong> <?php echo htmlspecialchars($user_data['bio']); ?></p>
                                        <p><strong>NIK:</strong> <?php echo htmlspecialchars($user_data['nik']); ?></p>
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
                                    <li>✅ Exploit URL parameters</li>
                                    <li>✅ Extract database information</li>
                                    <li>✅ Use UNION-based injection</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Payloads</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="payloadsAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#basic">
                                                Basic Injection
                                            </button>
                                        </h2>
                                        <div id="basic" class="accordion-collapse collapse" data-bs-parent="#payloadsAccordion">
                                            <div class="accordion-body">
                                                <code>?id=1 OR 1=1</code>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#union">
                                                UNION Injection
                                            </button>
                                        </h2>
                                        <div id="union" class="accordion-collapse collapse" data-bs-parent="#payloadsAccordion">
                                            <div class="accordion-body">
                                                <code>?id=1 UNION SELECT 1,2,3,4,5</code>
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