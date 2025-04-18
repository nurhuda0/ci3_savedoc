<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?></title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Link to your CSS file -->
</head>

<body>
    <div class="container">
        <?php $this->load->view($main_content); ?>
    </div>
</body>

</html>