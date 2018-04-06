<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <meta charset="utf-8">
</head>
<body>


<div class="container">
    <div class="col-xs-12">
        <h3>
            <center>
                <div id="headingProfile2">Specific Message</div>
            </center>
        </h3>
        <div class="container">
            <div class="search col-sm-offset-5">
                <button type="button" onclick="location.href='/MVC/account/myProfile';" name="action" class="btn btn-primary btn-sm">
                    Back
                </button>
            </div>
        </div>

        <?php $displayUsernameOnce = 0; ?>

        <?php foreach ($data['messageData'] as $details): ?>
        <form class="form-horizontal" id="formShowProfile" action="/MVC/teacher/answerMessage/<?= $data['personReceiveId']; ?>/<?= $data['personSendId']; ?>" method="post">
            <?php if ($displayUsernameOnce < 1): ?>
                <div class="form-group">
                    <label class="col-sm-5 control-label">Original Receiver</label>
                    <div class="col-sm-4">
                        <p class="form-control-static"><?= $details['username_email']; ?></p>
                    </div>
                </div>
                <?php $displayUsernameOnce++; endif; ?>
            <div class="form-group">
                <label class="col-sm-5 control-label"><?= $details['username_email']; ?></label>

                <div class="col-sm-4">
                    <p class="form-control-static"><?= $details['message']; ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label"></label>
            </div>
            <?php endforeach ?>
            <div class="form-group">
                <label class="col-sm-5 control-label">Answer</label>

                <div class="col-sm-4">
                    <textarea class="form-description" name="message" rows="5" required></textarea>
                    <input hidden id="<?= $data['relatedMessage']; ?> " name="relatedMessage"
                           value="<?= $data['relatedMessage']; ?>">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-4">
                    <button id="updateProfile" name="action" type="submit" class="btn btn-default" value="Answer">Answer
                        Message
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>