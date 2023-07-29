<?php

namespace Includes\Project;

use OS\MimozaCore\Database;
use PDO;
/**
 * Bu klastaki fonksiyonlar global olarak her tarafta kullanılabilir.
 *
 */
class Functions
{
    private Database $database;

    public function __construct(Database $database)
    {
        global $system;
        $this->system = $system;
        $this->database = $database;

    }
    public function getComments(int $postId,int $type): array
    {
        $query = $this->database::selectQuery('comment',array(
            'post_id' => $postId,
            'type' => $type,
            'status' => 1,
            'deleted' => 0,
        ),false,null,5,' id DESC');
        return $query;
    }

    /**
     * It's return header navbar
     *
     * @return string
     */
    public function getHeaderNavbar(): string
    {
        $navbar = "";
        /** @var Database $db */
        $data_query = $this->database::$db->prepare("SELECT * FROM menu WHERE lang=:lang AND show_type IN(1,3) AND menu_type=1 AND status=1 AND deleted=0 ORDER BY show_order ASC");
        $data_query->bindParam(":lang", $_SESSION["lang"], PDO::PARAM_STR);
        $data_query->execute();
        $data = $data_query->fetchAll(PDO::FETCH_OBJ);
        foreach ($data as $row) {
            //alt menu var mı
            $data_query = $this->database::$db->prepare("SELECT * FROM menu WHERE lang=:lang AND top_id=:top_id AND status=1 AND deleted=0 ORDER BY show_order ASC");
            $data_query->bindParam(":lang", $_SESSION["lang"], PDO::PARAM_STR);
            $data_query->bindParam(":top_id", $row->id, PDO::PARAM_INT);
            $data_query->execute();
            $sub_nav_data = $data_query->fetchAll(PDO::FETCH_OBJ);
            if (!empty($sub_nav_data)) {
                $navbar .= '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        ' . $row->name . '
                    </a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                foreach ($sub_nav_data as $sub) {
                    $navbar .= '<li>
                            <a class="dropdown-item" href="' . ((int)$sub->redirect === 1 ? $sub->redirect_link : $this->system->url($sub->link)) . '" target="' . ((int)$sub->redirect === 1 && (int)$sub->redirect_open_type === 1 ? "_blank" : "_self") . '">
                                ' . $sub->name . '
                            </a>
                        </li>';
                }
                $navbar .= '
                        </ul>
                    </li>';
            } else {
                $navbar .= '<li class="nav-item">
                    <a class="nav-link" href="' . ((int)$row->redirect === 1 ? $row->redirect_link : $this->system->url($row->link)) . '" target="' . ((int)$row->redirect === 1 && (int)$row->redirect_open_type === 1 ? "_blank" : "_self") . '">
                        ' . $row->name . '
                    </a>
                </li>';
            }
        }
        return $navbar;
    }

    /**
     * It's return footer menu
     *
     * @return string
     */
    public function getFooterNavbar(): string
    {
        /** @var Database $db */
        $query = $this->database::$db->prepare("SELECT * FROM menu WHERE show_type IN(2,3) AND lang=:lang AND menu_type=1 AND status=1 AND deleted=0 ORDER BY show_order ASC");
        $query->bindParam(":lang", $_SESSION["lang"], PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        $result = "";
        foreach ($data as $row) {
            $link = $row->link;
            if ((int)$row->redirect === 1) {
                $link = $row->redirect_link;
            }
            $result .= '<li class="mb-2"><i class="fas fa-angle-right"></i><a href="' . $link . '" target="' . ($row->redirect_open_type == 1 ? "_blank" : null) . '" class="link-hover-style-1 ms-1">' . $row->name . '</a></li>';
        }
        return $result;
    }
}