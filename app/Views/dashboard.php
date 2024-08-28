<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create API Key</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Create API Key</h1>
    <button id="generate-key">Generate API Key</button>
    <p id="api-key-display"></p>
    <a href="/documentation">DOCUMENTATION</a>

    <section>
        <h2>Populate Database</h2>
        <button id="populate-database">Populate Database</button>
        <p id="populate-status"></p>
    </section>

    <section>
        <h2>Make a Reservation</h2>
        <form action="<?= base_url('dashboard/submit_reservation') ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="date_from">Start Date:</label>
            <input type="date" id="date_from" name="date_from" required><br>

            <label for="date_to">End Date:</label>
            <input type="date" id="date_to" name="date_to" required><br>

            <label for="guests">Number of Guests:</label>
            <input type="number" id="guests" name="guests" required><br>

            <label for="spot">Spot ID:</label>
            <input type="number" id="spot" name="spot" required><br>

            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment"></textarea><br>

            <button type="submit">Submit Reservation</button>
        </form>
        <p id="reservation-status"></p>
    </section>
        <?php if (session()->get('success')): ?>
            <p style="color: green;"><?= session()->get('success') ?></p>
        <?php endif; ?>

        <?php if (session()->get('error')): ?>
            <p style="color: red;"><?= session()->get('error') ?></p>
        <?php endif; ?>

    <script>
        $('#generate-key').on('click', function() {
            $.ajax({
                url: '<?= base_url('apikey/store') ?>',
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        $('#api-key-display').text('API Key: ' + response.api_key);
                    } else {
                        $('#api-key-display').text('Error: ' + response.error);
                    }
                },
                error: function() {
                    $('#api-key-display').text('An error occurred.');
                }
            });
        });

        // Populate Database
        $('#populate-database').on('click', function() {
            $.ajax({
                url: '<?= base_url('populate-database') ?>',
                type: 'GET',
                success: function(response) {
                    $('#populate-status').text('Database successfully populated.');
                },
                error: function() {
                    $('#populate-status').text('An error occurred while populating the database.');
                }
            });
        });

    </script>
</body>
</html>
