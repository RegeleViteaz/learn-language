<!DOCTYPE html>
<html>
<head>
    <title>HomePage Logged In</title>
    <link rel="stylesheet" href="/MVC/css/myProfile.css" type="text/css">
</head>
<body>

<div class="container">


    <h2>
        <center>
            <div id="headingProfile">Reviews</div>
        </center>
    </h2>

    <form id="custom-search-form" class="form-search form-horizontal" action="/MVC/account" method="post">
        <?php foreach ($data['reviewData'] as $details): ?>
            <div class="form-group">
                <label class="col-sm-5 control-label">Lesson ID</label>

                <div class="col-sm-4">
                    <p class="form-control-static"><?= $details['lesson_id']; ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 control-label">Rating</label>

                <div class="col-sm-4">
                    <p class="form-control-static"><?= $details['rating']; ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-5 control-label">Comment</label>

                <div class="col-sm-4">
                    <p class="form-control-static"><?= $details['comment']; ?></p>
                </div>
            </div>
        <?php endforeach ?>
        <div class="form-group">
            <div class="search col-sm-offset-4">
                <button type="submit" name="action" class="btn btn-primary btn-md" value="BackButton">Back</button>
            </div>
        </div>

    </form>


</div>

</body>
</html>