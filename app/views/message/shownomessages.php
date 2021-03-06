<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/MVC/css/homeIndex.css" type="text/css">
    <link rel="stylesheet" href="/MVC/css/searchfield.css" type="text/css">
</head>
<body>

<div class="container">
    <div class="col-xs-12">
        <h2>
            <center>
                <div id="headingProfile2">No Messages Found</div>
            </center>
        </h2>
        <div class="container">
            <div class="row">
                <form id="custom-search-form" class="form-search form-horizontal" action="/MVC/teacher/searchPersonToMessage" method="post">
                    <div class="search col-sm-offset-5">
                        <input type="text" name="searchPerson" id="searchPerson" class="form-control input-sm" maxlength="64" placeholder="Search"/>
                        <button type="submit" name="action" class="btn btn-primary btn-sm" value="SearchPerson">Search for User</button>
                        <button type="button" onclick="location.href='/MVC/account/myProfile';" name="action" class="btn btn-primary btn-sm">
                            Back
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>