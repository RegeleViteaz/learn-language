<!DOCTYPE html>
<html>
<head>
    <title>Choose a language to learn and teach</title>
    <link rel="stylesheet" href="/MVC/css/chooseLanguage.css" type="text/css">

    <style type="text/css">
        .container {

        }
    </style>

</head>
<body>
<div id="chooseText">
    <font size="7">
        <?php if (($data['disableLearn']) == true) { ?>
            <center>Choose a language to teach</center>
        <?php } else { ?>
            <center>Choose a language to learn and teach</center>
        <?php } ?>
    </font>
</div>

<div class="container form-group text-center">
    <form class="form-horizontal" id="form" action="/MVC/home/languageChosen" method="post">
        <div class="form-group text-center">
            <table>
                <tr>
                    <th>Language</th>
                    <th>Learn</th>
                    <th>Teach</th>
                </tr>
                <?php foreach ($data['languageData'] as $languageData): ?>
                    <tr>
                        <td><?php echo $languageData['language_name'] ?></td>
                        <td>
                            <?php if (($data['disableLearn']) == true) { ?>
                                <input type="checkbox" disabled id="<?= $languageData['language_id']; ?> " name="learn[]"
                                       value="  <?= $languageData['language_id']; ?>  ">
                            <?php } else { ?>
                                <input type="checkbox" id="<?= $languageData['language_id']; ?> " name="learn[]"
                                       value="  <?= $languageData['language_id']; ?>  ">
                            <?php } ?>
                        </td>
                        <td>
                            <input type="checkbox" id="<?= $languageData['language_id']; ?> " name="teach[]"
                                   value="  <?= $languageData['language_id']; ?>  ">
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
            <button id="continueButton" name="action" type="submit" class="btn btn-default" value="RegisterLanguage">
                Continue
            </button>
        </div>
    </form>
</div>
</body>
</html>