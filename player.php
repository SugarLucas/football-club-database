<html>
    <head>
        <title>Players page</title>
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
        <div style="float:right"> <a href="head_page.php">Home</a></div>
        <hr />
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="player.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <h2>All Players:</h2>
        <form method="GET" action="player.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <p><input type="submit" value="Show All Players" name="showAll"></p>
        </form>

        <hr />

        <h2>Insert Players into Team</h2>
        <form method="POST" action="player.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            ID:<input type="int" name="id"> <br /><br />
            Name:<input type="text" name="name"> <br /><br />
            Goals:<input type="int" name="goals"> <br /><br />
            Age:<input type="int" name="age"> <br /><br />
            Country:<input type="text" name="country"> <br /><br />
            Team_Name:<input type="text" name="teamName"> <br /><br />
            Game_Played:<input type="int" name="gamePlayed"> <br /><br />
            Salary:<input type="int" name="salary"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Delete Values from team</h2>
        <form method="POST" action="player.php">
            <input type="hidden" name="deleteQueryRequest">
            Name: <input type="text" name="delName"><br><br>
            <input type="submit" name="deleteSubmit" value="Delete">
        </form>


        <h2>Update info in team</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="player.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Name: <input type="text" name="oldName"> <br /><br />
            New Name: <input type="text" name="newName"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Count the Tuples in DemoTable</h2>
        <form method="GET" action="player.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>

        
        <hr />
        <h2>Select the team with players from both countries</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p >

        <form method="POST" action="player.php"> <!--refresh page when submitted-->
            <input type="hidden" id="divisionQueryRequest" name="divisionQueryRequest">
            Nationality1: <input type="text" name="nationality1"> <br /><br />
            Nationality2: <input type="text" name="nationality2"> <br /><br />

            <input type="submit" value="Select" name="divisionSubmit"></p >
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
        // TODO
        function printResult($result) { //prints results from a select statement
            echo "<table>";
            echo "<tr><th>PID</th><th>Name</th><th>Goals</th><th>Age</th><th>Country</th><th>Game Played</th><th>Team Name</th></tr>";
        
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["PID"] . "</td><td>" . $row["NAME"] . "</td><td>" . $row["GOALS"] . "</td><td>" . $row["AGE"] . "</td><td>" . $row["COUNTRY"] . "</td><td>" . $row["GAME_PLAYED"] . "</td><td>" . $row["TEAM_NAME"] . "</td></tr>"; //or just use "echo $row[0]"
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
            executePlainSQL("UPDATE Player_Info SET name='" . $new_name . "' WHERE name='" . $old_name . "'");
            OCICommit($db_conn);
            echo "<p>Successfully updated player $id.</p>";
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE Player_Info");
            executePlainSQL("DROP TABLE Player_Salary");
        
            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("CREATE TABLE Player_Salary(
                game_played int,
                goals int,
                age int,
                salary int,
                PRIMARY KEY (game_played, goals, age)
            )");
            executePlainSQL("CREATE TABLE Player_Info(
                PID int,
                name char(20),
                goals int,
                age int,
                country char(20),
                game_played int,
                team_name char(20) NOT NULL,
                PRIMARY KEY (PID),
                FOREIGN KEY (game_played, goals, age) REFERENCES Player_Salary 
                ON DELETE CASCADE,
                FOREIGN KEY (team_name) REFERENCES Team 
                ON DELETE CASCADE
            )");
        
            OCICommit($db_conn);
        }
        

        function handleInsertRequest() {
            global $db_conn, $success;
            $id = $_POST['id'];
            $name = $_POST['name'];
            $goals = $_POST['goals'];
            $age = $_POST['age'];
            $country = $_POST['country'];
            $teamName = $_POST['teamName'];
            $gamePlayed = $_POST['gamePlayed'];
            $salary = $_POST['salary'];
         
            // TODO: add further validation for input values (e.g. check if values are non-empty, numeric, etc.)
            
            // $query1 = "INSERT INTO Player_Salary (game_played, goals, age, salary) VALUES (?, ?, ?, ?)";
            // $stmt1 = mysqli_prepare($db_conn, $query1);
            // mysqli_stmt_bind_param($stmt1, "iiii", $gamePlayed, $goals, $age, $salary);
            // $result1 = mysqli_stmt_execute($stmt1);
            executePlainSQL("INSERT INTO Player_Salary VALUES ($gamePlayed, $goals, $age, $salary)");
            executePlainSQL("INSERT INTO Player_Info VALUES ($id, '$name', $goals, $age, '$country', $gamePlayed, '$teamName')");
            OCICommit($db_conn);
            echo "Player '$name' added Successfully<br>";
            
            // $query2 = "INSERT INTO Player_Info (PID, name, goals, age, country, game_played, team_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
            // $stmt2 = mysqli_prepare($db_conn, $query2);
            // mysqli_stmt_bind_param($stmt2, "ississs", $id, $name, $goals, $age, $country, $gamePlayed, $teamName);
            // $result2 = mysqli_stmt_execute($stmt2);
            
            // if ($result1 && $result2) {
            //     echo "<p>Successfully inserted player $name.</p >";
            // }
            // else {
            //     echo "<p>Failed to insert player $name.</p >";
            // }
        }  


        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM Player_Info");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in team: " . $row[0] . "<br>";
            }
        }
        function handleDisplayRequest() {
            global $db_conn;
            $result = executePlainSQL("SELECT * FROM Player_Info");
            printResult($result);
        }
        function handleDeleteRequest() {
            global $db_conn;
            $delName = $_POST['delName'];
            executePlainSQL("DELETE FROM Player_Salary
            WHERE (game_played, goals, age) IN (
              SELECT game_played, goals, age
              FROM Player_Info
              WHERE name = '$delName'
            )");
           // executePlainSQL("DELETE FROM Player_Salary WHERE game_played IN (SELECT game_played FROM Player_Info WHERE name='$delName')");
            OCICommit($db_conn);
            echo "Values Deleted Successfully<br>";
        }
        function handleDivisionRequest() {
            global $db_conn;
    
            $nat1 = $_POST['nationality1'];
            $nat2 = $_POST['nationality2'];
    
            $result = executePlainSQL("select distinct t.name
            from Player_Info p
            join Team t on p.team_name = t.name
            where p.country IN ('$nat1','$nat2')
            group by t.name
            having count(distinct p.country) = 2");
            
            
            echo "<table>";
                echo "<tr><th>Team that contains '$nat1' and '$nat2' players</th></tr>";
    
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr><td>" . $row[0] . "</td><tr>" ; 
                }
    
                echo "</table>";
        }
        
        
        
        
        
        

        

        

    

        // HANDLE ALL POST ROUTES
    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('resetTablesRequest', $_POST)) {
                handleResetRequest();
            } else if (array_key_exists('updateQueryRequest', $_POST)) {
                handleUpdateRequest();
            } else if (array_key_exists('deleteQueryRequest', $_POST)) {
                handleDeleteRequest();
            } else if (array_key_exists('insertQueryRequest', $_POST)) {
                handleInsertRequest();
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
                } 
                disconnectFromDB();
            }
        }


        if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])|| isset($_POST['deleteSubmit'])|| isset($_POST['divisionSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest'])|| isset($_GET['showAll'])) {
            handleGETRequest();
        }
        ?>
    </body>
</html>
