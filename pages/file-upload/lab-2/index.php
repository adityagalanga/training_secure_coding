<?php
$page_title = "File Upload Lab 2 - Bypass Restrictions";
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
    
    // VULNERABLE - Weak file type validation
    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);

        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (@getimagesize($file_tmp) === false) {
            $message = "File is not a valid image.";
            return;
        }

        if (in_array(strtolower($file_extension), $allowed_extensions) || in_array($finfo, $allowed_mimes)) {
            $destination = $upload_dir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $message = "File uploaded successfully: " . htmlspecialchars($filename);
            } else {
                $message = "Error uploading file.";
            }
        } else {
            $message = "Invalid file type. Only images allowed.";
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
                                <li class="breadcrumb-item active">Lab 2</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 2: Bypass File Upload Restrictions</h1>
                            <span class="lab-difficulty difficulty-medium">Medium</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Restricted File Upload (Images Only)</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($message): ?>
                                    <div class="alert alert-info" role="alert">
                                        <?php echo $message; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Choose image file:</label>
                                        <input type="file" class="form-control" id="file" name="file" accept="image/*" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Upload Image</button>
                                </form>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <strong>Allowed:</strong> JPG, JPEG, PNG, GIF files only
                                    </small>
                                </div>
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
                                    <li>✅ Bypass file restrictions</li>
                                    <li>✅ Use double extensions</li>
                                    <li>✅ Spoof MIME types</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Bypass Techniques</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="techniquesAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#double">
                                                Double Extension
                                            </button>
                                        </h2>
                                        <div id="double" class="accordion-collapse collapse" data-bs-parent="#techniquesAccordion">
                                            <div class="accordion-body">
                                                <code>shell.php.jpg</code>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mime">
                                                MIME Type Spoofing
                                            </button>
                                        </h2>
                                        <div id="mime" class="accordion-collapse collapse" data-bs-parent="#techniquesAccordion">
                                            <div class="accordion-body">
                                                Change Content-Type header to image/jpeg
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