<?php
    require '../application_functions.php';


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Hamdulilah Mobile Payment Services</title>

    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/colors/default.css" id="theme" rel="stylesheet">
</head>

<body class="fix-header">
    <div id="wrapper">
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav">
                <div class="sidebar-head">
                    <h3><span class="hide-menu">Hamdulilah Mobile Payment Services</span></h3>
                </div>
            </div>
        </div>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Dashboard</h4> </div>
                </div>
                <?php
                    $ussd = new ApplicationFunctions();
                    $transcations = $ussd->transactionCount();
                    //echo "$transcations";
                ?>
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Number Of Transaction</h3>
                            <ul class="list-inline">
                                <li class="text-center"><span class="counter text-success"><?php echo "$transcations";  ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Number of complete transfer</h3>
                            <ul class="list-inline">
                                <li class="text-center"> <span class="counter text-purple"><?php $count = $ussd->countOfCompleteTransfers(); echo "$count";  ?>0</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Current Month Trasactions</h3>
                            <ul class="list-inline">
                                <li class="text-center"><span class="counter text-info"><?php $currMonth =$ussd->getCurrentMonthTransactions(); echo "$currMonth"; ?></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="white-box">
                            <div class="col-md-3 col-sm-4 col-xs-6 pull-right">
                                <select class="form-control pull-right row b-none">
                                    <option value="January">January</option>
                                    <option value="February">February</option>
                                    <option value="March">March</option>
                                    <option value="April">April</option>
                                    <option value="May">May</option>
                                    <option value="June">June</option>
                                    <option value="July">July</option>
                                    <option value="August">August</option>
                                    <option value="September">September</option>
                                    <option value="October">October</option>
                                    <option value="November">November</option>
                                    <option value="December">December</option>
                                </select>
                            </div>
                            <h3 class="box-title">Recent Money Transfer</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>SENDER</th>
                                            <th>RECIPIENT</th>
                                            <th>AMOUNT</th>
                                            <th>TRANSACTION DATE</th>
                                            <th>TRANSACTION ID</th>
                                            <th>DEBIT_STATUS</th>
                                            <th>CREDIT_STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                            $ussd = new ApplicationFunctions();
                                            $transactions = $ussd->getTransactions();
                                            //print_r($transactions);
                                            //var_dump($transactions);
                                            //echo $transactions['id'];
                                            //var_dump($transactions['id']);
                                            $i = 1;
                                            if($transactions != null){
                                                foreach ($transactions as $record) {
                                                    echo '<tr><td>'.$i.'</td>
                                                        <td class="txt-oflo">'.$record["sender_msisdn"].'</td>
                                                        <td class="txt-oflo">'.$record["recipient_msisdn"].'</td>
                                                        <td class="txt-oflo">'.$record['amount'].'</td>
                                                        <td class="txt-oflo">'.$record['timestamp'].'</td>
                                                        <td class="txt-oflo">'.$record['transactionID'].'</td>
                                                        <td class="txt-oflo">'.$record['debit_status'].'</td>
                                                        <td class="txt-oflo">'.$record['credit_status'].'</td></tr>';
                                                        $i++;
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2017 &copy; Hamdulilah Mobile Payment Services</footer>
        </div>

    </div>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>
