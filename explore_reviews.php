<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Reviews</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Explore Reviews</h1>
        <div class="row">
            {% for review in reviews %}
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ review.book.title }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ review.book.author }}</h6>
                        <p class="card-text">Rating: {{ review.rating }}</p>
                        <p class="card-text">{{ review.text }}</p>
                    </div>
                </div>
            </div>
            {% endfor %}
        </div>
    </div>
</body>
</html>

