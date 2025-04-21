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
</body>

</html>