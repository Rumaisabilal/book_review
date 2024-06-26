<?php
session_start();
include 'config.php';
$base_url="http://localhost/book_review";

$conn_string = 'mysql:host=' . $servername . ';dbname=' . $dbname;

try {
    $pdo = new PDO($conn_string, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['submit'])) {
        if (empty($_POST['book_title']) || empty($_POST['book_author']) || empty($_POST['review']) || empty($_POST['rating'])) {
            $_SESSION['review_error'] = "All fields are required.";
            header("Location: ".$base_url."/submit_review.php");
            exit();
        } else {
            $stmt = $pdo->prepare("INSERT INTO review (book_title, book_author, review, ratings) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$_POST['book_title'], $_POST['book_author'], $_POST['review'], $_POST['rating']])) {
                $_SESSION['review_success'] = "Review added successfully!";
                header("Location: ".$base_url."/index.php");
                exit();
            } else {
                $_SESSION['review_error'] = "Failed to add review. Please try again.";
                header("Location: ".$base_url."/submit_review.php");
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
    <title>Submit Review</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  
    <div class="container mt-5">
        <h2>Submit Review</h2>
        <form method="post" action="submit_review.php">
            <div class="form-group">
                <label for="book_title">Book Title</label>
                <input type="text" class="form-control" id="book_title" name="book_title" required>
            </div>
            <div class="form-group">
                <label for="book_author">Book Author</label>
                <input type="text" class="form-control" id="book_author" name="book_author" required>
            </div>
            <div class="form-group">
                <label for="review">Review</label>
                <input type="text" class="form-control" id="review" name="review" required>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit Review</button>
        </form>
    </div>
</body>
</html>