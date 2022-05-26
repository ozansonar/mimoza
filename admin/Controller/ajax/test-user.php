<?php
if(isset($_POST["count"]) && is_numeric($_POST["count"])){
    $count = $functions->cleanPostInt("count");
    $html = '<div class="border my-1 p-2 test-user-[count]">
                <div class="form-group">
                    <label for="id_[count]_email">E-posta</label>
                    <input type="email" class="form-control" name="user_test[[count]][email]" id="id_[count]_email">
                </div>
                <div class="form-group">
                    <label for="id_1_name">Ad</label>
                    <input type="text" class="form-control" name="user_test[[count]][name]" id="id_[count]_name">
                </div>
                <div class="form-group">
                    <label for="id_1_surname">Soyad</label>
                    <input type="text" class="form-control" name="user_test[[count]][surname]" id="id_[count]_surname">
                </div>
                <div class="form-group text-right">
                    <button class="btn btn-danger test-user-delete" type="button">SİL</button>
                </div>
            </div>';
    $html = str_replace("[count]",$count,$html);
    $message["success"][] = "Başarıyla eklendi.";
    $message["html"] = $html;
}