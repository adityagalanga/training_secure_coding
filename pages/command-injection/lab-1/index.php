<?php
$page_title = "Command Injection Lab 1 - Ping Command";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$output = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $domain = $_POST['domain'] ?? '';
    
    if ($domain) {
        $output =  system("nslookup " . escapeshellarg($domain));
        
        if ($output == null): 
            $output = "No output returned. Please check the domain.";
        endif;
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
                                <li class="breadcrumb-item"><a href="../">Domain Name Injection</a></li>
                                <li class="breadcrumb-item active">Lab 1</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 1: Domain Name Injection</h1>
                            <span class="lab-difficulty difficulty-easy">Easy</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Network Ping Tool</h5>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="domain" class="form-label">Get IP Address from Domain:</label>
                                        <input type="text" class="form-control" id="domain" name="domain" placeholder="google.com" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Get IP Address</button>
                                </form>
                                
                                <?php if ($output): ?>
                                    <div class="mt-4">
                                        <h6>Output:</h6>
                                        <pre class="bg-dark text-light p-3 rounded"><?php echo htmlspecialchars($output); ?></pre>
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
                                    <li>✅ Execute system commands</li>
                                    <li>✅ Chain multiple commands</li>
                                    <li>✅ Understand command injection</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Injection Payloads</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="payloadsAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#chain">
                                                Command Chaining
                                            </button>
                                        </h2>
                                        <div id="chain" class="accordion-collapse collapse" data-bs-parent="#payloadsAccordion">
                                            <div class="accordion-body">
                                                <code>google.com; ls -la</code>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pipe">
                                                Pipe Commands
                                            </button>
                                        </h2>
                                        <div id="pipe" class="accordion-collapse collapse" data-bs-parent="#payloadsAccordion">
                                            <div class="accordion-body">
                                                <code>google.com | whoami</code>
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