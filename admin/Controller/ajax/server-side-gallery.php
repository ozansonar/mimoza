<?php
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */


// DB table to use
$table = 'gallery';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
    array( 'db' => 'lang', 'dt' => 'lang' ),
    array( 'db' => 'deleted', 'dt' => 'deleted' ),
    array(
        'db'        => 'name',
        'dt'        => 'name',
        'formatter' => function($d, $row ) {
            global $functions;
            return $functions->textModal($row["name"], 20);
        }
    ),
    array(
        'db'        => 'img',
        'dt'        => 'img',
        'formatter' => function($d, $row ) {
            global $constants;
            $exportHtml = null;
            if(!empty($row["img"]) && file_exists($constants::fileTypePath["gallery"]["full_path"] . $row["img"])){
                $exportHtml .= '
                    <a href="'.$constants::fileTypePath["gallery"]["url"] . $row["img"].'" data-toggle="lightbox" data-title="'.$row["name"].'" class="color-unset">
                        <img src="'.$constants::fileTypePath["gallery"]["url"] . $row["img"].'" alt="" class="table-list-img">
                    </a>
                    ';
            }
            return $exportHtml;
        }
    ),
    array(
        'db'        => 'type',
        'dt'        => 'type',
        'formatter' => function($d, $row ) {
            global $constants;
            return $constants::systemGalleryTypes[$row["type"]]["view_text"] ?? null;
        }
    ),
    array(
        'db'        => 'created_at',
        'dt'        => 'created_at',
        'formatter' => function($d, $row ) {
            global $functions;
            return $functions->dateTimeConvertTr($row["created_at"]);
        }
    ),

    array(
        'db'        => 'status',
        'dt'        => 'status',
        'formatter' => static function($d, $row ) {
            global $constants;
            return '<span class="'.$constants::systemStatus[$row["status"]]["view_class"].'">'.$constants::systemStatus[$row["status"]]["view_text"].'</span>';
        }
    ),
    array(
        'db'        => 'id',
        'dt'        => 'settings',
        'formatter' => static function($d, $row ) {
            $pageRoleKey = "gallery";
            $pageAddRoleKey = "gallery-settings";
            global $session,$constants,$system;
            $exportButton = null;
            if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === true){
                $exportButton .= '
                    <a href="'.$system->adminUrl($pageAddRoleKey."?id=" . $row["id"]).'" class="btn btn-outline-success m-1">
                        <i class="fas fa-pencil-alt px-1"></i>
                        Düzenle
                    </a>';
            }
            if ($session->sessionRoleControl($pageRoleKey, $constants::deletePermissionKey) === true){
                $exportButton .= '
                    <button type="button" class="btn btn-outline-danger m-1 post_delete" data-delete-url="'.$system->adminUrl("ajax/delete-gallery?delete=" . $row["id"]).'">
                        <i class="fas fa-trash px-1"></i> Sil
                    </button>';
            }
            if($session->sessionRoleControl("gallery-image-upload", $constants::addPermissionKey) === true){
                $exportButton .= '
                <a href="'.$system->adminUrl("gallery-image-upload?id=" . $row["id"]).'" class="btn btn-outline-primary m-1">
                    <i class="fas fa-plus px-1"></i>
                    Resim Ekle
                </a>
                ';
            }
            if($session->sessionRoleControl("video-upload", $constants::addPermissionKey) === true){
                $exportButton .= '
                <a href="'.$system->adminUrl("gallery-video-upload?id=" . $row["id"]).'" class="btn btn-outline-warning m-1">
                    <i class="fab fa-youtube px-1"></i>
                    Video Ekle
                </a>
                ';
            }
            return $exportButton;
        }
    )
);

// SQL server connection information
$sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASS,
    'db'   => DB_NAME,
    'host' => DB_HOST
);

//esktra şartlar
$whereExtra = " lang='".$siteManager->defaultLanguage()->short_lang."' AND deleted=0 ";

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require $system->path("includes/Project/ssp.class.php");

try {
    echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns,$whereExtra), JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}