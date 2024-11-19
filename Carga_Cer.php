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
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/imss-green-icon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <title>Load Files from Folder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #fileList {
            margin-top: 20px;
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 5px 0;
        }
    </style>
</head>
<body id="pagina">
    <!-- ======= Header ======= -->
    <?php include_once 'page-format/header.php'; ?>
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <?php include_once 'page-format/sidebar.php'; ?>
    <!-- End Sidebar -->

    <main id="main" class="main"> 
        <h1>Load Files from a Folder</h1>
        <button id="selectFolder" class="btn btn-primary">Select Folder</button>
        <p id="warning" style="color: red; display: none;">Your browser does not support File System Access API.</p>
        <ul id="fileList"></ul>
    </main>

    <script>
        const selectFolderButton = document.getElementById('selectFolder');
        const fileList = document.getElementById('fileList');
        const warning = document.getElementById('warning');

        // Check browser compatibility
        if (!window.showDirectoryPicker) {
            warning.style.display = 'block';
            selectFolderButton.disabled = true;
        }

        selectFolderButton.addEventListener('click', async () => {
            try {
                // Open folder picker
                const folderHandle = await window.showDirectoryPicker();
                fileList.innerHTML = ''; // Clear previous list
                
                // Iterate through files in the folder
                for await (const [name, handle] of folderHandle) {
                    if (handle.kind === 'file') {
                        const listItem = document.createElement('li');
                        listItem.textContent = name;
                        fileList.appendChild(listItem);
                    }
                }
            } catch (error) {
                console.error('Error selecting folder:', error);
                alert('Folder selection canceled or not supported.');
            }
        });
    </script>
</body>
</html>
