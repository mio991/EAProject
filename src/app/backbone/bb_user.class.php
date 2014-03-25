<?php
class bb_user extends bb_base{
    function getData($id = null){
        if (false === authorize('user')) {
            return false;
        }

        $db = MySql :: getInstance();
        $sql_select_query = 'SELECT * FROM `books` WHERE `id`=%1$u ORDER by id ASC;';
        $res = $db->queryf($sql_select_query, $id);
        $row = $db->fetchAll($res);
        $db->free($res);

        return $row;
    }

    function create($params) {
        if (false === authorize('superuser')) {
            return false;
        }

        //var_export($params);
        // $current_datetime = date('Y.m.d H:i:m',time());
        // $current_date = date('Y-m-d',time());
        $db = MySql :: getInstance();

        $db->query("INSERT INTO books (id, customer_id, title, initial_balance) ".
                      "VALUES (null, ".$_SESSION['auth']['customer_id'].",'".$params->title."', ".intval($params->initial_balance).");");

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

        $db = MySQL::getInstance();
        $sql_update_str = array();

        if ($params->title)
                $sql_update_str[] = sprintf("`title` = '%s'", $db->escape($params->title));
        if ($params->news_text)
                $sql_update_str[] = sprintf("`initial_balance` = %u", $db->escape($params->initial_balance));

        // nothing to change
        if (empty($sql_update_str)) {
            return true;
        }

        $sql_update_str = 'UPDATE `books` SET ' . implode(', ', $sql_update_str) . ' WHERE `id` = %1$u;';
        $db->queryf($sql_update_str, $params->id);

        // evaluate results
        $affected_rows = $db->getAffected();
        if ($affected_rows == -1) {
            $err = 'Could not update book: ' . $params->id;
            throw new Exception($err);
        }

        return array(
            'id' => intval($params->id)
        );
    }

    function delete($id){
        if (false === authorize('superuser')) {
            return false;
        }

        $db = MySQL::getInstance();
        $sql_delete = 'DELETE FROM %1$s WHERE `%2$s` = %3$u LIMIT %4$u;';
        $db->queryf($sql_delete, 'books', 'id', $id, 1);

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