<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1, h2 {
            color: #2c3e50;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #2980b9;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        .status {
            margin-top: 10px;
            font-weight: bold;
        }

        .status.success {
            color: green;
        }

        .status.error {
            color: red;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Create API Key</h1>
        <button id="generate-key">Generate API Key</button>
        <p id="api-key-display" class="status"></p>
        <a href="/documentation">DOCUMENTATION</a>

        <section>
            <h2>Populate Database</h2>
            <button id="populate-database">Populate Database</button>
            <p id="populate-status" class="status"></p>
        </section>

        <section>
            <h2>Make a Reservation</h2>
            <form action="<?= base_url('dashboard/submit_reservation') ?>" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="date_from">Start Date:</label>
                <input type="date" id="date_from" name="date_from" required>

                <label for="date_to">End Date:</label>
                <input type="date" id="date_to" name="date_to" required>

                <label for="guests">Number of Guests:</label>
                <input type="number" id="guests" name="guests" required>

                <label for="spot">Spot ID:</label>
                <input type="number" id="spot" name="spot" required>

                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment"></textarea>

                <button type="submit">Submit Reservation</button>
            </form>
            <p id="reservation-status" class="status"></p>
        </section>

        <?php if (session()->get('success')): ?>
            <p class="status success"><?= session()->get('success') ?></p>
        <?php endif; ?>

        <?php if (session()->get('error')): ?>
            <p class="status error"><?= session()->get('error') ?></p>
        <?php endif; ?>
    </div>

    <script>
        $('#generate-key').on('click', function() {
            $.ajax({
                url: '<?= base_url('apikey/store') ?>',
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        $('#api-key-display').text('API Key: ' + response.api_key).addClass('success');
                    } else {
                        $('#api-key-display').text('Error: ' + response.error).addClass('error');
                    }
                },
                error: function() {
                    $('#api-key-display').text('An error occurred.').addClass('error');
                }
            });
        });

        // Populate Database
        $('#populate-database').on('click', function() {
            $.ajax({
                url: '<?= base_url('populate-database') ?>',
                type: 'GET',
                success: function(response) {
                    $('#populate-status').text('Database successfully populated.').addClass('success');
                },
                error: function() {
                    $('#populate-status').text('An error occurred while populating the database.').addClass('error');
                }
            });
        });
    </script>
</body>
</html>