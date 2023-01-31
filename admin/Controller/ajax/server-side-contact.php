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
$table = 'contact_form';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
    array( 'db' => 'deleted', 'dt' => 'deleted' ),
    array( 'db' => 'name', 'dt' => 'name' ),
    array( 'db' => 'surname', 'dt' => 'surname' ),
    array( 'db' => 'reply_send_user_id', 'dt' => 'reply_send_user_id' ),
    array( 'db' => 'reply_send_date', 'dt' => 'reply_send_date' ),
    array(
        'db'        => 'email',
        'dt'        => 'email',
        'formatter' => function($d, $row ) {
            global $functions;
            $sendUserName = $row["name"] . " " . $row["surname"] . " (" . $row["email"] . ")";
            return $functions->textModal($sendUserName, 20);
        }
    ),
    array(
        'db'        => 'subject',
        'dt'        => 'subject',
        'formatter' => function($d, $row ) {
            global $functions;
            return $functions->textModal($row["subject"],40);
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
        'db'        => 'read_user',
        'dt'        => 'read_user',
        'formatter' => function($d, $row ) {
            global $functions;
            $html = '
                <span class="badge badge-'.((int)$row["read_user"] === 1 ? "success" : "danger").' ">
                    '.((int)$row["read_user"] === 1 ? "Okundu" : "Okunmadı").'
                </span>';
            if (!empty($row["reply_send_user_id"])){
                $html .= '<span class="badge badge-info mt-2">Cevaplandı: ('.$functions->dateTimeConvertTr($row["reply_send_date"]).')</span>';
            }
            return $html;
        }
    ),

    array(
        'db'        => 'id',
        'dt'        => 'settings',
        'formatter' => static function($d, $row ) {
            $pageRoleKey = "contact";
            $pageAddRoleKey = "contact-settings";
            global $session,$constants,$system;
            $exportButton = null;
            if ($session->sessionRoleControl($pageRoleKey, $constants::editPermissionKey) === true){
                $exportButton .= '
                    <a href="'.$system->adminUrl($pageAddRoleKey."?id=" . $row["id"]).'" class="btn btn-outline-success m-1">
                        <i class="fas fa-pencil-alt px-1"></i>
                        Detay
                    </a>';
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
$whereExtra = " deleted=0 ";

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require $system->path("includes/Project/ssp.class.php");

try {
    echo json_encode(SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns,$whereExtra), JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}