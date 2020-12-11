<?php

function dd($var){
    var_dump($var);
    die();
}

if (!empty($_POST)) {

    if (isset($_FILES['csv']['name'])) {
        $csv = stripslashes($_FILES['csv']['name']);
        $csv = md5(uniqid(rand(), true)) . '.' . 'csv';
        $uploadDir = "uploads/" . $csv;
        $copied = move_uploaded_file($_FILES['csv']['tmp_name'], $uploadDir);


    $fp = fopen('file.csv', 'w');
    $headers = array("Time","Type","Ref","Amount","MSISDN","Use","FirstName","MiddleName","LastName","BillRefNumber","BusinessShortCode","OrgAccountBalance","Wallet","Transaction");
    fputcsv($fp, $headers);
    $row = 0;

    if (($handle = fopen("uploads/$csv", "r")) !== FALSE) {

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row == 0) {
                $row++;
                continue;
            }
    /*       $num = count($data);
            for ($c=0; $c < $num; $c++) {
                echo $data[$c] . "<br />\n";
            }*/
            $getWallet = json_decode($data[6])->wallet;
            $getTransaction = json_decode($data[6])->transaction;
            $list = array (
                array($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $getWallet, $getTransaction)
            );

            // Populate the data
            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }

        }
        fclose($handle);
    }

    fclose($fp);

    }


    // We'll be outputting a CSV
    header('Content-Type: application/csv');

// It will be called data.csv
    header('Content-Disposition: attachment; filename="data.csv"');

// The CSV source is in original.csv
    readfile('file.csv');

    // delete file from uploads
    unlink("uploads/$csv");
}
