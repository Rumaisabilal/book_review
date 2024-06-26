<?php
session_start(); // Ensure session is started at the very beginning
include 'config.php';
$is_logged_in = isset($_SESSION['user_id']);
$login_success = '';
if (isset($_SESSION['login_success'])) {
    $login_success = $_SESSION['login_success'];
    unset($_SESSION['login_success']);
}
$review_success = '';
if (isset($_SESSION['review_success'])) {
    $review_success = $_SESSION['review_success'];
    unset($_SESSION['review_success']);
}
$conn_string = 'mysql:host=' . $servername . ';dbname=' . $dbname;

try {
    $pdo = new PDO($conn_string, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT book_title, book_author, review, ratings FROM review ORDER BY id DESC");
    $stmt->execute();
    $reviews = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Book Buzz</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="css/style.css">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1544640808-32ca72ac7f37?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
            margin-top: 100px;
            background: rgba(0, 0, 0, 0.6);
            padding: 50px;
            border-radius: 10px;
        }
        h1 {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Adding text shadow for emphasis */
        }
        p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }
        .btn-custom {
            font-size: 1.25rem;
            padding: 10px 30px;
            margin-top: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s ease;
            margin-right: 20px;
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .reviews {
            margin-top: 40px;
            text-align: left;
            color: #fff; /* Adjusting review text color */
        }
        .review {
            background: rgba(0, 0, 0, 0.5); /* Adjusting review background transparency */
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Adding box shadow for depth */
        }
        .review h5 {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .review p {
            margin-bottom: 5px;
            font-size: 1.2rem; /* Increasing font size for better readability */
        }
        .stars {
            color: #f8d64e;
        }
    </style>
</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Book Buzz</a>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <!-- Left-aligned content if any can go here -->
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <?php if ($is_logged_in): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./logout.php">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <?php
        if (!empty($login_success)) {
            echo '<div class="alert alert-success">'.$login_success.'</div>';
        }
        if (!empty($review_success)) {
            echo '<div class="alert alert-success">'.$review_success.'</div>';
        }
        ?>
    <div class="container">
        <h1>Explore <span style="color: #17a2b8;">BookBuzz</span></h1>
        <p>Your literary haven awaits...</p>
        <p>Discover, review, and connect with fellow book enthusiasts.</p>
        <?php if (!$is_logged_in): ?>
            <a href="../login.php" class="btn btn-custom">Login</a>
            <a href="../register.php" class="btn btn-custom">Register</a>
        <?php endif; ?>
        <a href="./submit_review.php" class="btn btn-custom">Add Review</a>
    
        <div class="container mt-5">
        <h2 style="margin-bottom: 20px; font-size: 2rem;">Recent Reviews</h2>
        <div class="reviews">
            <?php if ($reviews): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <h5><?php echo htmlspecialchars($review['book_title']); ?></h5>
                        <p><strong>Author:</strong> <?php echo htmlspecialchars($review['book_author']); ?></p>
                        <p><strong>Rating:</strong> <?php echo str_repeat('★', $review['ratings']) . str_repeat('☆', 5 - $review['ratings']); ?></p>
                        <p><?php echo htmlspecialchars($review['review']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews available.</p>
            <?php endif; ?>
        </div>
    </div>
    </div>


	<script scr="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script scr="https://cdnjs.cloudflare.com.ajax/libs/popper.js/.1.16.0/umd/popper.min.js"></script>
	<script scr="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>