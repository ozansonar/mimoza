<section class="content">
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
            <p><b>Mail Konusu: </b> <?php echo $data->mailing->subject; ?></p>
            <p><b>Mail İçeriği: </b> <?php echo $data->mailing->text; ?></p>
			<?php if (isset($attachment_array) && !empty($attachment_array)): ?>
                <p>
                    <b>Mailde Gidecek Ek(ler): </b><br>
					<?php foreach ($attachment_array as $attachment_row): ?>
                        <a href="<?php echo $attachment_row["url"]; ?>"
                           target="_blank"><?php echo $attachment_row["name"]; ?></a>
                        <br>
					<?php endforeach; ?>
                </p>
			<?php endif; ?>

            <div class="col-12 py-2 p-0">
				<?php echo $functions->csrfToken(); ?>
				<?php if ((int)$data->mailing->completed === 3): ?>
                    <p class="alert alert-info mailing_start mailing-request-btn" role="alert">
                        Mailleri göndermek için tıklayın.. ->
                        <button type="button" class="btn btn-success"
                                onclick="send_alert('Dikkat','Mailingi başlatmak istediğinize emin misiniz?','info','<?php echo $data->mailing->id; ?>')">
                            Mail Gönder
                        </button>
                    </p>
				<?php elseif (isset($maling_user_array[3]) || isset($maling_user_array[2]) || (int)$data->mailing->completed === 2): ?>
                    <p class="alert alert-info mailing-request-btn" role="alert">
                        Mail gönderilemeyen adresler mevcut lütfen tekrar deneyin.
                        <button type="button" class="btn btn-warning"
                                onclick="send_alert('Dikkat','Yarıda kalan mailingi devam ettirmek istediğinize emin misiniz?','info','<?php echo $data->id; ?>')">
                            Tekrar Dene
                        </button>
                    </p>
				<?php else : ?>
                    <div class="alert alert-info mailing_completed" role="alert">
                        Mail gönderimi tamamlanmış.
                        <span class="font-weight-bold">
                            Tamamlanma Tarihi <?php echo $data->mailing->completed_date; ?>
                        </span>
                    </div>
				<?php endif ?>
            </div>

            <div class="col-12 px-0">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-gonderilmis">
                    Gönderilmiş Mailler
                </button>
                <div class="modal fade" id="modal-gonderilmis">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Gönderilmiş Mailler</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
								<?php if (isset($data->mailingUsers[1])): ?>
                                    <p>Toplam <b><?php echo count($data->mailingUsers[1]) ?></b>
                                        kişiye mail gönderilmiş.
                                    </p>
									<?php
									$counter = 1; ?>
									<?php foreach ($data->mailingUsers[1] as $gonderilmis_user): ?>
                                        <p><?php echo $counter . "." . $gonderilmis_user->email . " - " . $gonderilmis_user->name . " " . $gonderilmis_user->surname; ?></p>
										<?php $counter++; ?>
									<?php endforeach ?>
								<?php else: ?>
                                    <p>Gönderilmiş mail bulunamadı.</p>
								<?php endif; ?>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-gonderilememis">
                    Gönderilememiş Mailler
                </button>
                <div class="modal fade" id="modal-gonderilememis">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Gönderilememiş Mailler</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="mailSendGonderilmeyen">
								<?php if (isset($data->mailingUsers[2])): ?>
                                    <p>Toplam <b><?php echo count($data->mailingUsers[2]) ?></b>
                                        kişiye mail gönderilememiş.
                                    </p>
									<?php $counter = 1; ?>
									<?php foreach ($data->mailingUsers[2] as $gonderilmis_user) : ?>
                                        <p>
											<?php echo $counter . "." . $gonderilmis_user->email . " - " . $gonderilmis_user->name . " " . $gonderilmis_user->surname; ?>
                                        </p>
										<?php $counter++; ?>
									<?php endforeach ?>
								<?php else: ?>
                                    <p>Gönderilememiş mail bulunamadı.</p>
								<?php endif; ?>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-bekleyen">
                    Bekleyen Mailler
                </button>
                <div class="modal fade" id="modal-bekleyen">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Bekleyen Mailler</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="mailBekleyenMailler">
								<?php if (isset($data->mailingUsers[3])): ?>
                                    <p>Toplam <b><?php echo count($data->mailingUsers[3]) ?></b> mail gönderilmeyi
                                        bekliyor.</p>
									<?php $counter = 1; ?>
									<?php foreach ($data->mailingUsers[3] as $gonderilmis_user): ?>
                                        <p>
											<?php echo $counter . "." . $gonderilmis_user->email . " - " . $gonderilmis_user->name . " " . $gonderilmis_user->surname; ?>
                                        </p>
										<?php $counter++; ?>
									<?php endforeach ?>
								<?php else: ?>
                                    <p>Bekleyen mail bulunamadı.</p>
								<?php endif; ?>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- mail gönderim işlemlerinin işlendiği modal -->
<div class="modal fade" id="mailIslemleri" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mail Gönderim İşlemleri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mailIslemleriBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">KAPAT</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="mail_count" value="0">
<input type="hidden" id="success_send" value="0">
<input type="hidden" id="error_send" value="0">
<input type="hidden" id="wait_mail" value="0">
<script>
    function send_alert(title = "Dikkat", text = "Bu işlemi yapmak istediğinize emin misiniz?", icon = "warning", mailing_id = 0) {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonText: 'Evet',
            cancelButtonText: 'Hayır',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.value) {
                if (result.value) {
                    $(".mailIslemleriBody").html("");
                    $("#mail_count").val("0");
                    mail_send(mailing_id);
                }
            }
        });
    }

    function mail_send(mailing_id) {
        const token = $("#token").val();
        $.ajax({
            type: 'POST',
            url: "ajax/mailing-start",
            data: {"mailing_id": mailing_id, "token": token},
            success: function (response) {
                let islem_mail;
                console.log(response);
                if (response.result) {
                    $("#mailIslemleri").modal();
                    if (response.ok_send || response.no_send || response.error_mail) {
                        const m_count = parseInt($("#mail_count").val()) + 1;
                        $("#mail_count").val(m_count);
                        let islem_icon = "";
                        let islem_text = "";
                        if (response.ok_send) {
                            islem_mail = response.ok_email;
                            islem_icon = '<i class="fas fa-check-circle text-success"></i>';
                            islem_text = "<b class='text-success'>" + m_count + "-Gönderildi: </b>";
                        } else if (response.no_send) {
                            islem_mail = response.no_email;
                            islem_icon = '<i class="fas fa-exclamation-circle text-danger"></i>';
                            islem_text = "<b class='text-danger'>" + m_count + "-Gönderilmedi: </b>";
                        } else if (response.error_mail) {
                            islem_mail = response.error_mail;
                            islem_icon = '<i class="fas fa-exclamation-triangle text-danger"></i>';
                            islem_text = "<b class='text-danger'>" + m_count + "-Hatalı e-mail listeden silindi: </b>";
                        }
                        $(".mailIslemleriBody").append("<p>" + islem_text + islem_mail + " " + islem_icon + "</p>");

                        $("#modal-gonderilmis .modal-body").html("<p>Kayıt bulunamadı.</p>");
                        $("#modal-gonderilememis .modal-body").html("<p>Kayıt bulunamadı.</p>");
                        $("#modal-bekleyen .modal-body").html("<p>Kayıt bulunamadı.</p>");

                        //başarılı giden mailler
                        if (response.success_send) {
                            $("#modal-gonderilmis .modal-body").html("");
                            $.each(response.success_send, function (index, value) {
                                const success_send = parseInt($("#success_send").val()) + 1;
                                $("#success_send").val(success_send);
                                $("#modal-gonderilmis .modal-body").append("<p><b>" + success_send + "</b>-" + value + " <i class='fas fa-check-circle text-success'></i></p>");
                            });
                        }

                        //hatalı giden mailler
                        if (response.error_send) {
                            $("#modal-gonderilememis .modal-body").html("");
                            $.each(response.error_send, function (index, value) {
                                const error_send = parseInt($("#error_send").val()) + 1;
                                $("#error_send").val(error_send);
                                $("#modal-gonderilememis .modal-body").append("<p><b>" + error_send + "</b>-" + value + " <i class='fas fa-exclamation-circle text-danger'></i></p>");
                            });
                        }

                        //bekleyen mailler
                        if (response.wait_mail) {
                            $("#modal-bekleyen .modal-body").html("");
                            $.each(response.wait_mail, function (index, value) {
                                const wait_mail = parseInt($("#wait_mail").val()) + 1;
                                $("#wait_mail").val(wait_mail);
                                $("#modal-bekleyen .modal-body").append("<p><b>" + wait_mail + "</b>-" + value + " <i class='fas fa-hourglass-start text-info'></i></p>");
                            });
                        }
                    }
                    if (response.repeat) {
                        mail_send(mailing_id);
                    }
                    if (response.message) {
                        $.each(response.message, function (index, value) {
                            $(".mailIslemleriBody").append("<p>" + value + "</p>");
                        });
                    }
                    if (response.completed) {
                        $(".mailing-request-btn").hide();
                    }
                }
                if (response.reply) {
                    AlertMessage("error", "HATA", response.reply, "TAMAM", 0);
                }
            },
            dataType: 'json'
        });
    }
</script>