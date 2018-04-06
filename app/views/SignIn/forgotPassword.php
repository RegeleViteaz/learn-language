<!DOCTYPE html>
<html>
<head>
    <title>Password Recover</title>
</head>
<body>

<h3>Enter your email address and you will get default password sent to you</h3>

<form method="post" action="/MVC/home/forgotPassword">
    <label for="email">Email Address</label>
    <input class="email_address" type="email" id="email" name="email"/> <br><br>

    <button name="action" type="submit" value="recover">Recover Password</button>
</form>

</body>
</html>