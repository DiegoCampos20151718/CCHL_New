<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION)) { 
    session_start(); 
} 
if (!isset($_SESSION['cchl']['rol']) || ($_SESSION['cchl']['rol'] != 1 && $_SESSION['cchl']['rol'] != 2)) {
    header('location: index.php');
    exit;
}

// Handle file saving
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folderName']) && isset($_FILES['pdfFiles'])) {
    $folderName = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folderName']); // Sanitize folder name
    $targetDir = "assets/Certificados/$folderName/";

    // Create directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Save uploaded files
    foreach ($_FILES['pdfFiles']['tmp_name'] as $index => $tmpName) {
        $fileName = $_FILES['pdfFiles']['name'][$index];
        $targetFile = $targetDir . basename($fileName);

        if (move_uploaded_file($tmpName, $targetFile)) {
            echo "<p>File '$fileName' saved to '$targetDir'</p>";
        } else {
            echo "<p>Error saving file '$fileName'.</p>";
        }
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Save PDF files to a user-defined folder">
    <meta name="keywords" content="PDF upload, folder creation, modern design">

    <!-- Favicons -->
    <link href="assets/img/imss-green-icon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Nunito:300,400,600,700|Poppins:300,400,500,600,700" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <title>Save PDFs to Folder</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }
        h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
        }
        .main {
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <!-- ======= Header ======= -->
    <?php include_once 'page-format/header.php'; ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include_once 'page-format/sidebar.php'; ?>
    <!-- End Sidebar -->

    <main id="main" class="main container mt-9">
        <h1 class="text-center mb-4">Save PDFs from a Selected Folder</h1>
        <div class="p-4 bg-light rounded">
            <div class="mb-3">
                <label for="folderName" class="form-label">Folder Name</label>
                <input type="text" id="folderName" class="form-control" placeholder="Enter folder name" required>
            </div>
            <button id="selectFolder" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-folder"></i> Select PDF Folder
            </button>
            <button id="uploadFiles" class="btn btn-success w-100 hidden">Upload Selected PDFs</button>
        </div>
        <ul id="fileList" class="list-group mt-4"></ul>
    </main>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        const selectFolderButton = document.getElementById('selectFolder');
        const uploadFilesButton = document.getElementById('uploadFiles');
        const fileList = document.getElementById('fileList');
        const folderNameInput = document.getElementById('folderName');

        let selectedFiles = [];

        // Open folder picker and list PDFs
        selectFolderButton.addEventListener('click', async () => {
            try {
                const folderHandle = await window.showDirectoryPicker();
                selectedFiles = [];
                fileList.innerHTML = ''; // Clear previous list

                for await (const [name, handle] of folderHandle) {
                    if (handle.kind === 'file' && name.endsWith('.pdf')) {
                        selectedFiles.push(await handle.getFile());
                        const listItem = document.createElement('li');
                        listItem.textContent = name;
                        listItem.className = 'list-group-item';
                        fileList.appendChild(listItem);
                    }
                }

                if (selectedFiles.length > 0) {
                    uploadFilesButton.classList.remove('hidden');
                } else {
                    alert('No PDF files found in the selected folder.');
                }
            } catch (error) {
                console.error('Error selecting folder:', error);
            }
        });

        // Upload files to server
        uploadFilesButton.addEventListener('click', async () => {
            const folderName = folderNameInput.value.trim();
            if (!folderName) {
                alert('Please enter a folder name.');
                return;
            }

            const formData = new FormData();
            formData.append('folderName', folderName);

            selectedFiles.forEach((file, index) => {
                formData.append(`pdfFiles[]`, file);
            });

            const response = await fetch('<?= $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                body: formData,
            });

            const result = await response.text();
            alert('Files uploaded successfully!');
            console.log(result);
        });
    </script>
</body>
</html>
