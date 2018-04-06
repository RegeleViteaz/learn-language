<?php

require_once 'app/views/header.php';

class account extends Controller
{
    /*
     * Displays the website homepage when the user logs in.
     * Select the username of the user to display it in a welcome message
     */
    public function index()
    {
        $person = $this->model('Person');
        if (LoginCore::isLoggedIn()) {
            $handler = ConnectionDatabase();

            $sqlGetUsername = "SELECT username_email
                               FROM person WHERE person_id = :person_id";

            $query = $handler->prepare($sqlGetUsername);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->execute();
            $number_of_rows = $query->rowCount();

            $person->username = $query->fetchAll(PDO::FETCH_COLUMN, 0);

            if ($number_of_rows === 1) {
                foreach ($person->username as $data) {
                    $this->view('home/index', ['username' => $data]);
                }
            } else {
                echo "You have more than 1 user with the same username/email";
            }
        }
    }

    /*
     * Select the user's information from the database using his session_id and display it
     */
    public function myProfile()
    {
        if (LoginCore::isLoggedIn()) {
            $handler = ConnectionDatabase();

            $sqlGetPerson = "SELECT first_name, last_name, status, description FROM person WHERE person_id = :person_id";

            $query = $handler->prepare($sqlGetPerson);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->execute();
            $number_of_rows = $query->rowCount();

            $personData = $query->fetchAll(PDO::FETCH_ASSOC);

            if ($number_of_rows === 1) {

                foreach ($personData as $data) {
                    $this->view('myAccount/index', ['$personData' => $data]);
                }
            } else {
                echo "You have more than 1 user with the same username/email";
            }
        }
    }

    /*
     * Populate the various fields using values from the database so that the user can edit his information.
     */
    public function fillProfile()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'Update') {
            if (LoginCore::isLoggedIn()) {

                $handler = ConnectionDatabase();

                $sqlGetPersonInfo = "SELECT first_name, last_name, status, description FROM person WHERE person_id = :person_id";

                $query = $handler->prepare($sqlGetPersonInfo);
                $query->bindValue(':person_id', $_SESSION['person_id']);
                $query->execute();
                $number_of_rows = $query->rowCount();

                $personData = $query->fetchAll(PDO::FETCH_ASSOC);

                if ($number_of_rows === 1) {

                    foreach ($personData as $data) {
                        $this->view('myAccount/updateProfile', ['$personData' => $data]);
                    }
                } else {
                    echo "You have more than 1 user with the same username/email";
                }
            }
        }
    }

    /*
     * Update the user's information and profile depending on his input.
     */
    public function updateProfile()
    {
        $person = $this->model('Person');
        if (isset($_POST['action']) && $_POST['action'] == 'Update') {
            $person->first_name = $_POST['firstName'];
            $person->last_name = $_POST['lastName'];
            $person->description = $_POST['description'];

            $handler = ConnectionDatabase();
            $sqlUpdateProfile = "UPDATE person SET first_name = :first_name, last_name = :last_name, description = :description WHERE person_id = :person_id";

            $query = $handler->prepare($sqlUpdateProfile);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->bindValue(':first_name', $person->first_name);
            $query->bindValue(':last_name', $person->last_name);
            $query->bindValue(':description', $person->description);
            $query->execute();
            $number_of_rows = $query->rowCount();

            if ($number_of_rows === 1) {
                header('location: /MVC/account/myProfile');
            }
        }
        header('location: /MVC/account/myProfile');
    }
}

?>