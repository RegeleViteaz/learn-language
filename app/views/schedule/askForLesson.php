<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/MVC/css/scheduletable.css" type="text/css">
    <title>Schedule</title>

    <meta charset="utf-8">
</head>
<body>
<?php $dateArray = $data['dateData']; ?>
<?php $scheduleData = $data['scheduleData']; ?>

<div class="container">
    <div class="col-xs-12">
        <h3>
            <center>
                <div id="headingProfile2">Schedule</div>
            </center>
        </h3>
        <div class="form-group text-center">
            <form class="form-inline" action="/MVC/teacher/InsertLessonPending" method="post">
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
                        <?php
                        $size = count($data['scheduleData']);
                        $count = 0;
                        foreach ($data['timeData'] as $timeTable): ?>
                            <tr>
                                <?php for ($i = 0; $i < 7; $i++): ?>
                                    <td>
                                        <?php 
                                            $scheduleData = ($count < $size?$data['scheduleData'][$count]:0); ?>
                                            <div class="btn-group" data-toggle="buttons">
                                                <?php if ($count < $size && ($scheduleData['time_id'] == $timeTable['time_id'] && $scheduleData['date'] == $dateArray[$i] && $scheduleData['status'] === '1')) { ?>
                                                    <label class="btn btn-success">
                                                        <input type="checkbox" id="<?= $timeTable['time_id']; ?>"
                                                               name="time[]"
                                                               value="<?= $timeTable['time_id']; ?> "
                                                               autocomplete="off"><?= $timeTable['time_hour']; ?>
                                                        <input type="checkbox" hidden id="<?= $dateArray[$i]; ?>"
                                                               name="date[]"
                                                               value="<?= $dateArray[$i]; ?>">
                                                        <input type="checkbox" hidden id="<?= $scheduleData['status']; ?> "
                                                               name="statusSchedule[]"
                                                               value="  <?= $scheduleData['status']; ?>  ">
                                                        <input type="checkbox" hidden id="<?= $scheduleData['person_id']; ?> "
                                                               name="teacherId"
                                                               value="  <?= $scheduleData['person_id']; ?>  ">
                                                    </label>

                                                <?php $count++; ?>
                                                <?php } elseif (($scheduleData['time_id'] == $timeTable['time_id'] && $scheduleData['date'] == $dateArray[$i] && $scheduleData['status'] === '2')) { ?>
                                                    <label class="btn btn-warning disabled">
                                                        <input type="checkbox" id="<?= $timeTable['time_id']; ?>"
                                                               name="time[]"
                                                               value="<?= $timeTable['time_id']; ?> "
                                                               autocomplete="off"><?= $timeTable['time_hour']; ?>
                                                        <input type="checkbox" hidden id="<?= $dateArray[$i]; ?>"
                                                               name="date[]"
                                                               value="<?= $dateArray[$i]; ?>">
                                                    </label>

                                                <?php $count++; ?>
                                                <?php } elseif (($scheduleData['time_id'] == $timeTable['time_id'] && $scheduleData['date'] == $dateArray[$i] && $scheduleData['status'] === '3')) { ?>
                                                    <label class="btn btn-danger disabled">
                                                        <input type="checkbox" id="<?= $timeTable['time_id']; ?>"
                                                               name="time[]"
                                                               value="<?= $timeTable['time_id']; ?> "
                                                               autocomplete="off"><?= $timeTable['time_hour']; ?>
                                                        <input type="checkbox" hidden id="<?= $dateArray[$i]; ?>"
                                                               name="date[]"
                                                               value="<?= $dateArray[$i]; ?>">
                                                    </label>

                                                <?php $count++; ?>     
                                                <?php } else { ?>
                                                    <label class="btn btn-primary disabled">
                                                        <input type="checkbox" id="<?= $timeTable['time_id']; ?>"
                                                               name="time[]"
                                                               value="<?= $timeTable['time_id']; ?> "
                                                               autocomplete="off"><?= $timeTable['time_hour']; ?>
                                                        <input type="checkbox" hidden id="<?= $dateArray[$i]; ?>"
                                                               name="date[]"
                                                               value="<?= $dateArray[$i]; ?>">
                                                        <input type="checkbox" hidden id="<?= $scheduleData['status']; ?> "
                                                               name="statusSchedule[]"
                                                               value="0">
                                                    </label>
                                                <?php } ?>
                                            </div>
                                        <?php //endforeach ?>
                                    </td>
                                <?php endfor ?>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="form-horizontal">
                        <button id="continueButton" name="action" type="submit" class="btn-lg btn-default"
                                value="setSchedule">
                            Continue
                        </button>

                    </div>
                </div>
            </form>
        </div>

    </div>


</body>
</html>