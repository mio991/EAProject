<?php
class bb_user extends bb_base{
    function getData($id = null){
        if (false === authorize('user')) {
            return false;
        }

        if (!isset($id)) {
            throw new Exception('no id given');
        }

        // check if user is customer is owner of the selected book

        $db = MySql :: getInstance();
        $sql_select_query = 'SELECT * FROM `user` WHERE `id`=%1$u ORDER by id ASC;';
        $res = $db->queryf($sql_select_query, $id);

        $row = $db->fetch($res);
        $db->free($res);

        return $row;
    }

    function create($params) {
        if (false === authorize('user')) {
            return false;
        }

        // check if users customer is owner of the selected book
        $current_datetime = date('Y.m.d H:i:m',time());
        $current_date = date('Y-m-d',time());
        $db = MySql :: getInstance();

        $db->query("INSERT INTO events (id, user_id, begin, end, title, comment) ".
                      "VALUES (null, ".$params->user_id.", '".$params->begin."','".$params->end."','".$params->title."','".$params->comment."')");

        if ($db->getLastId() == null) {
            throw new Exception('error while db insert');
        }

        return array(
            'id' => intval($db->getLastId())
        );
    }

    function update($params){
        if (false === authorize('superuser')) {
            return false;
        }

        // check if users customer is owner of the selected book

        $db = MySQL::getInstance();
        $sql_update_str = array();

        if ($params->title)
                $sql_update_str[] = sprintf("`title` = '%s'", $db->escape($params->title));
        if ($params->comment)
                $sql_update_str[] = sprintf("`comment` = '%s'", $db->escape($params->comment));
        if ($params->begin)
                $sql_update_str[] = sprintf("`comment` = '%s'", $db->escape($params->begin));
        if ($params->end)
                $sql_update_str[] = sprintf("`comment` = '%s'", $db->escape($params->end));

        // nothing to change
        if (empty($sql_update_str)) {
            return true;
        }

        $sql_update_str = 'UPDATE `events` SET ' . implode(', ', $sql_update_str) . ' WHERE `id` = %1$u;';
        $db->queryf($sql_update_str, $params->id);

        // evaluate results
        $affected_rows = $db->getAffected();
        if ($affected_rows == -1) {
            $err = 'Could not update media: ' . $params->id;
            throw new Exception($err);
        }

        return array(
            'id' => $params->id
        );
    }

    function delete($id){
        if (false === authorize('superuser')) {
            return false;
        }

        // check if users customer is owner of the selected book

        $db = MySQL::getInstance();

        $sql_delete = 'DELETE FROM %1$s WHERE `id` = %2$u LIMIT %3$u;';
        $db->queryf($sql_delete, 'transactions', $id, 1);

        $affected = $db->getAffected();

        if ($affected >= 1) {
            return true;
        }

        if ($affected == 0) {
            $info = sprintf('Could not delete non exsiting entry with id "%s" from table "%s".', $id, $table_name);
            throw new Exception($info);
            return true;
        }

        if ($affected == -1) {
            $err = sprintf('Error deleting entry with id "%s" from table "%s".', $id, $table_name);
            throw new Exception($err);
        }
    }
}
?>