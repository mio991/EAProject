<?php
class bb_transaction extends bb_base{
    function getData($id = null){
        if (false === authorize('user')) {
            return false;
        }

        if (!isset($_GET['book_id'])) {
            throw new Exception('no book_id given');
        }

        // check if users customer is owner of the selected book

        $db = MySql :: getInstance();
        $sql_select_query = 'SELECT * FROM `transactions` WHERE `book_id`=%1$u AND `date` LIKE "%2$u-%3$s-%%"  ORDER by id ASC;';
        $res = $db->queryf($sql_select_query, $_GET['book_id'],  $_GET['filter_year'],  $_GET['filter_month']);

        $row = $db->fetchAll($res);
        $db->free($res);

        // get all years an months with payments
        $year_list = array();
        $sql_select_years = 'SELECT date FROM `transactions` WHERE `book_id`=%1$u ORDER by id ASC;';
        $res_years = $db->queryf($sql_select_years, $_GET['book_id']);
        $row_years = $db->fetchAll($res_years);
        $db->free($res_years);

        foreach ($row_years AS $k => $v){
            $split_date = explode('-', $v['date']);
            $year_list[$split_date[0]][$split_date[1]] = true;
        }

        return array(
            'transactions' => $row,
            'years' => $year_list
        );
    }

    function create($params) {
        if (false === authorize('user')) {
            return false;
        }

        // check if users customer is owner of the selected book
        $current_datetime = date('Y.m.d H:i:m',time());
        $current_date = date('Y-m-d',time());
        $db = MySql :: getInstance();

        $db->query("INSERT INTO transactions (id, book_id, user_id, create_datetime, receipt, cost, stock, contra_account, proof_nr, date, vat_rate, description, sequential_number) ".
                      "VALUES (null, ".$params->book_id.", 1,'".$current_datetime."',".$params->receipt.",".$params->cost.",".$params->stock.",'".$params->contra_account."','".$params->proof_nr."','".$params->date."',".$params->vat_rate.", '".$params->description."', '".$params->sequential_number."')");

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
        if ($params->news_text)
                $sql_update_str[] = sprintf("`news_text` = '%s'", $db->escape($params->news_text));
        // if ($params->create_date)
        //         $sql_update_str[] = sprintf("`create_date` = '%s'", $db->escape($params->create_date));

        // nothing to change
        if (empty($sql_update_str)) {
            return true;
        }

        $sql_update_str = 'UPDATE `news` SET ' . implode(', ', $sql_update_str) . ' WHERE `id` = %1$u;';
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