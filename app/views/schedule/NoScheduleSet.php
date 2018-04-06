<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/MVC/css/scheduletable.css" type="text/css">
    <title>Schedule</title>

    <meta charset="utf-8">
</head>
<body>
<?php $dateArray = $data['dateData']; ?>

<div class="container">
    <div class="col-xs-12">
        <h3>
            <center>
                <div id="headingProfile2">Schedule</div>
            </center>
        </h3>
        <div class="form-group text-center">
            <form class="form-inline" action="/MVC/teacher/setMyScheduleTeacher" method="post">
                <div class="form-group text-center">
                    <table style="float: left;">
                        <thead>
                        <tr>
                            <?php for ($i = 0; $i < 7; $i++): ?>
                                <th>
                                    <?php echo $dateArray[$i]; ?>
                                </th>
                            <?php endfor ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data['timeData'] as $timeTable): ?>
                            <tr>
                                <?php for ($i = 0; $i < 7; $i++): ?>
                                    <td>
                                        <div class="btn-group" data-toggle="buttons">
                                            <label class="btn btn-primary">
                                                <input type="checkbox" id="<?= $timeTable['time_id']; ?>" name="time[]"
                                                       value="<?= $timeTable['time_id']; ?> "
                                                       autocomplete="off"><?= $timeTable['time_hour']; ?>
                                                <input type="hidden" id="<?= $dateArray[$i]; ?>" name="date[]"
                                                       value="<?= $dateArray[$i]; ?>">
                                            </label>
                                        </div>
                                    </td>
                                <?php endfor ?>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-horizontal">
                    <button id="continueButton" name="action" type="submit" class="btn-lg btn-default"
                            value="setSchedule">
                        Continue
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>



