<!DOCTYPE html>
<html>
<head>

<style>
		body {
			background-color: #f1f1f1;
			font-family: Arial, sans-serif;
		}
		
		h1 {
			font-size: 48px;
			font-weight: bold;
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

		.navbar {
			background-color: #333;
			overflow: hidden;
			position: fixed;
			top: 0;
			width: 100%;
		}

		.navbar a {
			float: right;
			color: #f2f2f2;
			text-align: center;
			padding: 14px 16px;
			text-decoration: none;
			font-size: 17px;
		}

		.navbar a:hover {
			background-color: #ddd;
			color: black;
		}
	</style>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Search For Previous Order</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
</head>

<body bgcolor="ffffcc">
    <div style="float:right"><a href="cover.php">Cover</a> | <a href="head_page.php">Home</a></div>
    <p>Football Club database management system</p>
    <hr />
    
    <h1 style="text-align:center;">Players</h1>

    <div class="container" style="text-align:center;">
        <a href="player.php">
            <button class="btn btn-primary btn-lg">Edit players</button>
        </a>
    </div>

    <hr />
    
    <h1 style="text-align:center;">Team</h1>

    <div class="container" style="text-align:center;">
        <a href="team.php">
            <button class="btn btn-primary btn-lg">Edit teams</button>
        </a>
    </div>

    <hr />
    <h1 style="text-align:center;">Staff</h1>

    <div class="container" style="text-align:center;">
        <a href="staff.php">
            <button class="btn btn-primary btn-lg">Edit staffs</button>
        </a>
    </div>

    <hr />
    
</body>
</html>
