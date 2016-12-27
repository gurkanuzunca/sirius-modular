<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="<?php echo base_url('admin/') ?>" />

    <link rel="stylesheet" type="text/css" href="../public/admin/plugin/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="../public/admin/plugin/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../public/admin/css/main.css" />

    <script type="text/javascript" src="../public/admin/js/jquery.js"></script>
    <script type="text/javascript" src="../public/admin/plugin/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../public/admin/js/main.js"></script>

</head>
<body>

<?php $this->view($view, @$data); ?>

</body>
</html>
