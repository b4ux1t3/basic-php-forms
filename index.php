<!DOCTYPE html>
<html>
<head>
<title>Registration</title>
<style>
  #regForm
  {
    width: 25%;
    margin:auto;
  }
</style>
</head>
<body>
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