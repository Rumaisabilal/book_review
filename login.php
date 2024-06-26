<?php
session_start();
$page = 'login';
$base_url="http://localhost/book_review";
include 'config.php';
error_reporting(E_ALL); 
$registration_success = '';
if (isset($_SESSION['registration_success'])) {
    $registration_success = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']);
}

$conn_string = 'mysql:host=' . $servername . ';dbname=' . $dbname;

try {
    $pdo = new PDO($conn_string, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

    if (isset($_POST['submit'])) {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $_SESSION['login_error'] = "All fields are required.";
            header("Location: ".$base_url."/login.php");
            exit();
        } else {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ? AND password = ?");
            $stmt->execute([$_POST['email'], $_POST['password']]);
            $user = $stmt->fetchAll();
            if ($user) {
                $_SESSION['user_id'] = $user[0]['id']; 
                $_SESSION['email'] = $user[0]['email']; 
                $_SESSION['login_success'] = "Login successful! Welcome back.";
                header("Location: ".$base_url."/index.php");
                exit(); 
            } else {
                $_SESSION['login_error'] = "Invalid email or password.";
                header("Location: ".$base_url."/login.php");
                exit();
            }
        }
    }
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <?php
        if (!empty($registration_success)) {
            echo '<div class="alert alert-success mt-3">'.$registration_success.'</div>';
        }
        ?>
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger mt-3"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Login</button>
        </form>
        
    </div>
</body>
</html>
