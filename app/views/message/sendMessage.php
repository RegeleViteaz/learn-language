<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <meta charset="utf-8">
</head>
<body>


<div class="container">
    <div class="col-xs-12">
        <h2>Send a Message</h2>

        <form action="/MVC/teacher/sendMessage" method="post">
            <?php foreach ($data['messageData'] as $details): ?>
            <div class="form-group">
                <label for="user">Send to: <?php echo $details['username_email'] ?></label>
                <input hidden id="<?= $details['person_id']; ?> " name="user" value="<?= $details['person_id']; ?>">
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div form-group>
            <div class="form-group">
                <button name="action" type="submit" class="btn btn-default" value="PostQuestion">Send Message</button>
                <?php endforeach ?>
        </form>
        <div class="container">
            <div class="search col-sm-offset-5">
                <button type="button" onclick="location.href='/MVC/account/myProfile';" name="action" class="btn btn-primary btn-sm">
                    Back
                </button>
            </div>
        </div>
    </div>
</div>
</body>
</html>