<!-- Main content -->
<section class="content">
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title"><?php echo $data->title; ?>
                <?php if(!empty($sub_title)): ?>
                    <br>
                    <small><?php echo $sub_title; ?></small>
                <?php endif; ?>
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            Bu sayfayı görüntülemek için yetkiniz yoktur.
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->