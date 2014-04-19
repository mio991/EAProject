<?php
class bb_events extends bb_base{
    function getData($id = null){
        if (false === authorize('user')) {
            return false;
        }

        if (!isset($_GET['event_id'])) {
            throw new Exception('no book_id given');
        }

        // check if user is customer is owner of the selected book

        $db = MySql :: getInstance();
        $sql_select_query = 'SELECT `user_service`.`id`, `user_service`.`user_id`, `services`.`name` as `title`, `user_service`.`comment`, `user_service`.`begin`, `user_service`.`end` FROM `user_service` LEFT JOIN `services` ON `services`.`id` = `user_service`.`service_id` WHERE `user_id`=%1$u ORDER by begin ASC;';
        $res = $db->queryf($sql_select_query, $_SESSION['auth']['user_id']);

        $row = $db->fetchAll($res);
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

        $db->query("INSERT INTO `eaproject`.`services` (`id`, `name`, `location`, `standby`, `erreichbar`) " .
                "VALUES (NULL, '" . $params->title . "', '', b'0', b'0');");

        // $db->query("INSERT INTO events (id, user_id, begin, end, title, comment) ".
        //               "VALUES (null, ".$_SESSION['auth']['user_id'].", '".$params->begin."','".$params->end."','".$params->title."','".$params->comment."')");

        if ($db->getLastId() == null) {
            throw new Exception('error while db insert');
        }

        $id = intval($db->getLastId());

        $db->query("INSERT INTO `eaproject`.`user_service` (`id`, `user_id`, `service_id`, `comment`, `begin`, `end`) " .
            " VALUES (NULL, ".$_SESSION['auth']['user_id'].", $id , '".$params->comment."', '".$params->begin."','".$params->end."');");

        // $db->query("INSERT INTO events (id, user_id, begin, end, title, comment) ".
        //               "VALUES (null, ".$_SESSION['auth']['user_id'].", '".$params->begin."','".$params->end."','".$params->title."','".$params->comment."')");

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

        if ($params->comment)
                $sql_update_str[] = sprintf("`comment` = '%s'", $db->escape($params->comment));
        if ($params->begin)
                $sql_update_str[] = sprintf("`begin` = '%s'", $db->escape($params->begin));
        if ($params->end)
                $sql_update_str[] = sprintf("`end` = '%s'", $db->escape($params->end));

        // // nothing to change
        // if (empty($sql_update_str)) {
        //     return true;
        // }
        //

        if (count($sql_update_str) != 0) {
            $sql_update_str = 'UPDATE `events` SET ' . implode(', ', $sql_update_str) . ' WHERE `id` = %1$u;';
            $db->queryf($sql_update_str, intval($params->id));

            // evaluate results
            $affected_rows = $db->getAffected();

            // if ($affected_rows == -1) {
            //     $err = 'Could not update media: ' . $params->id;
            //     throw new Exception($err);
            // }
        }




        $sql_update_str = array();

        if ($params->title)
        {
             //$sql_update_str = printf('SELECT `service_id` FROM `user_service` WHERE `id` = %1$u;', $params->id);

            $db = MySQL::getInstance();
            $res = $db->queryf('SELECT `service_id` FROM `user_service` WHERE `id` = %1$u;', $params->id);

            $row = $db->fetch($res);

            $db->free($res);

            // nothing to change
            // if (empty($sql_update_str)) {
            //     return true;
            // }

            $sql_update_str = 'UPDATE `services` SET `name` = "%1$s" WHERE `id` = %2$u;';

            $db->queryf($sql_update_str,$db->escape($params->title), intval($row['service_id']));

            // evaluate results
            $affected_rows = $db->getAffected();
            if ($affected_rows == -1) {
                $err = 'Could not update media: ' . $params->id;
                throw new Exception($err);
            }
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
        $db->queryf($sql_delete, 'user_service', $id, 1);

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