<?php

namespace Includes\Project;

use OS\MimozaCore\Database;
/**
 * Bu klastaki fonksiyonlar global olarak her tarafta kullanılabilir.
 *
 */
class Functions
{
    private Database $database;

    public function __construct(Database $database)
    {
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
}