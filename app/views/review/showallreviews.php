<!DOCTYPE html>
<html>
<head>
    <title>HomePage Logged In</title>
    <link rel="stylesheet" href="/MVC/css/myProfile.css" type="text/css">
</head>
<body>

<?php $lessonId = $data['lessonId']; ?>

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
    <h2>
        <center>
            <div id="headingProfile2">Leave A Review</div>
        </center>
    </h2>

    <form id="custom-search-form" class="form-search form-horizontal" action="/MVC/teacher/insertReview"
          method="post">

        <div class="form-group">
            <label for="message">Rating</label>
            <select name="rating" required>
                <option class="form-control" value=""></option>
                <option class="form-control" value="1">1 Star</option>
                <option class="form-control" value="2">2 Stars</option>
                <option class="form-control" value="3">3 Stars</option>
                <option class="form-control" value="4">4 Stars</option>
                <option class="form-control" value="5">5 Stars</option>
            </select>
            <input hidden id="<?= $lessonId; ?> " name="lessonId"
                   value="<?= $lessonId ?>">
        </div>

        <div class="form-group">
            <label for="message">Comment</label>
            <textarea class="form-control" id="comment" name="comment" rows="5"></textarea>
        </div form-group>
        <div class="form-group">
            <button name="action" type="submit" class="btn btn-default" value="PostQuestion">Leave Review
            </button>
        </div>

    </form>
</div>

</body>
</html>