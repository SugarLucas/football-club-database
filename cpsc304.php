!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password -->

<html>
    <head>
        <title>CPSC 304 PHP/304 Project</title>
    </head>

    <body>
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="cpsc304.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <h2>Insert Values into DemoTable</h2>
        <form method="POST" action="cpsc304.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Number: <input type="text" name="insNo"> <br /><br />
            Name: <input type="text" name="insName"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Delete Values from DemoTable</h2>
        <form method="POST" action="cpsc304.php">
            <input type="hidden" name="deleteQueryRequest">
            Name: <input type="text" name="delName"><br><br>
            <input type="submit" name="deleteSubmit" value="Delete">
        </form>


        <h2>Update Name in DemoTable</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="cpsc304.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Name: <input type="text" name="oldName"> <br /><br />
            New Name: <input type="text" name="newName"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Count the Tuples in DemoTable</h2>
        <form method="GET" action="cpsc304.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>

        <h2>Display the Tuples in DemoTable</h2>
        <form method="GET" action="cpsc304.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" name="displayTuples"></p>
        </form>

        <h2>Project the Tuples from DemoTable</h2>
        <form method="GET" action="cpsc304.php">
            <input type="hidden" name="projectRequest">
            Attribute: <input type="text" name="projectAttr"><br><br>
            <input type="submit" name="projectSubmit" value="Project">
        </form>

        <h2>Aggregation with Group By</h2>
        <form method="GET" action="cpsc304.php"> <!--refresh page when submitted-->
            <input type="hidden" id="aggGroupByRequest" name="aggGroupByRequest">
            <label for="groupByColumn">Group By Column:</label>
            <select name="groupByColumn">
                <option value="column1">Column 1</option>
                <option value="column2">Column 2</option>
                <option value="column3">Column 3</option>
            </select>
            <br /><br />
            <label for="aggFunc">Aggregate Function:</label>
            <select name="aggFunc">
                <option value="count">Count</option>
                <option value="sum">Sum</option>
                <option value="avg">Average</option>
                <option value="max">Max</option>
                <option value="min">Min</option>
            </select>
            <br /><br />
            <input type="submit" name="aggGroupBySubmit">
        </form>

        <h2>Aggregation with Having</h2>
        <form method="GET" action="cpsc304.php"> <!--refresh page when submitted-->
            <input type="hidden" id="aggHavingRequest" name="aggHavingRequest">
            <label for="aggFunc">Aggregate Function:</label>
            <select name="aggFunc">
                <option value="count">Count</option>
                <option value="sum">Sum</option>
                <option value="avg">Average</option>
                <option value="max">Max</option>
                <option value="min">Min</option>
            </select>
            <br /><br />
            <label for="havingCond">Having Condition:</label>
            <input type="text" name="havingCond">
            <br /><br />
            <input type="submit" name="aggHavingSubmit">
        </form>

        <h2>Nested Aggregation with Group By</h2>
        <form method="POST" action="cpsc304.php">
            <input type="hidden" id="nestedAggregationRequest" name="nestedAggregationRequest">
            Group By Column: 
            <select name="nestedAggGroupByCol">
                <?php
                $columnNames = getColumnNames("demoTable");
                foreach ($columnNames as $colName) {
                    echo "<option value='$colName'>$colName</option>";
                }
                ?>
            </select><br><br>
            Aggregation Column:
            <select name="nestedAggAggCol">
                <?php
                foreach ($columnNames as $colName) {
                    echo "<option value='$colName'>$colName</option>";
                }
                ?>
            </select><br><br>
            Nested Aggregation Function:
            <select name="nestedAggFunc">
                <option value="MAX">MAX</option>
                <option value="MIN">MIN</option>
                <option value="SUM">SUM</option>
                <option value="AVG">AVG</option>
            </select><br><br>
            Aggregation Threshold:
            <input type="text" name="nestedAggThreshold"><br><br>
            <input type="submit" value="Execute" name="nestedAggSubmit"></p>
        </form>

        <h2>Join DemoTable and DemoTable2 on Name</h2>
        <form method="POST" action="cpsc304.php"> <!--refresh page when submitted-->
            <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
            <input type="submit" value="Join" name="joinSubmit"></p>
        </form>

        <h2>Perform Division Operation</h2>
        <form method="POST" action="cpsc304.php">
            <input type="hidden" name="divisionRequest">
            <p>Select the dividend table:</p>
            <select name="dividendTable">
                <option value="Table1">Table1</option>
                <option value="Table2">Table2</option>
                <option value="Table3">Table3</option>
            </select>
            <br><br>
            <p>Select the divisor table:</p>
            <select name="divisorTable">
                <option value="TableA">TableA</option>
                <option value="TableB">TableB</option>
                <option value="TableC">TableC</option>
            </select>
            <br><br>
            <input type="submit" value="Perform Division" name="divisionSubmit">
        </form>


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

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
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

        function handleUpdateRequest() {
            global $db_conn;

            $old_name = $_POST['oldName'];
            $new_name = $_POST['newName'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE demoTable SET name='" . $new_name . "' WHERE name='" . $old_name . "'");
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE demoTable");

            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insNo'],
                ":bind2" => $_POST['insName']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into demoTable values (:bind1, :bind2)", $alltuples);
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM demoTable");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in demoTable: " . $row[0] . "<br>";
            }
        }
        function handleDisplayRequest() {
            global $db_conn;
            $result = executePlainSQL("SELECT * FROM DemoTable");
            printResult($result);
        }
        function handleDeleteRequest() {
            global $db_conn;
            $delName = $_POST['delName'];
            $result = executePlainSQL("DELETE FROM DemoTable WHERE name='$delName'");
            OCICommit($db_conn);
            echo "Values Deleted Successfully<br>";
        }
        function handleProjectRequest() {
            global $db_conn;
            $projectAttr = $_GET['projectAttr'];
            $result = executePlainSQL("SELECT DISTINCT $projectAttr FROM DemoTable");
            printResult($result);
        }
        function handleAggGroupByRequest() {
            global $db_conn;
            $groupByColumn = $_GET['groupByColumn'];
            $aggFunc = $_GET['aggFunc'];

            $result = executePlainSQL("SELECT $groupByColumn, $aggFunc(*) FROM demoTable GROUP BY $groupByColumn");

            echo "<br> Results: <br>";
            echo "<table>";
            echo "<tr><th>$groupByColumn</th><th>$aggFunc</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["$groupByColumn"] . "</td><td>" . $row["$aggFunc(*)"] . "</td></tr>";
            }
            echo "</table>";
        }
        function handleAggHavingRequest() {
            global $db_conn;
            $aggFunc = $_GET['aggFunc'];
            $havingCond = $_GET['havingCond'];
        
            $result = executePlainSQL("SELECT $aggFunc(*) FROM demoTable GROUP BY column1 HAVING $havingCond");
        
            echo "<br> Results: <br>";
            echo "<table>";
            echo "<tr><th>$aggFunc</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["$aggFunc(*)"] . "</td></tr>";
            }
            echo "</table>";
        }
        function handleNestedAggregationRequest()
        {
            global $db_conn;
            $groupByCol = $_POST['nestedAggGroupByCol'];
            $aggCol = $_POST['nestedAggAggCol'];
            $aggFunc = $_POST['nestedAggFunc'];
            $threshold = $_POST['nestedAggThreshold'];
            $threshold = intval($threshold);

            $query = "SELECT $groupByCol, COUNT(*) as Count, $aggFunc($aggCol) as AggValue 
                    FROM demoTable
                    GROUP BY $groupByCol
                    HAVING COUNT(*) > $threshold";

            $result = executePlainSQL($query);

            echo "<br>Aggregation results:<br>";
            echo "<table>";
            echo "<tr><th>$groupByCol</th><th>Count</th><th>$aggFunc($aggCol)</th></tr>";

            while (($row = oci_fetch_row($result)) != false) {
                echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>";
            }

            echo "</table>";
        }
        function handleDivisionRequest() {
            global $db_conn;
            $output = "";
        
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $dividend = $_POST["dividend"];
                $divisor = $_POST["divisor"];
                
                // Validate input
                if (!is_numeric($dividend) || !is_numeric($divisor)) {
                    $output = "<p>Please enter numeric values for dividend and divisor.</p>";
                } else {
                    // Execute division query
                    $query = "SELECT DISTINCT d1.Name FROM DemoTable d1 WHERE NOT EXISTS (SELECT d2.Name FROM DemoTable d2 WHERE NOT EXISTS (SELECT * FROM DemoTable d3 WHERE d2.Name = d3.Name AND d1.Number = d3.Number / $divisor))";
                    $result = executePlainSQL($query);
        
                    // Display result
                    $output .= "<h2>Result of Division:</h2>";
                    if (($row = oci_fetch_row($result)) != false) {
                        $output .= "<ul>";
                        do {
                            $output .= "<li>" . $row[0] . "</li>";
                        } while (($row = oci_fetch_row($result)) != false);
                        $output .= "</ul>";
                    } else {
                        $output .= "<p>No results found.</p>";
                    }
                }
            }
        }
        

        

        
        

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('resetTablesRequest', $_POST)) {
                handleResetRequest();
            } else if (array_key_exists('updateQueryRequest', $_POST)) {
                handleUpdateRequest();
            } else if (array_key_exists('insertQueryRequest', $_POST)) {
                handleInsertRequest();
            } else if (array_key_exists('selectQueryRequest', $_POST)) {
                handleSelectRequest();
            } else if (array_key_exists('joinQueryRequest', $_POST)) {
                handleJoinRequest();
            } else if (array_key_exists('nestedAggregationRequest', $_POST)) {
                handleAggregationHavingRequest();
            } else if (array_key_exists('divisionQueryRequest', $_POST)) {
                handleDivisionRequest();
            }
            disconnectFromDB();
        }
    }
    

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                } else if (array_key_exists('displayTupleRequest',$_GET)){
                    handleDisplayRequest();
                } else if (array_key_exists('projectionQueryRequest', $_GET)) {
                    handleProjectionRequest();
                } else if (array_key_exists('aggHavingRequest', $_GET)) {
                    handleAggregationRequest();
                } else if (array_key_exists('aggHavingRequest', $_GET)) {
                    handleAggregationGroupByRequest();
                } 

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
