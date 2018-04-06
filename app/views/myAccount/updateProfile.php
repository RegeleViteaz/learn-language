<!DOCTYPE html>
<html>
<head>
    <title>HomePage Logged In</title>
    <link rel="stylesheet" href="/MVC/css/myProfile.css" type="text/css">
</head>
<body>

<div class="container">
    <?php $details = $data['$personData'];?>

    <h2><center><div id="headingProfile">Updating Profile</div></center></h2>

    <form class="form-horizontal" id="formShowProfile" action="/MVC/account/updateProfile" method="post">
        <div class="form-group">
            <label class="col-sm-5 control-label">First Name</label>
            <div class="col-sm-4">
                <input class="form-control" type="text" name="firstName" id="firstName" value="<?= $details['first_name'];?>" required/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-5 control-label">Last Name</label>
            <div class="col-sm-4">
                <input class="form-control" type="text" name="lastName" id="lastName" value="<?= $details['last_name'];?>" required/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-5 control-label">Description</label>
            <div class="col-sm-4">
                <textarea class="form-control" id="description" name="description" rows="5" required><?= $details['description'];?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4">
                <button id="updateProfile" name="action" type="submit" class="btn btn-default" value="Update">Update My Profile
                </button>
            </div>
        </div>
    </form>
</div>

</body>
</html>