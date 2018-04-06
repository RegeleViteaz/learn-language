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
                    <th>Date</th>
                    <th>Choose</th>
                </tr>
                <?php foreach ($data['lessonData'] as $details): ?>
                    <form class="form-horizontal" id="form" action="/MVC/teacher/cancelLesson" method="post">
                        <tr>
                            <td>
                                <input hidden id="<?= $details['person_id']; ?> " name="chooseQuestion[]"
                                       value="  <?= $details['related_question']; ?>  "><?php echo $details['title']; ?>
                            </td>

                            <td>
                                <button type="submit" name="action" class="btn btn-primary btn-sm" value="Questions">Cancel
                                </button>
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