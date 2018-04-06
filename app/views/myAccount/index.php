<!DOCTYPE html>
<html>
<head>
    <title>HomePage Logged In</title>
    <link rel="stylesheet" href="/MVC/css/myProfile.css" type="text/css">
</head>
<body>

<div class="container">
    <?php $details = $data['$personData']; ?>

    <h2>
        <center>
            <div id="headingProfile"><?= $details['first_name']; ?> Profile</div>
        </center>
    </h2>

    <form class="form-horizontal" id="formShowProfile" action="/MVC/account/fillProfile" method="post">
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
        <div class="form-group">
            <div class="col-sm-offset-4">
                <button id="updateProfile" name="action" type="submit" class="btn btn-default" value="Update">Update My
                    Profile
                </button>
            </div>
        </div>
    </form>

    <h3>
        <center>
            <div id="teacherHeading">Teacher Section</div>
        </center>
    </h3>
    <?php if ($details['status'] === '1'): ?>
        <form class="form-horizontal" action="/MVC/teacher/stopTeaching" method="post">
            <div class="form-group">
                <div class="col-sm-offset-4">Do you want to stop being a teacher ?
                    <button id="updateProfile" name="action" type="submit" class="btn btn-danger" value="stopTeaching">
                        Yes
                    </button>
                </div>
            </div>
        </form>
    <?php elseif($details['status'] === '0'): ?>
        <form class="form-horizontal" action="/MVC/teacher/becomeTeacher" method="post">
            <div class="form-group">
                <div class="col-sm-offset-4">Do you want to be a teacher ?
                    <button id="updateProfile" name="action" type="submit" class="btn btn-success" value="becomeTeacher">
                        Yes
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
    <h3>
        <center>
            <div id="headingProfile2">My Messages</div>
        </center>
    </h3>
    <div class="container">
        <form class="form-search form-horizontal" action="/MVC/teacher/showAllMessages" method="post">
            <div class="search col-sm-offset-5">
                <button type="submit" name="action" class="btn btn-primary btn-sm" value="Questions">My Messages</button>
            </div>
        </form>
    </div>
    <h3>
        <center>
            <div id="headingProfile2">Schedule</div>
        </center>
    </h3>
        <?php if ($details['status'] === '1'): ?>
        <form class="form-horizontal" action="/MVC/teacher/setTeacherSchedule" method="post">
            <div class="form-group">
                <div class="col-sm-offset-4">
                    <button id="updateProfile" name="action" type="submit" class="btn btn-danger" value="setSchedule">
                        My Schedule
                    </button>
                </div>
            </div>
        </form>
        <?php endif; ?>
        <form class="form-horizontal" action="/MVC/teacher/showTeacherLessons" method="post">
            <div class="form-group">
                <div class="col-sm-offset-4">
                    <button id="updateProfile" name="action" type="submit" class="btn btn-danger" value="setSchedule">
                        My Lessons - Teacher
                    </button>
                </div>
            </div>
        </form>

        <form class="form-horizontal" action="/MVC/teacher/showLearnerLessons" method="post">
            <div class="form-group">
                <div class="col-sm-offset-4">
                    <button id="updateProfile" name="action" type="submit" class="btn btn-danger" value="setSchedule">
                        My Lessons - Learner
                    </button>
                </div>
            </div>
        </form>

</div>
</body>
</html>

