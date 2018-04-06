<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>

<div class="container">
    <div>
        <form class="form-horizontal" id="form" action="/MVC/home/registration" method="post">
            <div class="form-group">
                <label for="username">Email</label>
                <input class="form-control" type="text" name="username" id="username" required/>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" type="password" name="password" id="password" required/>
            </div>
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input class="form-control" type="text" name="firstName" id="firstName" required/>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input class="form-control" type="text" name="lastName" id="lastName" required/>
            </div>
            <div class="form-group">
                <label class=".radio-inline" for="M">Male</label>
                <input type="radio" name="gender" id="M" value="Male" required/>
                <label class=".radio-inline" for="F">Female</label>
                <input type="radio" name="gender" id="F" value="Female" required/>
            </div>
            <div class="form-group">
                <label for="nativeLanguage">Native Language</label>

                <select name="languageList" required>
                    <option value=""></option>
                    <?php foreach($data['languageData'] as $languageData): ?>
						<option class="form-control" value="  <?= $languageData['language_id']; ?>  ">  <?= $languageData['language_name']; ?>   </option>
					<?php endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <select name="countryList" required>
                    <option value=""></option>
                    <?php foreach($data['countryData'] as $countryData): ?>
						<option class="form-control" value=" <?= $countryData['country_id']; ?>">  <?= $countryData['country_name']; ?>  </option>
					<?php endforeach ?>
                </select>
            </div>
            <input class="btn btn-default" type="submit" name="action" value="Register">
        </form>
    </div>
</div>
</body>
</html>