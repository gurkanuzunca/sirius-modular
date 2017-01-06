<main id="main">
    <div class="page-title">
        <div class="container">
            <h1><?php echo $content->title ?></h1>
        </div>
    </div>
    <div class="container">
        <div class="typography">
            <?php echo $content->content ?>
        </div>

        <div class="buttons">
            <a class="btn" href="javascript:history.back();"><span class="fa fa-chevron-left"></span> <?php echo lang('content-go-back') ?></a>
        </div>

    </div>
</main>