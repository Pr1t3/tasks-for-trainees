<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete File</title>
</head>
<body>
    <h1>Delete File</h1>
    <form action="/delete" method="POST" enctype="multipart/form-data">
        <div>
            <label for="name">Name on disk:</label>
            <select name="name" id="name" required>
            <?php foreach ($files as $file): ?>
                <option value="<?= htmlspecialchars($file['path']) ?>"><?= htmlspecialchars($file['name']) ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Delete</button>
    </form>
</body>
</html>