<?php
session_start();
require_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email already exists!";
        header('Location: ../admin/register_employee.php');
        exit();
    }

    // Insert new employee
    $query = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $password, $role);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Employee registered successfully!";
    } else {
        $_SESSION['error'] = "Error registering employee!";
    }

    header('Location: ../admin/register_employee.php');
    exit();
}
?>