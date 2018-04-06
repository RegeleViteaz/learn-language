<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <meta charset="utf-8">
</head>
<body>


<div class="container form-group">
    <div class="col-xs-12">
        <h2>Login</h2>

        <form action="/MVC/home/login" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input class="form-control" type="email" id="email" name="email"/>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" name="password" type="password" id="password"/>
                <!-- <a href="/MVC/home/forgotPassword">Forgot Password?</a> <br><br> !-->
            </div form-group>

            <div class="form-group">
                <button name="action" type="submit" class="btn btn-default" value="Login">Login</button>
        </form>
    </div>
    <div class="form-inline">
        <form action="/MVC/home/registerDataDropDownLists">
            <button name="action" type="submit" class="btn btn-default" value="Register">Register</button>
        </form>
    </div>
</div>
</body>
</html>
