<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand">Learn a Language</a>
        </div>
        <ul class="nav navbar-nav">
            <?php if (LoginCore::isLoggedIn()) : ?>
                <li class="active"><a href="/MVC/account">Home</a></li>
                <li><a href="/MVC/account/myProfile">My Profile</a></li>
                <li><a href="/MVC/home/logout">Logout</a></li>
            <?php endif; ?>


        </ul>
    </div>
</nav>
</body>
</html>