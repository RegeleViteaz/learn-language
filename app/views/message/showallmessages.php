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
                <div id="headingProfile2">Messages Found</div>
            </center>
        </h3>
        <div class="container">

            <form id="custom-search-form" class="form-search form-horizontal" action="/MVC/teacher/searchPersonToMessage" method="post">
                <div class="search col-sm-offset-5">
                    <input type="text" name="searchPerson" id="searchPerson" class="form-control input-sm"
                           maxlength="64" placeholder="Search"/>
                    <button type="submit" name="action" class="btn btn-primary btn-sm" value="SearchPerson">Search
                        for User
                    </button>
                    <button type="button" onclick="location.href='/MVC/account/myProfile';" name="action" class="btn btn-primary btn-sm">
                        Back
                    </button>
                </div>
            </form>

        </div>
        <div class="container">
            <table>
                <tr>
                    <th>Message</th>
                    <th>Respond</th>
                </tr>
                <?php foreach ($data['messageData'] as $details): ?>
                    <form class="form-horizontal" id="form" action="/MVC/teacher/lookspecificMessage/<?= $details['person_send_id']; ?>/<?= $details['person_receive_id']; ?>/<?= $details['related_message']; ?>" method="GET">
                        <tr>
                            <td><?php echo $details['message'] ?>

                            </td>
                            <td>
                                <button type="submit" name="action" class="btn btn-default" value="Message">Check Message
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