<!DOCTYPE html>
<html>
<head>
    <title>Questions Found</title>

    <meta charset="utf-8">
    <link rel="stylesheet" href="/MVC/css/chooseLanguage.css" type="text/css">
</head>
<body>


<div class="container">
    <div class="col-xs-12">
        <h3>
            <center>
                <div id="headingProfile2">Questions Found</div>
            </center>
        </h3>
        <div class="container">
            <form class="form-search form-horizontal" action="/MVC/teacher/askQuestionFiller" method="post">
                <div class="search col-sm-offset-5">
                    <button type="submit" name="action" class="btn btn-primary btn-sm" value="Questions">Ask a Question</button>
                    <button type="button" onclick="location.href='/MVC/account/';" name="action" class="btn btn-primary btn-sm">
                        Back
                    </button>
                </div>

            </form>
        </div>
        <div class="container">
            <table>
                <tr>
                    <th>Question Information</th>
                    <th>Options</th>
                </tr>
                <?php foreach ($data['questionData'] as $details): ?>

                    <form class="form-horizontal" id="form" action="/MVC/teacher/lookspecificQuestion/<?= $details['related_question']; ?>" method="GET">
                        <tr>
                            <td><input hidden id="<?= $details['person_id']; ?> " name="chooseQuestion"
                                       value="<?= $details['related_question']; ?>"><?php echo $details['title']; ?>
                            </td>
                            <td>
                                <button type="submit" name="action" class="btn btn-primary btn-sm" value="Questions">Question Details
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