<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View File</title>
</head>
<body>
    <pre>
        <?php echo htmlspecialchars($fileContent, ENT_QUOTES, 'UTF-8'); ?>
    </pre>
</body>
</html>