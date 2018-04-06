<!DOCTYPE html>
<html>
<head>
    <title>HomePage Logged In</title>
    <link rel="stylesheet" href="/MVC/css/myProfile.css" type="text/css">
</head>
<body>

<div class="container">
    <?php $details = $data['$personData'];?>

    <h2><center><div id="headingProfile">Teacher Profile</div></center></h2>

    <form class="form-horizontal" id="formShowProfile">
        <div class="form-group">
            <label class="col-sm-5 control-label">First Name</label>

            <div class="col-sm-4">
                <p class="form-control-static"><?= $details['first_name']; ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-5 control-label">Last Name</label>

            <div class="col-sm-4">
                <p class="form-control-static"><?= $details['last_name']; ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-5 control-label">Description</label>

            <div class="col-sm-4">
                <p class="form-control-static"><?= $details['description']; ?></p>
            </div>
        </div>
    </form>
    <form class="form-horizontal" action="/MVC/teacher/seeTeacherSchedule" method="post">
        <div class="form-group">
            <div class="col-sm-offset-4">
                <input hidden id="<?= $details['person_id']; ?> " name="teacher"
                       value="  <?= $details['person_id']; ?>  ">
                <button id="updateProfile" name="action" type="submit" class="btn btn-success" value="setSchedule">
                    Check Teacher Schedule
                </button>
            </div>
        </div>
    </form>
    <form class="form-horizontal" action="/MVC/teacher/showAllReviews" method="post">
        <div class="form-group">
            <div class="col-sm-offset-4">
                <input hidden id="<?= $details['person_id']; ?> " name="teacherId"
                       value="  <?= $details['person_id']; ?>  ">
                <button id="updateProfile" name="action" type="submit" class="btn btn-danger" value="seeReviews">
                    Leave Review
                </button>
            </div>
        </div>
    </form>
    <form class="form-horizontal" action="/MVC/account/" method="post">
        <div class="form-group">
            <div class="col-sm-offset-4">
                <button type="button" onclick="location.href='/MVC/account/';" name="action" class="btn btn-primary">
                    Back
                </button>
            </div>
        </div>
    </form>
</div>

</body>
</html>