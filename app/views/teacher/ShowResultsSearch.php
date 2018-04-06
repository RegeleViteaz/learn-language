<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="/MVC/css/chooseLanguage.css" type="text/css">

    <meta charset="utf-8">
</head>
<body>


<div class="container">
    <div class="col-xs-12">
        <div id="chooseText">
            <font size="7">
                <center>Search Result</center>
            </font>
        </div>
        <div class="form-group">
            <table>
                <tr>
                    <th>Teacher</th>
                    <th>Language</th>
                    <th>Choice</th>
                </tr>
                <?php foreach ($data['teachers'] as $teachData): ?>
                    <form class="form-horizontal" id="form" action="/MVC/teacher/displayTeacherProfile" method="post">
                        <tr>
                            <td><?php echo $teachData['username_email'] ?></td>
                            <td><input hidden id="<?= $teachData['person_id']; ?> " name="teacher[]"
                                       value="  <?= $teachData['person_id']; ?>  "><?php echo $teachData['language_name'] ?>
                            </td>
                            <td>
                                <button id="continueButton" name="action" type="submit" class="btn btn-default"
                                        value="Search">Visit Profile
                                </button>
                            </td>
                        </tr>
                    </form>
                <?php endforeach ?>
            </table>
        </div>
        <div class="container">
            <div class="search col-sm-offset-5">
                <button type="button" onclick="location.href='/MVC/account/';" name="action" class="btn btn-primary">
                    Back
                </button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
