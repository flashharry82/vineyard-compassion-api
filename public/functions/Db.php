<?php
	class Db {
//        class Db extends mysqli {
//            public function __construct() {
//                parent::__construct($server, $username, $password, $defautSchema);
//
//                if (mysqli_connect_error()) {
//                    die('Connect Error (' . mysqli_connect_errno() . ') '
//                        . mysqli_connect_error());
//                }
//            }
//        }

		public static function open() {

            $server = SQLSERVER_NAME;
            $username = SQLSERVER_USERNAME;
            $password = SQLSERVER_PASSWORD;
            $defaultSchema = SQLSERVER_DATABASE_NAME;


			$link = new mysqli($server, $username, $password, $defaultSchema);

			if($link -> errno) {
				die('Connection to database failed (' . $link -> errno . ') - ' . $link -> error);
			}

			return $link;
		}



		public static function close($link) {
			$link -> close();
		}



        public static function logError($error, $link) {
            $stmt = $link -> prepare("INSERT INTO ErrorLog (DateOfError, PageUrl, ErrorMessage) VALUE (NOW(), ?, ?)");
            $stmt -> bind_param('ss', $_SERVER['PHP_SELF'], $error);
            $stmt -> execute();
        }



        /***** Data Preparation Helpers for Prepared Statements *****/
        public static function prepareDateForStmt($date) {
            if(!empty($date)) {
                $preparedDate = DateTime::createFromFormat('d/m/Y', $date);
                return $preparedDate -> format('Y-m-d');
            } else {
                return null;
            }
        }

        public static function prepareStringForStmt($string) {
            //Remove all html tags from the value.
            $string = strip_tags($string);

            //remove extra slashes characters
            $string = stripslashes($string);

            //Trim all leading and trailing whitespace.
            $string = trim($string);

            return (strlen($string) > 0 ? $string : NULL);
        }
        /***** Data Preparation Helpers for Prepared Statements *****/
	}
?>