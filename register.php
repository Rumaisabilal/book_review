<?php
$page='register';
include 'config.php';
error_reporting(0);
session_start();
$base_url="http://localhost/book_review";

$conn_string = 'mysql:host=' . $servername . ';dbname=' . $dbname;

try {
  $pdo = new PDO($conn_string, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
  $field_error = false;
  $user_exists = false;
  $registration_success = false;
 
  if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['name'])) {
      $field_error = true;
    } else {
      $field_error = false;
      $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
      $stmt->execute([$_POST['email']]);
      $user = $stmt->fetchAll();
      if ($user) {
        $user_exists = true;
      } else {
        $user_exists = false;
        $stmt = $pdo->prepare("INSERT INTO user (name, email, password, usertype) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$_POST['name'], $_POST['email'], $_POST['password'], 'user'])) {
          $_SESSION['registration_success'] = "Registration successful! You can now log in.";
          header("Location: ".$base_url."/login.php");
          exit;
        }
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
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Register</h2>
        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Register</button>
        </form>
        <?php
        if ($field_error) {
            echo '<div class="alert alert-danger mt-3">All fields are required.</div>';
        }
        if ($user_exists) {
            echo '<div class="alert alert-danger mt-3">User already exists.</div>';
        }
        if ($registration_success) {
            echo '<div class="alert alert-success mt-3">Registration successful!</div>';
        }
        ?>
    </div>
</body>
</html>
