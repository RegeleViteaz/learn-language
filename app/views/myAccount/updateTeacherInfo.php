<!DOCTYPE html>
<html>
<head>
    <title>HomePage Logged In</title>
    <link rel="stylesheet" href="/MVC/css/myProfile.css" type="text/css">
</head>
<body>

<div class="container">
    <?php $details = $data['descriptionData']; ?>

    <h2>
        <center>
            <div id="headingProfile">Updating Teacher Information</div>
        </center>
    </h2>

    <form class="form-horizontal" id="formShowProfile" action="/MVC/teacher/updateTeacherDescription" method="post">
        <div class="form-group">
            <label class="col-sm-5 control-label">Languages Able to Teach</label>
            <div class="col-sm-4">
                <?php foreach ($data['languageTeach'] as $language): ?>
                    <p class="form-control-static"><?= $language['language_name']; ?></p>
                <?php endforeach ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-5 control-label">Description</label>

            <div class="col-sm-4">
                <textarea class="form-control" id="description" name="description" rows="5"
                          required><?= $details['0']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4">
                <button name="action" type="submit" class="btn btn-default" value="Update">Update Description
                </button>
            </div>
        </div>
    </form>
</div>

</body>
</html>