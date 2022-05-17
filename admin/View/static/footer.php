<?php if ($session->isThereAdminSession() && $system->route(1) !== "login"): ?>
    <!-- Main Footer -->
    <footer class="main-footer">
        <strong><?php echo $settings->project_name; ?></strong>
    </footer>
    </div>
<?php endif; ?>
<?php if (isset($message) && is_array($message) && (isset($message["reply"]) || isset($message["success"]))): ?>
    <!-- modal content -->
    <div id="page_alert_modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header <?php echo isset($message["success"]) ? "bg-success" : "bg-danger"; ?>">
                    <h6 class="modal-title"><?php echo isset($message["success"]) ? "İşlem Başarılı" : "Hata"; ?></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>
						<?php
						if (!empty($message["reply"])) {
							foreach ($message["reply"] as $m_reply) {
								echo $m_reply . "<br>";
							}
						}
						if (!empty($message["success"])) {
							foreach ($message["success"] as $m_success) {
								echo $m_success . "<br>";
							}
						}
						?>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn <?php echo isset($message["success"]) ? "bg-success" : "bg-danger"; ?>"
                            data-dismiss="modal">Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <script>
        $(document).ready(function () {
            $("#page_alert_modal").modal();
        });
    </script>
<?php endif; ?>
<?php $sessionError = $session->get('session_error');
if (!empty($sessionError)): ?>
    <!-- modal content -->
    <div id="page_alert_modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-danger ">
                    <h6 class="modal-title">Hata</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><?php echo $sessionError; ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn bg-danger"
                            data-dismiss="modal">Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
	<?php $session->delete('session_error') ?>
    <!-- /.modal -->
    <script>
        $(document).ready(function () {
            $("#page_alert_modal").modal();
        });
    </script>
<?php endif; ?>

<?php if ($session->isThereAdminSession() && ($session->get('user_rank') && $session->get('user_rank') >= 60)): ?>
    <script>
        function post_delete(url) {
            Swal.fire({
                title: 'Silmek istiyormusunuz ?',
                text: "Silmek istediğinize emin misiniz ?",
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Evet SİL !',
                cancelButtonText: 'Vazgeç',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.value) {
                    if (result.value) {
                        window.location = url;
                    }
                }
            });
        }

        function post_edit(url) {
            window.location = url;
        }
    </script>
<?php endif; ?>

<!-- REQUIRED SCRIPTS -->

<!-- Bootstrap -->
<script src="<?php echo $system->adminPublicUrl("plugins/bootstrap/js/bootstrap.bundle.min.js"); ?>"></script>
<script src="<?php echo $system->adminPublicUrl("plugins/ekko-lightbox/ekko-lightbox.min.js"); ?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo $system->adminPublicUrl("plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo $system->adminPublicUrl("dist/js/adminlte.js"); ?>"></script>
<script src="<?php echo $system->adminPublicUrl("plugins/sweetalert2/sweetalert2.min.js"); ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $system->adminPublicUrl("dist/js/demo.js"); ?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo $system->adminPublicUrl("dist/js/pages/dashboard2.js"); ?>"></script>

<!-- Page Custom JS -->
<?php
if (!empty($customJs)) {
	foreach ($customJs as $js) {
		?>
        <script src="<?php echo $system->adminPublicUrl($js); ?>"></script>
		<?php
	}
}
?>

<script src="<?php echo $system->adminPublicUrl("dist/js/custom.js"); ?>"></script>
</body>
</html>
