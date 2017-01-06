<!DOCTYPE html>
<html lang="<?php echo $this->language ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo $this->stack->get('options.metaTitle') ?></title>
    <meta name="description" content="<?php echo $this->stack->get('options.metaDescription') ?>">
    <meta name="keywords" content="<?php echo $this->stack->get('options.metaKeywords') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="<?php echo base_url('/') ?>" />

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700|Roboto:400,500,700&amp;subset=latin-ext" />
    <link rel="stylesheet" type="text/css" href="public/assets/compiled.css" />
    <?php foreach ($this->assets->css() as $css): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $css ?>" />
    <?php endforeach; ?>
    <link rel="stylesheet" type="text/css" href="public/css/main.css" />

    <script type="text/javascript" src="public/assets/compiled.js"></script>
    <?php foreach ($this->assets->js() as $js): ?>
        <script type="text/javascript" src="<?php echo $js ?>"></script>
    <?php endforeach; ?>
    <script type="text/javascript" src="public/js/main.js"></script>

    <?php if ($this->stack->has('options.ogType')): ?>
        <meta property="og:type" content="<?php echo $this->stack->has('options.ogType') ?>" />
    <?php endif; ?>
    <?php if ($this->stack->has('options.ogTitle')): ?>
        <meta property="og:title" content="<?php echo htmlspecialchars($this->stack->has('options.ogTitle')) ?>" />
    <?php endif; ?>
    <?php if ($this->stack->has('options.ogDescription')): ?>
        <meta property="og:description" content="<?php echo htmlspecialchars($this->stack->has('options.ogDescription')) ?>" />
    <?php endif; ?>
    <?php if ($this->stack->has('options.ogImage')): ?>
        <meta property="og:image" content="<?php echo base_url('/').$this->stack->has('options.ogImage') ?>"/>
    <?php endif; ?>

    <meta property="og:url" content="<?php echo current_url() ?>"/>

    <?php echo $this->stack->get('options.customMeta') ?>
</head>
<body>

<?php $this->view($view); ?>

</body>
</html>
