<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <meta charset="utf-8">
</head>
<body>


<div class="container">
    <div class="col-xs-12">
        <h2>Ask a Question</h2>

        <form action="/MVC/teacher/askquestion" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input class="form-control" type="text" id="title" name="title" required/>
            </div>

            <div class="form-group">
                <label for="message">Question</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div form-group>
            <div class="form-group">
                <button name="action" type="submit" class="btn btn-default" value="PostQuestion">Ask a Question</button>
        </form>
    </div>
</div>

</body>
</html>
