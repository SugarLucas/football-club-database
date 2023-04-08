<!DOCTYPE html>
<html>
    <head>
        <title>View Team</title>
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
        <p>Team Information</p>
        
        <hr />

        <h2>View Team</h2>
        <p>Check The Fields You Want To View:</p>

        <form method="POST" action=""> <!--refresh page when submitted-->
            <label for="teamNameRequest"> Team Name: </label>
            <input type="checkbox" id="teamNameRequest" name="teamNameRequest" value="true"> <br /><br />

            <label for="teamRankRequest"> Rank: </label>
            <input type="checkbox" id="teamRankRequest" name="teamRankRequest" value="true"> <br /><br />

            <label for="teamAgeRequest"> Age: </label>
            <input type="checkbox" id="teamAgeRequest" name="teamAgeRequest" value="true"> <br /><br />

            <label for="teamPointsRequest"> Points: </label>
            <input type="checkbox" id="teamPointsRequest" name="teamPointsRequest" value="true"> <br /><br />

            <input type="submit" value="Show" name="viewSubmit"></p>
        </form>

        <hr />

        <h2>Filter Team</h2>
        <p>Filter the results based on the fields below(Greater and Equal Than):</p>

        <form method="POST" action="">
            <label for="filterRank">Rank:</label>
            <input type="text" id="filterRank" name="filterRank" placeholder="Enter rank"> <br /><br />

            <label for="filterAge">Age:</label>
            <input type="text" id="filterAge" name="filterAge" placeholder="Enter age"> <br /><br />

            <label for="filterPoints">Points:</label>
            <input type="text" id="filterPoints" name="filterPoints" placeholder="Enter points"> <br /><br />

            <input type="submit" value="Filter" name="filterSubmit">
        </form>

        <hr />

        <h2>Team Total Salary</h2>
        <p>Click the button to view the total salary of each team:</p>

        <form method="POST" action="">
            <input type="submit" value="Show Total Salaries" name="totalSalarySubmit">
        </form>
        <hr />

        <h2>Teams with Brazilian Players(selection)</h2>
        <p>Click the button to view the teams with Brazilian players:</p>

        <form method="POST" action="">
            <input type="submit" value="Show Teams" name="brazilianTeamsSubmit">
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

        function printTeamResult($result) {
            echo "<table>";
            echo "<tr><th>Team Name</th><th>Rank</th><th>Age</th><th>Points</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; 
            }

            echo "</table>";
        }

        function printTeamTotalSalaryResult($result) {
            echo "<table>";
            echo "<tr><th>Team Name</th><th>Total Salary</th></tr>";
        
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }
        
            echo "</table>";
        }

        function printBrazilianTeamsResult($result) {
            echo "<table>";
            echo "<tr><th>Team Name</th></tr>";
        
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }
        
            echo "</table>";
        }

        function handleView() {
            global $db_conn;

            if (!empty($_POST['teamNameRequest'])) {
                $teamName = "Name";
            } else {
                $teamName = "null";
            }

            if (!empty($_POST['teamRankRequest'])) {
                $teamRank = "Rank";
            } else {
                $teamRank = "null";
            }

            if (!empty($_POST['teamAgeRequest'])) {
                $teamAge = "Age";
            } else {
                $teamAge = "null";
            }

            if (!empty($_POST['teamPointsRequest'])) {
                $teamPoints = "Points";
            } else {
                $teamPoints = "null";
            }

            $result = executePlainSQL("SELECT $teamName, $teamRank, $teamAge, $teamPoints FROM Team");
            printTeamResult($result);
        }

        function handleFilter() {
            global $db_conn;

            $filterConditions = array();

            if (!empty($_POST['filterRank'])) {
                $filterRank = intval($_POST['filterRank']);
                $filterConditions[] = "Rank >= $filterRank";
            }

            if (!empty($_POST['filterAge'])) {
                $filterAge = intval($_POST['filterAge']);
                $filterConditions[] = "Age >= $filterAge";
            }

            if (!empty($_POST['filterPoints'])) {
                $filterPoints = intval($_POST['filterPoints']);
                $filterConditions[] = "Points >= $filterPoints";
            }

            $filterConditionsStr = implode(' AND ', $filterConditions);
            $filterConditionsStr = $filterConditionsStr ? "WHERE $filterConditionsStr" : "";

            $result = executePlainSQL("SELECT Name, Rank, Age, Points FROM Team $filterConditionsStr");
            printTeamResult($result);
        }

        function handleTotalSalary() {
            global $db_conn;
        
            $result = executePlainSQL("SELECT team_name, COALESCE(SUM(staff_salary),0) +  COALESCE(SUM(player_salary),0) AS total_salary
            FROM (
              SELECT team_name, salary AS staff_salary, NULL AS player_salary
              FROM staff_info
              UNION ALL
              SELECT pi.team_name, NULL AS staff_salary, ps.salary AS player_salary
              FROM player_info pi
              JOIN player_salary ps ON pi.game_played = ps.game_played AND pi.goals = ps.goals AND pi.age = ps.age
            ) t
            GROUP BY team_name
            ORDER BY team_name ASC");
            printTeamTotalSalaryResult($result);
        }

        function handleBrazilianTeams() {
            global $db_conn;
        
            $result = executePlainSQL("SELECT DISTINCT T.Name
                                        FROM Team T, Player_Info P
                                        WHERE T.Name = P.Team_Name AND P.country = 'Brazil'");
            printBrazilianTeamsResult($result);
        }

        
        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('viewSubmit', $_POST)) {
                    handleView();
                } else if (array_key_exists('filterSubmit', $_POST)) {
                    handleFilter();
                } else if (array_key_exists('totalSalarySubmit', $_POST)) {
                    handleTotalSalary();
                } else if (array_key_exists('brazilianTeamsSubmit', $_POST)) {
                    handleBrazilianTeams();
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
                }

                disconnectFromDB();
            }
        }

            if (isset($_POST['viewSubmit'])) {
                handlePOSTRequest();
            } else if (isset($_POST['filterSubmit'])) {
                handlePOSTRequest();
            } else if (isset($_GET['countTupleRequest'])) {
                handleGETRequest();
            } else if (isset($_POST['totalSalarySubmit'])) {
                handlePOSTRequest();
            } else if (isset($_POST['brazilianTeamsSubmit'])) {
                handlePOSTRequest();
            }
        
		?>
	</body>
</html>
