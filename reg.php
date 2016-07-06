<!DOCTYPE html>
<html>
<head>
<title>Registration</title>
<style>
  #regForm
  {
    width: 25%;
    margin: auto;
  }
</style>
</head>
<body>
<?php
if(isset($_POST['submit'])){
    $missingData = array();

    if(empty($_POST['username'])){
        $missingData[] = 'Username';
    } else {
        $username = trim($_POST[username]);
    }

    if(empty($_POST['password'])){
        $missingData[] = 'Password';
    } else {
        $password = trim($_POST[password]);
    }

    if(empty($_POST['question'])){
        $missingData[] = 'Question';
    } else {
        $question = trim($_POST[question]);
    }

    if(empty($_POST['answer'])){
        $missingData[] = 'Answer';
    } else {
        $answer = trim($_POST[answer]);
    }

    if(empty($missingData)){
        require_once('/home/alex/workspace/server/mysqlConnect.php');
        
        // Add username to database
        $userQuery = "INSERT INTO users (username, id) VALUES(?, NULL)";
        $stmt = mysqli_prepare($dbc, $userQuery);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $affected_rows = mysqli_stmt_affected_rows($stmt);

        if($affected_rows == 1){
            echo '<p>Username entered</p>';
        } else {
            echo '<p>Error occurred when adding username</p>';
            echo mysqli_error();
            mysqli_stmt_close($stmt);
            mysqli_close($dbc);
            die;
        }
        mysqli_stmt_close($stmt);

        // Get id of newly-created user. Loops until it gets to the highest id, hopefully
        $response = @mysqli_query($dbc, "SELECT id FROM users WHERE username = '$username'");
        while($row = mysqli_fetch_array($response)){
            $userID = intval($row[id]);
        }
        mysql_free_result($response);

        // Add password to database
        $passwordQuery = "INSERT INTO passwords (id, password) VALUES(?, ?)";
        $stmt = mysqli_prepare($dbc, $passwordQuery);
        mysqli_stmt_bind_param($stmt, "is", $userID, $password);
        mysqli_stmt_execute($stmt);

        $affected_rows = mysqli_stmt_affected_rows($stmt);

        if($affected_rows == 1){
            echo '<p>Password entered</p>';
        } else {
            echo '<p>Error occurred when adding password</p>';
            echo mysqli_error();
            mysqli_stmt_close($stmt);
            // Have to delete the user we just created.
            $delete = @mysqli_query($dbc, "DELETE FROM users WHERE id = " . $userID);
            mysqli_close($dbc);
            die;
            
        }
        mysqli_stmt_close($stmt);

        // Add secret question and answer to database
        $secretQuery = "INSERT INTO secretQuestion (id, question, answer) VALUES(?, ?, ?)";
        $stmt = mysqli_prepare($dbc, $secretQuery);
        mysqli_stmt_bind_param($stmt, "iss", $userID, $question, $answer);
        mysqli_stmt_execute($stmt);

        $affected_rows = mysqli_stmt_affected_rows($stmt);

        if($affected_rows == 1){
            echo '<p>Secret question and answer entered</p>';
        } else {
            echo '<p>Error occurred when entering question and answer</p>';
            echo mysqli_error();
            mysqli_stmt_close($stmt);
            // Have to delete the user we just created.
            // This should also trigger a delete on the passord.
            $delete = @mysqli_query($dbc, "DELETE FROM users WHERE id = " . $userID);
            mysqli_close($dbc);
            die;
        }
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
    } else {
        echo "<p>You need to enter the following data:</p><ul>";
        foreach($missingData as $data){
            echo "<li>$data</li>";
        }
        echo "</ul>";
    }
}
?>
  <div id="regForm">
    <form action="reg.php" method="post">
        <p>Desired Username</p>
        <input type="text" name="username" />
        <p>Password</p>
        <input type="text" name="password" />
        <p>Secret Question</p>
        <input type="text" name="question" />
        <p>Secret Answer</p>
        <input type="text" name="answer" />

        <p id="submitbutton"><input type="submit" value="Submit" name="submit"/></p>
    </form>
  </div>
</body>
</html>