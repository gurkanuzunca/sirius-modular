
<div class="row">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="col-md-8">
            <?php echo $this->alert->flash(['error', 'success']); ?>

            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-plus-square"></i> Kayıt Düzenle</div>
                <div class="panel-body">
                    <?php echo bsFormText('title', 'Başlık', ['required' => true, 'value' => $record->title]) ?>
                    <?php echo bsFormText('slug', 'Slug', ['value' => $record->slug]) ?>
                    <?php echo bsFormTextarea('summary', 'Özet', ['value' => $record->summary]) ?>
                    <?php echo bsFormImage('image', 'Görsel', ['value' => $record->image, 'path' => 'public/upload/content/']) ?>
                    <?php echo bsFormEditor('content', 'İçerik', ['value' => $record->content]) ?>
                </div>
                <div class="panel-footer">
                    <button class="btn btn-success" type="submit">Gönder</button>
                    <button class="btn btn-success" type="submit" name="redirect" value="records">Kaydet ve Listeye Dön</button>
                    <a class="btn btn-default" href="<?php echo moduleUri('records') ?>">Vazgeç</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <?php if ($this->isRoot()): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-plus-square"></i> Özel Değerler</div>
                    <div class="panel-body">
                        <?php echo bsFormText('reserved', 'Rezerve kayıt ise adını yazınız', ['value' => $record->reserved]) ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-plus-square"></i> Yayımla</div>
                <div class="panel-body">
                    <?php echo bsFormDropdown('status', 'Durum', ['value' => $record->status, 'options' => ['published' => 'Yayında', 'unpublished' => 'Yayında Değil']]) ?>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-plus-square"></i> Meta Bilgileri</div>
                <div class="panel-body">
                    <?php echo bsFormText('metaTitle', 'Title', ['value' => $record->metaTitle]) ?>
                    <?php echo bsFormTextarea('metaDescription', 'Description', ['value' => $record->metaDescription]) ?>
                    <?php echo bsFormTextarea('metaKeywords', 'Keywords', ['value' => $record->metaKeywords]) ?>
                </div>
            </div>
        </div>
    </form>
</div>