<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<?= $this->include('partials/navbar') ?>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title">Create API Key</h1>
                <button id="generate-key" class="btn btn-primary">Generate API Key</button>
                <p id="api-key-display" class="mt-3"></p>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h2 class="card-title">Populate Database</h2>
                <button id="populate-database" class="btn btn-secondary">Populate Database</button>
                <p id="populate-status" class="mt-3"></p>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h2 class="card-title">Make a Reservation</h2>
                <form action="<?= base_url('dashboard/submit_reservation') ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="date_from" class="form-label">Start Date:</label>
                        <input type="date" id="date_from" name="date_from" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="date_to" class="form-label">End Date:</label>
                        <input type="date" id="date_to" name="date_to" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="guests" class="form-label">Number of Guests:</label>
                        <input type="number" id="guests" name="guests" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="spot" class="form-label">Spot ID:</label>
                        <input type="number" id="spot" name="spot" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment:</label>
                        <textarea id="comment" name="comment" class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Submit Reservation</button>
                </form>
                <p id="reservation-status" class="mt-3"></p>
            </div>
        </div>

        <?php if (session()->get('success')): ?>
            <div class="alert alert-success mt-4"><?= session()->get('success') ?></div>
        <?php endif; ?>

        <?php if (session()->get('error')): ?>
            <div class="alert alert-danger mt-4"><?= session()->get('error') ?></div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#generate-key').on('click', function() {
            $.ajax({
                url: '<?= base_url('apikey/store') ?>',
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        $('#api-key-display').text('API Key: ' + response.api_key).addClass('text-success');
                    } else {
                        $('#api-key-display').text('Error: ' + response.error).addClass('text-danger');
                    }
                },
                error: function() {
                    $('#api-key-display').text('An error occurred.').addClass('text-danger');
                }
            });
        });

        // Populate Database
        $('#populate-database').on('click', function() {
            $.ajax({
                url: '<?= base_url('populate-database') ?>',
                type: 'GET',
                success: function(response) {
                    $('#populate-status').text('Database successfully populated.').addClass('text-success');
                },
                error: function() {
                    $('#populate-status').text('An error occurred while populating the database.').addClass('text-danger');
                }
            });
        });
    </script>
</body>
</html>