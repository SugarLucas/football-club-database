<html>
    <head>
        <title>Staff page</title>
    </head>
    <style>
		body {
			background-color: #f1f1f1;
			font-family: Arial, sans-serif;
		}
		
		h1 {
			font-size: 48px;
			color: #333;
			text-align: center;
			margin-top: 100px;
		}
		
		button {
			display: block;
			margin: 50px auto 0;
			padding: 10px 20px;
			font-size: 24px;
			background-color: #4CAF50;
			color: #fff;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s;
		}
		
		button:hover {
			background-color: #3e8e41;
		}
	</style>

    <body>
        <div style="float:right"><a href="head_page.php">Home</a></div>

        <hr />

        <h2>Show the number of staffs grouped by country</h2>
        <form method="GET" action="staff.php"> <!--refresh page when submitted-->
            <input type="hidden" id="aggGroupByRequest" name="aggGroupByRequest">
            <input type="submit" value="show group by result" name="showTable4"></p>
        </form>
        <hr />

        <h2>Show the number of staffs grouped by country(more than 1)(having)</h2>
        <form method="GET" action="staff.php"> <!--refresh page when submitted-->
            <input type="hidden" id="aggHavingRequest" name="aggHavingRequest">
            <input type="submit" value="show having result" name="showTable1"></p>
        </form>
        <hr />

        <h2>Show the name of all staff</h2>
        <form method="GET" action="staff.php">
            <input type="hidden" id="projectionQueryRequest" name="projectionQueryRequest">
            <input type="submit" value="show projection result" name="showTable2"></p >
        </form>

        <hr />

        <h2>Show the staff and team information(join)</h2>
        <form method="GET" action="staff.php">
            <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
            <input type="submit" value="show join result" name="showTable3"></p >
        </form>

        <hr />
    

    <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }


        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_lucas007", "a38612263", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleGroupByRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT c.country, count(*)
            FROM Staff_Country c
            join Staff_Info i on c.team_name = i.team_name
            GROUP BY country");
            printGroupByResult($result);
        }
        

        function printGroupByResult($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>Country</th><th>Count</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" .$row[1] ."</td><tr>" ; 
            }

            echo "</table>";
        }


        function handleGroupByHavingRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT c.country, count(*)
            FROM Staff_Country c
            join Staff_Info i on c.team_name = i.team_name
            GROUP BY country
            Having count(*) > 1 ");
            printGroupByHavingResult($result);
        }
        

        function printGroupByHavingResult($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>Country</th><th>Count</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" .$row[1] ."</td><tr>" ; 
            }

            echo "</table>";
        }

        function handleProjectStaffNameRequest() {
            global $db_conn;

            $result = executePlainSQL("select name
            from Staff_Info");
            printProjectStaffNameResult($result);
        }
        

        function printProjectStaffNameResult($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>"; 
            }

            echo "</table>";
        }

        function handleJoinRequest() {
            global $db_conn;

            $result = executePlainSQL("select *
            from staff_country SC
            join staff_info SI
            on SC.team_name = SI.team_name
            join team T
            on T.name = SI.team_name");
            printJoinResult($result);
        }
        

        function printJoinResult($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>Team_name</th><th>Country</th><th>ID</th><th>Name</th><th>age</th><th>salary</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>". $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[6] . "</td><td>"; 
            }

            echo "</table>";
        }


        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('deleteRequest', $_POST)) {
                    handDeleteRequest();
                } 

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('showTable1', $_GET)) {
                    handleGroupByHavingRequest();
                } else if (array_key_exists('showTable2', $_GET)){
                    handleProjectStaffNameRequest();
                } else if (array_key_exists('showTable3', $_GET)){
                    handleJoinRequest();
                } else if (array_key_exists('showTable4', $_GET)){
                    handleGroupByRequest();
                }
                disconnectFromDB();  
            }
        }

		if (isset($_POST['deleteSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['aggGroupByRequest']) || isset($_GET['aggHavingRequest']) || isset($_GET['projectionQueryRequest'])  || isset($_GET['joinQueryRequest'])) {
            handleGETRequest();
        }

        
		?>
    </body>
</html>
