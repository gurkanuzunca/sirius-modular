<main id="main">
    <div class="page-title">
        <div class="container">
            <h1><?php echo $content->title ?></h1>
        </div>
    </div>

    <div class="container">
        <div class="typography">
            <?php echo $content->detail ?>
        </div>

        <div class="content-list">
            <div class="row">
                <?php foreach ($content->childs as $child): ?>
                    <div class="col-md-6">
                        <div class="item">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="image">
                                        <a href="<?php echo clink(array('@content', $child->slug, $child->id)); ?>" title="<?php echo htmlspecialchars($child->title) ?>">
                                            <img src="<?php echo uploadPath($child->image, 'content', 480, 360) ?>" alt="<?php echo $child->title ?>" />
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="detail">
                                        <div class="title">
                                            <a href="<?php echo clink(array('@content', $child->slug, $child->id)); ?>" title="<?php echo htmlspecialchars($child->title) ?>">
                                                <?php echo $child->title ?>
                                            </a>
                                        </div>
                                        <div class="summary"><?php echo $child->summary ?></div>
                                        <a class="btn" href="<?php echo clink(array('@content', $child->slug, $child->id)); ?>" title="<?php echo htmlspecialchars($child->title) ?>"><i class="fa fa-angle-right"></i> Devamı</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


    </div>
</main>