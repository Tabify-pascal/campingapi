<?= $this->extend('layouts/main_template') ?>

<?= $this->section('title') ?>
API Documentation
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="text-center text-dark mb-5">API Documentation</h1>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h2 class="card-title">Getting Started</h2>
        <p class="card-text">To use our API, you need to create an account. Once registered and logged in, you can generate an API key, which is required for authentication in all API requests.</p>
        <p class="card-text">To create an account, please visit the <a href="<?= base_url('register') ?>">registration page</a>. After logging in, you can generate your API key from the dashboard.</p>
        <p class="card-text">Use this API key in the Authorization header for all API requests:</p>
        <div class="bg-light p-3 rounded border">
            <pre>Authorization: Bearer YOUR_API_KEY</pre>
        </div>
    </div>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h2 class="card-title">Populate Database</h2>
        <p class="card-text">
            <span class="badge bg-primary">POST</span> 
            <span class="text-danger"><?= base_url() ?>api/populate-database</span>
        </p>
        <p>This endpoint populates the database with random reservations and spot availability data for the next 100 days.</p>

        <h5>Request Headers:</h5>
        <ul>
            <li><strong>Authorization:</strong> Bearer YOUR_API_KEY</li>
            <li><strong>Content-Type:</strong> application/json</li>
        </ul>

        <h5>Example Request:</h5>
        <div class="bg-light p-3 rounded border">
            <pre>POST <?= base_url() ?>api/populate-database
Headers:
Authorization: Bearer YOUR_API_KEY
Content-Type: application/json

Body:
{}</pre>
        </div>
    </div>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h2 class="card-title">Get Available Dates</h2>
        <p class="card-text">
            <span class="badge bg-primary">POST</span> 
            <span class="text-danger"><?= base_url() ?>api/get-available-dates</span>
        </p>
        <p>This endpoint retrieves the available dates for a specific spot within a given date range.</p>

        <h5>Request Headers:</h5>
        <ul>
            <li><strong>Authorization:</strong> Bearer YOUR_API_KEY</li>
            <li><strong>Content-Type:</strong> application/json</li>
        </ul>

        <h5>Request Body:</h5>
        <div class="bg-light p-3 rounded border">
            <pre>{
    "spot_id": 2,
    "date_from": "2024-09-01",
    "date_to": "2024-09-30"
}</pre>
        </div>

        <h5>Example Request:</h5>
        <div class="bg-light p-3 rounded border">
            <pre>POST <?= base_url() ?>api/get-available-dates
Headers:
Authorization: Bearer YOUR_API_KEY
Content-Type: application/json

Body:
{
    "spot_id": 2,
    "date_from": "2024-09-01",
    "date_to": "2024-09-30"
}</pre>
        </div>

        <h5>Example Response:</h5>
        <div class="bg-warning p-3 rounded border">
            <pre>{
    "success": true,
    "available_dates": [
        "2024-09-01",
        "2024-09-03",
        "2024-09-05",
        ...
    ]
}</pre>
        </div>
    </div>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h2 class="card-title">Get Campsite Spots</h2>
        <p class="card-text">
            <span class="badge bg-primary">POST</span> 
            <span class="text-danger"><?= base_url() ?>api/get-campsite-spots</span>
        </p>
        <p>This endpoint retrieves a list of all available campsite spots, including their price, number of guests, name, and type.</p>

        <h5>Request Headers:</h5>
        <ul>
            <li><strong>Authorization:</strong> Bearer YOUR_API_KEY</li>
            <li><strong>Content-Type:</strong> application/json</li>
        </ul>

        <h5>Request Body:</h5>
        <div class="bg-light p-3 rounded border">
            <pre>{}</pre> <!-- No request body required -->
        </div>

        <h5>Example Request:</h5>
        <div class="bg-light p-3 rounded border">
            <pre>POST <?= base_url() ?>api/get-campsite-spots
Headers:
Authorization: Bearer YOUR_API_KEY
Content-Type: application/json

Body:
{}</pre>
        </div>

        <h5>Example Response:</h5>
        <div class="bg-warning p-3 rounded border">
            <pre>{
    "success": true,
    "data": [
        {
            "id": 1,
            "price": 120,
            "number_of_guests": 4,
            "name": "B1",
            "type": "Bungalow"
        },
        {
            "id": 2,
            "price": 80,
            "number_of_guests": 5,
            "name": "T2",
            "type": "Tent"
        },
        {
            "id": 3,
            "price": 50,
            "number_of_guests": 2,
            "name": "T3",
            "type": "Tent"
        },
        ...
    ]
}</pre>
        </div>
    </div>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h2 class="card-title">Insert Reservation</h2>
        <p class="card-text">
            <span class="badge bg-primary">POST</span> 
            <span class="text-danger"><?= base_url() ?>api/insert</span>
        </p>
        <p>This endpoint inserts a new reservation into the system over a range of dates.</p>

        <h5>Request Headers:</h5>
        <ul>
            <li><strong>Authorization:</strong> Bearer YOUR_API_KEY</li>
            <li><strong>Content-Type:</strong> application/json</li>
        </ul>

        <h5>Request Body:</h5>
        <div class="bg-light p-3 rounded border">
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
        <div class="bg-light p-3 rounded border">
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

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h2 class="card-title">Get Reservations Between Dates</h2>
        <p class="card-text">
            <span class="badge bg-primary">POST</span> 
            <span class="text-danger"><?= base_url() ?>api/get_reservations_between_dates</span>
        </p>
        <p>This endpoint retrieves reservations between two specified dates.</p>

        <h5>Request Headers:</h5>
        <ul>
            <li><strong>Authorization:</strong> Bearer YOUR_API_KEY</li>
            <li><strong>Content-Type:</strong> application/json</li>
        </ul>

        <h5>Request Body:</h5>
        <div class="bg-light p-3 rounded border">
            <pre>{
    "date_from": "2024-09-01",
    "date_to": "2024-09-30"
}</pre>
        </div>

        <h5>Example Request:</h5>
        <div class="bg-light p-3 rounded border">
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

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <h2 class="card-title">Get All Reservations</h2>
        <p class="card-text">
            <span class="badge bg-primary">POST</span> 
            <span class="text-danger"><?= base_url() ?>api/get_all</span>
        </p>
        <p>This endpoint retrieves all reservations from the system.</p>

        <h5>Request Headers:</h5>
        <ul>
            <li><strong>Authorization:</strong> Bearer YOUR_API_KEY</li>
            <li><strong>Content-Type:</strong> application/json</li>
        </ul>

        <h5>Request Body:</h5>
        <div class="bg-light p-3 rounded border">
            <pre>{}</pre>
        </div>

        <h5>Example Request:</h5>
        <div class="bg-light p-3 rounded border">
            <pre>POST <?= base_url() ?>api/get_all
Headers:
Authorization: Bearer YOUR_API_KEY
Content-Type: application/json

Body:
{}</pre>
        </div>
    </div>
</div>
<?= $this->endSection() ?>