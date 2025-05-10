<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit File</title>
</head>
<body>
    <h1>Edit File</h1>
    <form action="/edit" method="POST" enctype="multipart/form-data">
        <div>
            <button type="button" id="toggleButton" onclick="toggleUploadMode()">File</button>
        </div>
        <div>
            <label for="name">Name on disk:</label>
            <select name="name" id="name" required>
            <?php foreach ($files as $file): ?>
                <option value="<?= htmlspecialchars($file['path']) ?>"><?= htmlspecialchars($file['name']) ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <div id="fileInput">
            <label for="file">Choose a file:</label>
            <input type="file" name="file" id="file" required>
        </div>
        <div id="fileContentInput" style="display: none;">
            <pre>
                <label for="fileContent">Enter file content:</label>
                <textarea name="fileContent" id="fileContent" rows="5" cols="30"></textarea>
            </pre>
        </div>
        <button type="submit">Edit</button>
    </form>
    <script>
        function toggleUploadMode() {
            const fileInput = document.getElementById('fileInput');
            const fileContentInput = document.getElementById('fileContentInput');
            const toggleButton = document.getElementById('toggleButton');
            const fileField = document.getElementById('file');
            const fileContentField = document.getElementById('fileContent');

            if (fileInput.style.display === 'none') {
                fileInput.style.display = 'block';
                fileContentInput.style.display = 'none';
                toggleButton.textContent = 'File';
                fileField.setAttribute('required', 'true');
                fileContentField.removeAttribute('required');
            } else {
                fileInput.style.display = 'none';
                fileContentInput.style.display = 'block';
                toggleButton.textContent = 'File content';
                fileField.removeAttribute('required');
                fileContentField.setAttribute('required', 'true');
            }
        }

        document.getElementById('name').addEventListener('change', function() {
            const selectedFilePath = this.value;
            const fileContentField = document.getElementById('fileContent');

            fetch('/api/files?path=' + encodeURIComponent(selectedFilePath))
                .then(response => response.json())
                .then(data => {
                    if (data.content) {
                        fileContentField.value = data.content;
                    } else {
                        fileContentField.value = '';
                    }
                })
                .catch(error => console.error('Error fetching file content:', error));
        });
    </script>
</body>
</html>