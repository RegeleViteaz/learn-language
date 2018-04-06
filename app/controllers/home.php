<?php

require_once 'app/views/header.php';

class home extends Controller
{
    /*
     * Redirects the user to login when he accesses the website without being logged in
     */
    public function index()
    {
        $this->view('SignIn/login');
    }

    /*
     * Enables the user to login into his account using his valid credentials
     * If it is the first time the user login in, he will be redirected to chooseLanguage view
     * where he can choose the language that he wants to learn and/or teach.
     */
    public function login()
    {
        $person = $this->model('Person');
        if (isset($_POST['action']) && $_POST['action'] == 'Login') {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $message = '"Please Enter your Email Address and Password"';
                echo '<script language=javascript> alert(' . $message . ') </script>';
                $this->view('SignIn/login');
            } else {
                $username = $_POST['email'];
                $password = $_POST['password'];

                // Select the user's information depending on his username and check if its valid
                $handler = ConnectionDatabase();
                $sqlFindUser = "SELECT person_id, username_email, password
                                FROM person
                                WHERE username_email = :username";
                $query = $handler->prepare($sqlFindUser);
                $query->bindValue(':username', $username);
                $query->execute();
                $number_of_rows = $query->rowCount();
                $resultsPerson = $query->fetch(PDO::FETCH_ASSOC);

                if ($number_of_rows === 1) {
                    $person->person_id = $resultsPerson['person_id'];
                } else {
                    $message = ' "Enter a valid username" ';
                    echo '<script language=javascript> alert(' . $message . ') </script>';
                    $this->view('SignIn/logIn');
                }
                // Verify that the password entered by the user is correct
                if (password_verify($password, $resultsPerson['password'])) {
                    $_SESSION['person_id'] = $person->person_id;

                    $sqlFindUser = "SELECT person_id
                                    FROM language_skill
                                    WHERE person_id = :person_id";
                    $query = $handler->prepare($sqlFindUser);
                    $query->bindValue(':person_id', $_SESSION['person_id']);
                    $query->execute();
                    $number_of_rows = $query->rowCount();

                    // If it is not the first time the user logs in, redirect to the website homepage
                    // Else send the user to chooseLanguage view so he chooses a language to learn and/or teach
                    if ($number_of_rows > 0) {
                        header('location: /MVC/account');
                    } else {
                        $sqlAllLanguages = "SELECT language_id, language_name
                                            FROM language";
                        $query = $handler->query($sqlAllLanguages);
                        $number_of_rows_all = $query->rowCount();
                        $resultsLanguage = $query->fetchAll(PDO::FETCH_ASSOC);

                        $sqlFindUser = "SELECT language_learning
                                        FROM language_skill
                                        WHERE person_id = :person_id AND language_learning = :language_learning";
                        $query = $handler->prepare($sqlFindUser);
                        $query->bindValue(':person_id', $_SESSION['person_id']);
                        $query->bindValue(':language_learning', 1);
                        $query->execute();
                        $number_of_rows_check = $query->rowCount();
                        if ($number_of_rows_check > 0) {
                            $disableLearnOption = true;
                            $this->view('Registration/chooseLanguage', ['languageData' => $resultsLanguage, 'disableLearn' => $disableLearnOption]);
                        } elseif ($number_of_rows_all > 0) {
                            $disableLearnOption = false;
                            $this->view('Registration/chooseLanguage', ['languageData' => $resultsLanguage, 'disableLearn' => $disableLearnOption]);
                        } else {
                            echo "Error login sql selecting all languages";
                        }
                    }
                } else {
                    $message = ' "Incorrect Password" ';
                    echo '<script language=javascript> alert(' . $message . ') </script>';
                    $this->view('SignIn/logIn');
                }
            }
        } else {
            $message = '"Please Enter your Email Address and Password"';
            echo '<script language=javascript> alert(' . $message . ') </script>';
            $this->view('SignIn/logIn');
        }
    }

    /*
     * Send country and language data from the database to the registration view in order to populate the dropdownlists in the form
     */
    public function registerDataDropDownLists()
    {
        $handler = ConnectionDatabase();
        $sqlCountry = "SELECT country_id, country_name
                       FROM country";
        $query = $handler->query($sqlCountry);
        $resultsCountry = $query->fetchAll(PDO::FETCH_ASSOC);

        $sqlLanguage = "SELECT language_id, language_name
                        FROM language";
        $query = $handler->query($sqlLanguage);
        $resultsLanguage = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultsCountry) > 0 && count($resultsLanguage) > 0) {
            $this->view('Registration/registration', ['countryData' => $resultsCountry, 'languageData' => $resultsLanguage]);
        }
    }

    /*
     * With the user input, create a new account that can only be accessed with the credentials created by that specific user
     */
    public function registration()
    {
        $person = $this->model('Person');
        if (isset($_POST['action']) && $_POST['action'] == 'Register') {
            $person->username = $_POST['username'];
            $person->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $person->first_name = $_POST['firstName'];
            $person->last_name = $_POST['lastName'];
            $person->gender = $_POST['gender'];
            $person->native_language = $_POST['languageList'];
            $person->country = $_POST['countryList'];

            $handler = ConnectionDatabase();
            $sqlInsertPerson = "INSERT INTO person (username_email, password, password_reset_token, first_name, last_name, gender, status, description, native_language_id, country_id)
								VALUES(:username, :password, :password_reset_token, :first_name, :last_name, :gender, :status, :description, :native_language_id, :country_id)";
            $query = $handler->prepare($sqlInsertPerson);
            $query->bindValue(':username', $person->username, PDO::PARAM_STR);
            $query->bindValue(':password', $person->password, PDO::PARAM_STR);
            $query->bindValue(':password_reset_token', null, PDO::PARAM_NULL);
            $query->bindValue(':first_name', $person->first_name, PDO::PARAM_STR);
            $query->bindValue(':last_name', $person->last_name, PDO::PARAM_STR);
            $query->bindValue(':gender', $person->gender, PDO::PARAM_STR);
            $query->bindValue(':status', 0, PDO::PARAM_INT);
            $query->bindValue(':description', null, PDO::PARAM_NULL);
            $query->bindValue(':native_language_id', $person->native_language, PDO::PARAM_INT);
            $query->bindValue(':country_id', $person->country, PDO::PARAM_INT);

            $query->execute();

            if ($query) {
                $this->view('SignIn/logIn');
            }
        }
    }

    /*
     * Insert the languages that the user wants to learn and/or teach in the language_skill table
     */
    public function languageChosen()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'RegisterLanguage') {
            $handler = ConnectionDatabase();

            // Insert the languages that have only been checked in the learn section
            if (isset($_POST['learn'])) {
                $learnArray = $_POST['learn'];

                $sqlInsertLanguageSkill = "INSERT INTO language_skill (person_id, language_id, language_learning, language_teaching)
                                               VALUES(:person_id, :language_id, :language_learning, :language_teaching)";

                foreach ($learnArray as $dataLearn) {
                    $query = $handler->prepare($sqlInsertLanguageSkill);
                    $query->bindValue(':person_id', $_SESSION['person_id']);
                    $query->bindValue(':language_id', $dataLearn);
                    $query->bindValue(':language_learning', 1);
                    $query->bindValue(':language_teaching', 0);
                    $query->execute();
                }
            }
            // Update the language_skill table when the same language has been checked to learn and to teach by the user.
            if (isset($_POST['teach']) && isset($_POST['learn'])) {
                $teachArray = $_POST['teach'];

                $sqlUpdateTeach = "UPDATE language_skill
                                   SET language_teaching = :language_teaching
                                   WHERE person_id = :person_id AND language_id = :language_id AND language_learning = :language_learning";

                for ($i = 0; $i < count($teachArray); $i++) {
                    $query = $handler->prepare($sqlUpdateTeach);
                    $query->bindValue(':person_id', $_SESSION['person_id']);
                    $query->bindValue(':language_id', $teachArray[$i]);
                    $query->bindValue(':language_learning', 1);
                    $query->bindValue(':language_teaching', 1);
                    $query->execute();
                }
            }

            // Insert the languages that have only been chosen to teach.
            if (isset($_POST['teach'])) {
                $teachArray = $_POST['teach'];

                $sqlInsertLanguageSkill = "INSERT INTO language_skill (person_id, language_id, language_learning, language_teaching)
                                           VALUES(:person_id, :language_id, :language_learning, :language_teaching)";

                $sqlFindLanguage = "SELECT language_id
                                FROM language_skill
                                WHERE person_id = :person_id AND language_learning = :language_learning AND language_id = :language_id";

                // Loop to select all the values that have been chosen to learn and to teach
                // Remove empty arrays from the $language_id variable and assign the result to $NoEmptyArray_language_id
                $NoEmptyArray_language_id = array();
                foreach ($teachArray as $dataTeach) {
                    $query = $handler->prepare($sqlFindLanguage);
                    $query->bindValue(':person_id', $_SESSION['person_id']);
                    $query->bindValue(':language_learning', 1);
                    $query->bindValue(':language_id', $dataTeach);
                    $query->execute();
                    $language_id = $query->fetchAll(PDO::FETCH_COLUMN, 0);
                    if (count($language_id) != 0) {
                        $NoEmptyArray_language_id[] = $language_id;
                    }
                }

                // Convert the $NoEmptyArray_language_id 2d array to a one dimensional array and assign the result to $language_1d_array
                $language_1d_array = array();
                for ($i = 0; $i < count($NoEmptyArray_language_id); $i++) {
                    for ($j = 0; $j < count($NoEmptyArray_language_id[$i]); $j++) {
                        $language_1d_array[] = $NoEmptyArray_language_id[$i][$j];
                    }
                }

                //Get the languages that have only been selected to teach
                $result = array_values(array_diff(array_map('trim', $teachArray), $language_1d_array));

                // Insert the teach only languages in the language_skill table
                foreach ($result as $teach_id) {
                    $query = $handler->prepare($sqlInsertLanguageSkill);
                    $query->bindValue(':person_id', $_SESSION['person_id']);
                    $query->bindValue(':language_id', $teach_id);
                    $query->bindValue(':language_learning', 0);
                    $query->bindValue(':language_teaching', 1);
                    $query->execute();
                }

                // Update the language_skill table with the language chosen to teach by the user but that was already inserted with the learn option selected.
                // This will be used when a user tries to become a teacher only after having selected languages to learn when he logged in for the first time.
                $sqlUpdateTeach = "UPDATE language_skill
                                       SET language_teaching = :language_teaching
                                       WHERE person_id = :person_id AND language_id = :language_id AND language_learning = :language_learning";

                foreach ($teachArray as $dataTeach) {
                    $query = $handler->prepare($sqlUpdateTeach);
                    $query->bindValue(':language_teaching', 1);
                    $query->bindValue(':person_id', $_SESSION['person_id']);
                    $query->bindValue(':language_id', $dataTeach);
                    $query->bindValue(':language_learning', 1);
                    $query->execute();
                }
            }
            header('location:/MVC/account');
        }
    }

    /*
     * Logout the user from his account and redirect him to the homepage
     */
    public function logout()
    {
        if (LoginCore::logout()) {
            header('location:/MVC/home/index');
        }
    }

    /*
     * Implementation not completed for this userstory
     * Placeholder for resetting a user's password
     */
    public function forgotPassword()
    {

        if (isset($_POST['action']) && $_POST['action'] == 'recover') {
            $email_address = $_POST['email'];

            /*	$headers = 'From: webmaster@example.com' . "\r\n" .
                           'Reply-To: webmaster@example.com';

                mail($email_address, 'Recover_Password', 'isnrignergigib', $headers);*/
        }
        $this->view('SignIn/forgotPassword');
    }
}

?>