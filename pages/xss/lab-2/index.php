<?php
$page_title = "XSS Lab 2 - Reflected XSS";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$search_term = htmlspecialchars($_GET['search']) ?? '';
$error_message = $_GET['error'] ?? '';

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
                                <li class="breadcrumb-item"><a href="../">XSS</a></li>
                                <li class="breadcrumb-item active">Lab 2</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 2: Reflected XSS via Search</h1>
                            <span class="lab-difficulty difficulty-easy">Easy</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Search Users</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($error_message): ?>
                                    <div class="alert alert-danger" role="alert">
                                        Error: <?php echo $error_message; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="get">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="search" placeholder="Search for users..." value="<?php echo htmlspecialchars($search_term); ?>">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </form>
                                
                                <?php if ($search_term): ?>
                                    <div class="alert alert-info" role="alert">
                                        <h5>Search Results for: <?php echo $search_term; ?></h5>
                                        <p>No users found matching your search criteria.</p>
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
                                    <li>✅ Execute reflected XSS</li>
                                    <li>✅ Understand URL parameters</li>
                                    <li>✅ Craft malicious links</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Reflected XSS Payloads</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="payloadsAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#simple">
                                                Simple Alert
                                            </button>
                                        </h2>
                                        <div id="simple" class="accordion-collapse collapse" data-bs-parent="#payloadsAccordion">
                                            <div class="accordion-body">
                                                <code>&lt;script&gt;alert('Reflected XSS')&lt;/script&gt;</code>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#img">
                                                Image Tag
                                            </button>
                                        </h2>
                                        <div id="img" class="accordion-collapse collapse" data-bs-parent="#payloadsAccordion">
                                            <div class="accordion-body">
                                                <code>&lt;img src=x onerror=alert('XSS')&gt;</code>
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