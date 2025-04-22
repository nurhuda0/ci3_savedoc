<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Link to your CSS file -->
</head>

<body>
    <h1><?php echo $page_title; ?></h1>

    <?php if (isset($suc_msg)): ?>
        <div class="alert alert-success"><?php echo $suc_msg; ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form enctype="multipart/form-data" class="jNice" accept-charset="utf-8" method="post" action="<?php echo site_url('admin/add_estates'); ?>">
        <fieldset>
            <label>Title : </label>
            <input type="text" class="text-long" value="" name="title" required>
            <br><br>
            <label>Description : </label>
            <textarea class="mceEditor" rows="10" cols="40" name="description"></textarea>
            <br><br>
            <label>Image : </label>
            <input type="file" multiple="" name="images[]">
            <br><br>
            <button class="button-submit" type="submit" name="save">Save</button>
        </fieldset>
    </form>

    <h2>Images List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Upload Date</th> <!-- New column for Upload Date -->
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($estates)): ?>
                <?php foreach ($estates as $estate): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($estate['id']); ?></td>
                        <td><?php echo htmlspecialchars($estate['title']); ?></td>
                        <td><?php echo htmlspecialchars($estate['created_at']); ?></td> <!-- Displaying created_at -->
                        <td>
                            <button onclick="downloadEstate(<?php echo $estate['id']; ?>)" class="button-download">Download</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No estates found.</td>
                </tr>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error_msg')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error_msg'); ?></div>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        function downloadEstate(id) {
            window.location.href = "<?php echo site_url('admin/download_estate/'); ?>" + id;
        }
    </script>
</body>

</html>