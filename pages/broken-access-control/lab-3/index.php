<?php
$page_title = "Business Logic Lab 1 - Price Manipulation";
require_once '../../../config/env.php';
require_once '../../../template/header.php';

$message = '';

$query = "SELECT * FROM customer_balances WHERE user_id = 2";
try {
        $result = $pdo->query($query);
        if ($result) {
            $balance = $result->fetch(PDO::FETCH_ASSOC);

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $amount = $balance['balance'] - $_POST['amount'];
                if($amount < 1){
                    $message = "Balance is not enough";
                } else {
                    $sql = "UPDATE customer_balances SET balance = $amount WHERE user_id = 2";
                    $pdo->query($sql);
                    $message = "Withdrawal successful";
                }                
            }
        }
    } catch (PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
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
                                <li class="breadcrumb-item active">Lab 3</li>
                            </ol>
                        </nav>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h2">Lab 1: Amount Manipulation</h1>
                            <span class="lab-difficulty difficulty-easy">Easy</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">E-wallet</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($message): ?>
                                    <div class="alert alert-info" role="alert">
                                        <?php echo $message; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <h5>Balance</h5>
                                                <h4 class="text-primary">RP. <?php echo number_format($balance['balance']); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Amount:</label>
                                        <input type="hidden" class="form-control" id="balance" name="balance" value="<?php echo ($balance['balance']);?>" required>
                                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="0" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Withdraw</button>
                                </form>
                                
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
                                    <li>✅ Manipulate balance</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">🛠️ Exploitation Techniques</h5>
                            </div>
                            <div class="card-body">
                                <div class="accordion" id="techniquesAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#price">
                                                Amount Tampering
                                            </button>
                                        </h2>
                                        <div id="price" class="accordion-collapse collapse" data-bs-parent="#techniquesAccordion">
                                            <div class="accordion-body">
                                                Change balance to > 0
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