<?php

class Convert
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    //Save the results of currency conversions to the database along with information about the source, target and converted amounts.

    public function get_converted_amount($data)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $source_amount = intval($data['source_amount']);
            $source_currency = floatval($data['source_currency']);
            $target_currency = floatval($data['target_currency']);
            $source_code = $data['source_code'];
            $target_code = $data['target_code'];
            if ($data['source_currency'] === "select") {

                return '<b style="color:red">Input values are not correct.</b>';
            } elseif ($data['target_currency'] === "select") {

                return '<b style="color:red">Input values are not correct.</b>';
            } else {
                if ($target_currency > 0) {

                    $sql_source = "SELECT * FROM rates WHERE code = '" . $source_code . "'";
                    $sql_target = "SELECT * FROM rates WHERE code = '" . $target_code . "'";
                    $result_source = $this->db->query($sql_source);
                    $result_target = $this->db->query($sql_target);

                    $row_bid = $result_source->fetch_assoc();
                    $row_ask = $result_target->fetch_assoc();
                    $source_code_ = $row_bid['currency'] . '(' . $source_code . ')';
                    $target_code_ = $row_ask['currency'] . '(' . $target_code . ')';

                    $convert = $source_amount * $source_currency / $target_currency;
                    $sql = "INSERT INTO converted (source_amount, source_currency, target_currency, target_amount, source_code, target_code) VALUES ('" . $source_amount . "', '" . $source_currency . "', '" . $target_currency . "', $convert, '" . $source_code_ . "', '" . $target_code_ . "')";

                    $this->db->query($sql);
                }
            }
        }
    }
}
