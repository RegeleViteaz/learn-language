<!DOCTYPE html>
<html>
<head>
    <title>Lessons Found</title>

    <meta charset="utf-8">
    <link rel="stylesheet" href="/MVC/css/chooseLanguage.css" type="text/css">
</head>
<body>

<div class="container">
    <div class="col-xs-12">
        <h3>
            <center>
                <div id="headingProfile2">Lessons Found</div>
            </center>
        </h3>
        <div class="container">
            <table>
                <tr>
                    <th>Date - Time - Learner</th>
                    <th>Options</th>
                </tr>
                    <?php
                    $size = count($data['timeUserData']);
                    $count = 0; ?>
                <?php foreach ($data['lessonData'] as $details): ?>
                    <form class="form-horizontal" id="form" action="/MVC/teacher/decisionTeacherLesson" method="post">
                        <tr>
                            <td>
                                <?php
                                $timeUserData = ($count < $size?$data['timeUserData'][$count]:0); ?>


                                <input hidden id="<?= $details['date'];?>" name="lessonDate"
                                       value="  <?= $details['date'];?>"><?php echo $details['date']; ?>
                                <input hidden id="<?= $details['client_id'];?>" name="clientId"
                                       value="  <?= $details['client_id'];?>">
                                <input hidden id="<?= $details['lesson_start_time'];?>" name="lessonTime"
                                       value="  <?= $details['lesson_start_time'];?>"><?php echo " || "; echo $timeUserData['time_hour']; ?>
                                <input hidden id="<?= $details['language_id']; ?>" name="language"
                                       value="  <?= $details['language_id']; ?>"><?php echo " || "; echo $timeUserData['username_email']; ?>
                                <input hidden id="<?= $details['status']; ?>" name="status"
                                       value="  <?= $details['status']; ?>">

                                <?php $count++; ?>
                            </td>
                            <td>
                                <?php if (($details['status'] == '1')) { ?>
                                    <button type="submit" name="action" class="btn btn-success btn-sm" value="Confirm">
                                        Confirm
                                    </button>
                                    <button type="submit" name="action" class="btn btn-danger btn-sm" value="Cancel">
                                        Cancel
                                    </button>
                                <?php } elseif (($details['status'] == '2')) { ?>
                                    <button type="submit" name="action" class="btn btn-danger btn-sm" value="Cancel">
                                        Cancel
                                    </button>
                                <?php } elseif (($details['status'] == '3')) { ?>
                                    <button type="button" onclick="location.href='/MVC/account/myProfile';" name="action" class="btn btn-info btn-sm" value="Cancel">

                                        Lesson Completed
                                    </button>
                                <?php } else { ?>

                                    <button type="button" onclick="location.href='/MVC/account/myProfile';" name="action" class="btn btn-warning btn-sm" value="Cancel">
                                        Lesson Canceled
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                    </form>
                <?php endforeach ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>