<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        h1 {
            color: #343a40;
            margin-bottom: 40px;
        }

        .endpoint {
            font-size: 1.2rem;
            color: #e74c3c;
        }

        .method {
            font-weight: bold;
            color: #3498db;
        }

        .example {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin-top: 15px;
        }

        .response {
            background-color: #f1c40f;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <a href="/">HOME</a>
    <div class="container">
        <h1 class="text-center">API Documentation</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Get Reservations Between Dates</h2>
                <p class="card-text">
                    <span class="method">POST</span> <span
                        class="endpoint"><?= base_url() ?>api/get_reservations_between_dates</span>
                </p>
                <p>This endpoint retrieves reservations between two specified dates.</p>

                <h5>Request Headers:</h5>
                <ul>
                    <li><strong>Authorization:</strong> Bearer YOUR_API_KEY</li>
                    <li><strong>Content-Type:</strong> application/json</li>
                </ul>

                <h5>Request Body:</h5>
                <div class="example">
                    <pre>{
    "date_from": "2024-09-01",
    "date_to": "2024-09-30"
}</pre>
                </div>

                <h5>Example Request:</h5>
                <div class="example">
                    <pre>POST <?= base_url() ?>api/get_reservations_between_dates
Headers:
    Authorization: Bearer YOUR_API_KEY
    Content-Type: application/json

Body:
{
    "date_from": "2024-09-01",
    "date_to": "2024-09-30"
}</pre>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Insert Reservation</h2>
                <p class="card-text">
                    <span class="method">POST</span> <span class="endpoint"><?= base_url() ?>api/insert</span>
                </p>
                <p>This endpoint inserts a new reservation into the system over a range of dates.</p>

                <h5>Request Headers:</h5>
                <ul>
                    <li><strong>Authorization:</strong> Bearer YOUR_API_KEY</li>
                    <li><strong>Content-Type:</strong> application/json</li>
                </ul>

                <h5>Request Body:</h5>
                <div class="example">
                    <pre>{
    "name": "John Doe",
    "phone_number": "123-456-7890",
    "email": "johndoe@example.com",
    "date_from": "2024-09-15",
    "date_to": "2024-09-20",
    "guests": 4,
    "spot": 2,
    "comment": "Prefer a quiet spot near the lake."
}</pre>
                </div>

                <h5>Example Request:</h5>
                <div class="example">
                    <pre>POST <?= base_url() ?>api/insert
Headers:
    Authorization: Bearer YOUR_API_KEY
    Content-Type: application/json

Body:
{
    "name": "John Doe",
    "phone_number": "123-456-7890",
    "email": "johndoe@example.com",
    "date_from": "2024-09-15",
    "date_to": "2024-09-20",
    "guests": 4,
    "spot": 2,
    "comment": "Prefer a quiet spot near the lake."
}</pre>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Get All Reservations</h2>
                <p class="card-text">
                    <span class="method">POST</span> <span class="endpoint"><?= base_url() ?>api/get_all</span>
                </p>
                <p>This endpoint retrieves all reservations from the system.</p>
        
                <h5>Request Headers:</h5>
                <ul>
                    <li><strong>Authorization:</strong> Bearer YOUR_API_KEY</li>
                    <li><strong>Content-Type:</strong> application/json</li>
                </ul>

                <h5>Request Body:</h5>
                <div class="example">
                    <pre>{}</pre>
                </div>

                <h5>Example Request:</h5>
                <div class="example">
                    <pre>POST <?= base_url() ?>api/get_all
Headers:
    Authorization: Bearer YOUR_API_KEY
    Content-Type: application/json

Body:
{}</pre>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
