<?php
include 'Db.php';
include 'Rate.php';
include 'Convert.php';

$db = new Db;
$db->migrate_tables();

$rate = new Rate($db);
$convert = new Convert($db);
$error = $convert->get_converted_amount($_POST);
$rate->get_exchange_rates('http://api.nbp.pl/api/exchangerates/tables/c/');

?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>

<body>
<div class="container-fluid">
    <div class="row first" style="margin-top:50px">
        <div class="col-12 text-center">
            <h3> Cryptocurrency Converter Calculator </h3>
            <p> <?php echo $error; ?> </p>
        </div>
    </div>
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
            <div style="width: 100%;padding:20px;background:#e9e9e9;">
                <form method="post">
                    <div class="text-center mb-2">
                        <label><b>Amount:</b></label>
                        <input type="number" name="source_amount" id="source_amount">
                    </div>
                    <div class="text-center mb-2">
                        <select name="source_currency" id="source_currency" onchange="handleSelectChange()">
                            <?php $rate->show_bid_currency(); ?>
                            <!------------  display currency code types ------------------>
                        </select>=>
                        <input type="text" id="source_code" name="source_code" style="display:none;"/>

                        <select name="target_currency" id="target_currency" onchange="handleSelectChange_()">
                            <?php $rate->show_ask_currency(); ?>
                            <!------------  display currency code types ------------------>
                        </select>
                        <input type="text" id="target_code" name="target_code" style="display:none;"/>
                    </div>
                    <div class="text-center">

                        <label><b>Result:</b></label>
                        <input type='text' disabled id='result_content'>
                        <button class="btn btn-info" id='result'>caculate</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-4"></div>
    </div>
    <div class="row">
        <div class="col-6" style='padding:20px;width:auto'>


            <br><br>

            <p><b>You can see exchange rates from the api database in right position.</b></p>
            <p><b>And then you can convert each other currency to basied exchange rates and you can see the conversion
                    results in the following table</b></p>
            <!------------  display converted currency code information ------------------>
            <div style='display:flex'>
                <div id='convert_content' style='margin-right:10px'>
                    <?php $rate->display_converted_add(); ?>
                </div>

            </div>
        </div>
        <div class="col-3" style='padding:20px;width:auto;margin-top:30px;'>

            <div id='rate_content'>
                <h3>Exchange rates of the National Bank of Poland</h3>
                <?php $rate->show_exchange_rates(); ?>
            </div>

        </div>
        <div class="col-3" style="width:auto;">
        </div>
    </div>
</div>
<script>
    function handleSelectChange() {
        // Retrieve the selected value and perform actions based on it

        var e = document.getElementById("source_currency");
        var text = e.options[e.selectedIndex].text;
        $('#source_code').val(text);
    }

    function handleSelectChange_() {
        // Retrieve the selected value and perform actions based on it

        var e = document.getElementById("target_currency");
        var text = e.options[e.selectedIndex].text;
        $('#target_code').val(text);
    }

    $(document).ready(function () {

        $('#result').click(function (e) {
            if ($('input[name=source_amount]').val()) {
                e.preventDefault();
                var source_currency = $('select[name=source_currency]').val();
                var source_amount = $('input[name=source_amount]').val();
                var target_currency = $('select[name=target_currency]').val();
                var result = source_amount * source_currency / target_currency;
                $('#result_content').val(result);
            } else {
                alert('Enter amount');
            }
        });

    });
</script>
</body>

</html>