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
    </script>
</body>
</html>
