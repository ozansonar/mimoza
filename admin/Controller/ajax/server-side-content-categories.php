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
$table = 'content_categories';

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
        'db'        => 'title',
        'dt'        => 'title',
        'formatter' => function($d, $row ) {
            global $functions;
            return $functions->textModal($row["title"], 20);
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
        'db'        => 'img',
        'dt'        => 'img',
        'formatter' => function($d, $row ) {
            global $constants;
            $exportHtml = null;
            if(!empty($row["img"]) && file_exists($constants::fileTypePath["content_categories"]["full_path"] . $row["img"])){
                $exportHtml .= '
                    <a href="'.$constants::fileTypePath["content_categories"]["url"] . $row["img"].'" data-toggle="lightbox" data-title="'.$row["title"].'" class="color-unset">
                        <i class="fas fa-images"></i>
                    </a>
                    ';
            }
            return $exportHtml;
        }
    ),
    array(
        'db'        => 'show_type',
        'dt'        => 'show_type',
        'formatter' => function($d, $row ) {
            global $constants;
            return $constants::systemContentCategoriesShowTypes[$row["show_type"]]["view_text"];
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
            $pageRoleKey = "content-categories";
            $pageAddRoleKey = "content-categories-settings";
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
                    <button type="button" class="btn btn-outline-danger m-1 post_delete" data-delete-url="'.$system->adminUrl($pageRoleKey."?delete=" . $row["id"]).'">
                        <i class="fas fa-trash px-1"></i> Sil
                    </button>';
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