<section class="content">
	<?php if (!empty($data->subTitle)): ?>
        <div class="alert alert-info alert-dismissible">
            <h5><i class="icon fas fa-info"></i> Dikkat !</h5>
			<?php echo $data->subTitle; ?>
        </div>
	<?php endif; ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
				<?php echo $data->title; ?>
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
				<?php if (!empty($data->logs)): ?>
                    <div class="col-5 col-sm-3">
                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                             aria-orientation="vertical">
							<?php $count = 1; ?>
							<?php foreach ($data->logs as $log_key => $log): ?>
                                <a class="nav-link <?php echo $count === 1 ? "active" : null; ?>"
                                   id="<?php echo $log_key; ?>-tab" data-toggle="pill"
                                   href="#tab-<?php echo $log_key; ?>" role="tab"
                                   aria-controls="tab-<?php echo $log_key; ?>"
                                   aria-selected="<?php echo $count === 1 ? "true" : "false"; ?>">
									<?php echo $functions->dateLong($log_key); ?>
                                </a>
								<?php $count++; ?>
							<?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-7 col-sm-9">
                        <div class="tab-content" id="vert-tabs-tabContent">
							<?php $logCount = 1; ?>
							<?php foreach ($data->logs as $log_key => $log): ?>
                                <div class="tab-pane text-left fade <?php echo $logCount === 1 ? "show active" : null; ?>"
                                     id="tab-<?php echo $log_key; ?>" role="tabpanel"
                                     aria-labelledby="<?php echo $log_key; ?>-tab">
                                    <div class="timeline">
                                        <div class="time-label">
                                            <span class="bg-red"><?php echo $functions->dateLong($log_key); ?></span>
                                        </div>
										<?php $itemCount = 1; ?>
										<?php foreach ($log as $log_row): ?>
                                            <div>
                                                <i class="fas fa-clock bg-red"></i>
                                                <div class="timeline-item">
                                                        <span class="time">
                                                            <i class="fas fa-clock"></i>
                                                            <?php echo $functions->dateLongWithTime($log_row->log_datetime); ?>
                                                        </span>
                                                    <h3 class="timeline-header">
                                                        <b><?php echo $itemCount; ?></b> - Bulunduğu
                                                        Sayfa: <?php echo $log_row->log_key; ?>
                                                    </h3>
                                                    <div class="timeline-body">
                                                        Sayfa linki: <?php echo $log_row->log_page; ?>
                                                    </div>
                                                </div>
                                            </div>
											<?php $itemCount++; ?>
										<?php endforeach; ?>
                                    </div>
                                </div>
								<?php $logCount++; ?>
							<?php endforeach; ?>
                        </div>
                    </div>
				<?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            Kullanıcının her hangi bir haretketi bulunmuyor.
                        </div>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</section>
