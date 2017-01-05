
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
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-plus-square"></i> Yayımla</div>
                <div class="panel-body">
                    <?php echo bsFormDropdown('status', 'Durum', ['value' => $record->status, 'options' => ['published' => 'Yayında', 'unpublished' => 'Yayında Değil']]) ?>
                    <?php echo bsFormText('createdAt', 'Oluşturulma', ['value' => $this->date->set($record->createdAt)->datetimeWithName(), 'disabled' => true]) ?>
                    <?php echo bsFormText('updatedAt', 'Güncellenme', ['value' => $this->date->set($record->updatedAt)->datetimeWithName(), 'disabled' => true]) ?>
                </div>
            </div>
        </div>
    </form>
</div>