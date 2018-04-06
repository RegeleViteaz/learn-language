<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <meta charset="utf-8">
</head>
<body>


<div class="container">
    <div class="col-xs-12">
        <h3>
            <center>
                <div id="headingProfile2">No Questions Found</div>
            </center>
        </h3>
        <div class="container">
            <form class="form-search form-horizontal" action="/MVC/teacher/askQuestionFiller" method="post">
                <div class="search col-sm-offset-5">
                    <button type="submit" name="action" class="btn btn-primary btn-sm" value="Questions">Ask a Question</button>
                    <button type="button" onclick="location.href='/MVC/account/';" name="action" class="btn btn-primary btn-sm">
                        Back
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>