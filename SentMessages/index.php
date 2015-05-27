<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sak']) && isset($_POST['number'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo sentMessages($_POST['sak'], $_POST['number']);
    } elseif($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['sak']) && isset($_GET['number'])) {
        header('Content-Type: application/json; charset=utf-8');
        echo sentMessages($_GET['sak'], $_GET['number']);
    } else {
        notAllow();
    }

    function notAllow() {
        $header = $_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden";
        header($header);
        die($header);
    }

    function sentMessages($key, $number) {
        require '../assets/config.php';


        $link = Db::open();

        //check for valid SAK
        $stmt = $link -> prepare("SELECT Businesses.BusinessID FROM Businesses WHERE SenderKey = ?");
        $stmt -> bind_param('s', $key);
        $stmt -> bind_result($businessID);
        $stmt -> execute();
        $stmt -> fetch();
        $stmt -> close();
        if($businessID === null)
        {
            $output = '{ "Result" : "FAIL", "ErrorCode":"01", "ErrorMessage":"Invalid SAK" }';
        }
        else
        {


            $output = '{ "Result" : "OK", "Data" : [';

            //Get records for the number passed
            $stmt = $link -> prepare("SELECT SentTo, Message, NumberOfCredits, SubmissionReference, SentDate FROM SmsMessages LEFT JOIN Businesses ON Businesses.BusinessID = SmsMessages.BusinessID WHERE Businesses.SenderKey = ? AND SentTo = ?");
            $stmt -> bind_param('ss', $key, $number);
            $stmt -> bind_result($sentTo, $message, $credits, $reference, $sentDate);
            $stmt -> execute();
            while($stmt -> fetch())
            {
                $output .= '{ "SentTo" : "' . $sentTo . '", "Message" : "' . $message . '", "CreditsUsed" : "' . $credits . '", "$reference" : "' . $reference . '", "SentDate" : "' . $sentDate . '"},';
            }
            $stmt -> close();

            $output = substr($output, 0, strlen($output)-1);


            $output .= ']}';

        }



        $link -> close();


        $result = $output;

        return $result;
    }