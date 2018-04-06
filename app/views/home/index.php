<!DOCTYPE html>
<html>
<head>
    <title>HomePage Logged In</title>
    <link rel="stylesheet" href="/MVC/css/homeIndex.css" type="text/css">
    <link rel="stylesheet" href="/MVC/css/searchfield.css" type="text/css">
</head>
<body>

<div class="container">
    <h3>
        <div id="helloUser">
            <font size="5">
                <center><?php echo 'Welcome ' . $data['username']; ?> </center>
            </font>
        </div>
    </h3>
</div>

<div class="container">

    <h3>
        <center>
            <div id="headingProfile">Search For Teacher</div>
        </center>
    </h3>
    <div class="container">
        <div class="row">
            <form id="custom-search-form" class="form-search form-horizontal" action="/MVC/teacher/searchTeacherLanguage" method="post">
                <div class="search col-sm-offset-5">
                    <input type="text" name="searchLanguage" id="searchLanguage" class="form-control input-sm" maxlength="64" placeholder="Search"/>
                    <button type="submit" name="action" class="btn btn-primary btn-sm" value="Search">Search</button>
                </div>
            </form>
        </div>
    </div>
    <h3>
        <center>
            <div id="headingProfile2">Questions and Answers</div>
        </center>
    </h3>
    <div class="container">
        <form class="form-search form-horizontal" action="/MVC/teacher/showAllQuestions" method="post">
            <div class="search col-sm-offset-5">
                <button type="submit" name="action" class="btn btn-primary btn-sm" value="Questions">Community Questions and Answers</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>