<!DOCTYPE html>
<html>
<head>
    <title>No Lessons</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/MVC/css/homeIndex.css" type="text/css">
</head>
<body>

<?php $lessonId = $data['lessonId']; ?>

<div class="container">
    <div class="col-xs-12">
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
</div>

</body>
</html>
