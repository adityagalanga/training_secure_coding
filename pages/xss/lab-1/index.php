<?php
$page_title = "XSS Lab 1 - Stored XSS";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$message = '';
$profiles = [];
$userID = 2;

// Fetch existing profiles
try {
    $result = $pdo->query("SELECT * FROM user_profiles where user_id = $userID");
    $profiles = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Table might not exist, create it
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        bio TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $bio = $_POST['bio'] ?? '';
    
    $name = htmlspecialchars($name,  ENT_HTML5, 'UTF-8');
    $bio = htmlspecialchars($bio, ENT_HTML5, 'UTF-8');

    $query = "UPDATE user_profiles SET name = ?, bio = ? where user_id = $userID";
    $stmt = $pdo->prepare($query);
    
    if ($stmt->execute([$name, $bio])) {
        $message = "Profile updated successfully!";
        // Refresh profiles
        $result = $pdo->query("SELECT * FROM user_profiles where user_id = $userID");
        $profiles = $result->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $message = "Error updating profile.";
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
                                <li class="breadcrumb-item"><a href="../">XSS</a></li>
                                <li class="breadcrumb-item active">Lab 1</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 1: Stored XSS via Profile Update</h1>
                            <span class="lab-difficulty difficulty-medium">Medium</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Update Your Profile</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($message): ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo htmlspecialchars($message); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name:</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Bio:</label>
                                        <textarea class="form-control" id="bio" name="bio" rows="3" required></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Profiles</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($profiles)): ?>
                                    <p class="text-muted">No profiles yet. Create the first one!</p>
                                <?php else: ?>
                                    <?php foreach ($profiles as $profile): ?>
                                        <div class="border rounded p-3 mb-3">
                                            <h6>Name: <?php echo $profile['name']; ?></h6>
                                            <p>Bio: <?php echo $profile['bio']; ?></p>
                                        </div>
                                    <?php endforeach; ?>
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
                                    <li>✅ Execute stored XSS</li>
                                    <li>✅ Understand persistence</li>
                                    <li>✅ Analyze impact</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ XSS Payloads</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="payloadsAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#basic">
                                                Basic Alert
                                            </button>
                                        </h2>
                                        <div id="basic" class="accordion-collapse collapse" data-bs-parent="#payloadsAccordion">
                                            <div class="accordion-body">
                                                <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cookie">
                                                Cookie Theft
                                            </button>
                                        </h2>
                                        <div id="cookie" class="accordion-collapse collapse" data-bs-parent="#payloadsAccordion">
                                            <div class="accordion-body">
                                                <code>&lt;script&gt;alert(document.cookie)&lt;/script&gt;</code>
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