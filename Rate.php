<?php

class Rate
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_exchange_rates($url)
    {
        $data = file_get_contents($url);

        if (!$data) {
            echo '<b style="color:red">Can not get exchange rates from NBP API.</b>';
            die();
        } else {
            $array = json_decode($data, true);
            $sql = "DELETE FROM rates";
            $this->db->query($sql);
            foreach ($array as $item) {
                $no = $item['no'];
                $trading_date = $item['tradingDate'];
                $effective_date = $item['effectiveDate'];
                $rates = $item['rates'];
                foreach ($rates as $rate) {
                    $sql = "INSERT INTO rates (no, tradingDate, effectiveDate, currency, code, bid, ask) VALUES ('$no', '$trading_date', '$effective_date', '{$rate['currency']}', '{$rate['code']}', '{$rate['bid']}', '{$rate['ask']}')";

                    $this->db->query($sql);
                }
            }
        }
    }

    public function show_exchange_rates()
    {
        $sql = "SELECT * FROM rates";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            // Loop through the result set and output the data
            $table = "<table class='table table-hover table-striped table-bordered'><thead style='background-color: #152e52 !important;color: #fff !important;'>";
            $table .= "<tr><th>Currency</th><th>Currency Code</th><th>Exchange Bid</th><th>Exchange Ask</th></tr></thead><tbody>";
            while ($row = $result->fetch_assoc()) {
                $table .= "<tr><td>" . $row['currency'] . "</td><td>" . $row['code'] . "</td><td>" . $row['bid'] . "</td><td>" . $row['ask'] . "</td></tr>";
            }
            $table .= "</tbody></table>";
            echo $table;

        } else {
            echo "No results found.";
        }
    }

    public function show_bid_currency()
    {
        $sql = "SELECT * FROM rates";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            // Loop through the result set and output the data
            $html = '<option value="select">select source currency</option>';
            while ($row = $result->fetch_assoc()) {
                $html .= "<option value=" . $row['bid'] . ">" . $row['code'] . "</option>";
            }

            echo $html;


        } else {
            echo "No results found.";
        }
    }

    public function show_ask_currency()
    {
        $sql = "SELECT * FROM rates";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            // Loop through the result set and output the data
            $html = '<option value="select">select target currency</option>';
            while ($row = $result->fetch_assoc()) {
                $html .= "<option value=" . $row['ask'] . ">" . $row['code'] . "</option>";
            }

            echo $html;


        } else {
            echo "No results found.";
        }
    }

    public function display_converted_add()
    {

        $sql = "SELECT * FROM converted ORDER BY reg_date DESC";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            // Loop through the result set and output the data
            $table = "<table class='table table-hover table-striped table-bordered'><thead style='background-color: #152e52 !important;color: #fff !important;'>";
            $table .= "<tr><th>Source Code</th><th>Source Currency</th><th>Source Amount</th><th>Target Code</th><th>Target Currency</th><th>Target Amount</th><th>Created Date</th></tr></thead><tbody>";
            while ($row = $result->fetch_assoc()) {
                $table .= "<tr><td>" . $row['source_code'] . "</td><td>" . $row['source_currency'] . "</td><td>" . $row['source_amount'] . "</td><td>" . $row['target_code'] . "</td><td>" . $row['target_currency'] . "</td><td>" . $row['target_amount'] . "</td><td>" . $row['reg_date'] . "</td></tr>";
            }
            $table .= "</tbody></table>";
            echo $table;

        } else {
            echo "No results found.";
        }
    }

}
