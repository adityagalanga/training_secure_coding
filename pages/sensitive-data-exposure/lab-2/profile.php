<?php
require_once '../../../config/env.php';

$query = "SELECT name,bio FROM user_profiles WHERE id = 33";
    
    try {
        $result = $pdo->query($query);
        if ($result) {
            $user_data = $result->fetch(PDO::FETCH_ASSOC);
            echo json_encode($user_data);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to fetch user data']);
    }