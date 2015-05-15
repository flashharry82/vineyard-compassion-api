<?php

    require '../assets/config.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sak'])) {
        header('Content-Type: application/json; charset=utf-8');
        $checkRequirements = checkRequirements('POST');
        if(!$checkRequirements) {
            echo sendMessage($_POST['sak'], $_POST['to'], $_POST['msg']);
        }
        else
        {
            echo $checkRequirements;
        }
    } elseif($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['sak'])) {
        header('Content-Type: application/json; charset=utf-8');
        $checkRequirements = checkRequirements('GET');
        if(!$checkRequirements) {

            echo sendMessage($_GET['sak'], $_GET['to'], $_GET['msg']);
        }
        else
        {
            echo $checkRequirements;
        }

    } else {
        notAllow();
    }

    function notAllow() {
        $header = $_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden";
        header($header);
        die($header);
    }

    function checkRequirements($requestMethod) {
        $return = null;


        switch($requestMethod) {
            case 'POST':
                if(!isset($_POST['msg']) || empty($_POST['msg'])) {
                    $return = '{ "Result":"FAIL", "ErrorCode":"02", "ErrorMessage":"Missing parameter: msg"}';
                    break;
                }

                if(!isset($_POST['to']) || empty($_POST['to'])) {
                    $return = '{ "Result":"FAIL", "ErrorCode":"03", "ErrorMessage":"Missing parameter: to"}';
                    break;
                }

                break;

            case 'GET':
                if(!isset($_GET['msg']) || empty($_GET['msg'])) {
                    $return = '{ "Result":"FAIL", "ErrorCode":"02", "ErrorMessage":"Missing parameter: msg"}';
                    break;
                }

                if(!isset($_GET['to']) || empty($_GET['to'])) {
                    $return = '{ "Result":"FAIL", "ErrorCode":"03", "ErrorMessage":"Missing parameter: to"}';
                    break;
                }

                break;
        }



        return $return;
    }

    function sendMessage($key, $to, $msg) {

        $msg = urldecode($msg);

        $link = Db::open();
        $stmt = $link -> prepare("SELECT Businesses.BusinessID, Businesses.BusinessName, (PurchasedCredits - CreditsUsed) AS NumberOfCredits FROM smsstatistics LEFT JOIN Businesses ON Businesses.BusinessID = smsstatistics.BusinessID WHERE Businesses.SenderKey = ?");
        $stmt -> bind_param('s', $key);
        $stmt -> bind_result($businessID, $businessName, $credits);
        $stmt -> execute();
        $stmt -> store_result();
        $stmt -> fetch();
        $stmt -> close();
        $link -> close();

        if($credits === null) {
            $result = '{ "Result" : "FAIL", "ErrorCode":"01", "ErrorMessage":"Invalid SAK" }';
        } elseif($credits == '0') {
            $result = '{ "Result" : "FAIL", "ErrorCode":"04", "ErrorMessage":"No available credits" }';
        } else {
            $sender = new SmsSender();

            if($sender -> send($to, $msg, $businessName, $businessID, $credits)) {
                $result = '{ "Result" : "OK", "SubmissionReference":"' . $sender -> submissionReference . '" }';
            } else {
                $error = explode('^', $sender -> error);
                if(sizeof($error) == 1) {
                    $result = '{ "Result" : "FAIL", "ErrorCode":"09", "ErrorMessage":"An error has occurred processing your submission" }';
                } else {
                    $result = '{ "Result" : "FAIL", "ErrorCode":"' . $error[0] . '", "ErrorMessage":"' . $error[1] . '" }';
                }
            }
        }

        return $result;
    }