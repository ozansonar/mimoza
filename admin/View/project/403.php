
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 admin-page-top-settings">
                    <h1>
                        <a href="javascript:goBack()"><i class="fas fa-arrow-circle-left"></i></a>
                        <?php echo $data->title; ?>
                    </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

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
</div>
<!-- /.content-wrapper -->

<!-- Page header -->
<div class="page-header border-bottom-0">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><a href="javascript:goBack()" class="color-unset"><i class="icon-arrow-left52 mr-2"></i></a> <span class="font-weight-semibold">Geri Dön</span></h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content pt-0">

    <!-- Form inputs -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"></h5>
        </div>

        <div class="card-body">
            <p class="mb-4">Bu sayfaya erişmek için yetkiniz bulunmuyor.</p>


        </div>
    </div>
    <!-- /form inputs -->

</div>
<!-- /content area -->
