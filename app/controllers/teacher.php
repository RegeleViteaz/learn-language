<?php

require_once 'app/views/header.php';

class teacher extends Controller
{
    /*
     * Redirect the user to his profile
     */
    public function index()
    {
        $this->view('myAccount/index');
    }

    /*
     * Enables the user to become a teacher.
     * If the user did not select a language he can teach when he registered, he will be prompted to select one again.
     */
    public function becomeTeacher()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'becomeTeacher') {

            $handler = ConnectionDatabase();

            // Select the user's description of his abilities
            $sqlGetDescription = "SELECT description
                                  FROM person
                                  WHERE person_id = :person_id";

            $query = $handler->prepare($sqlGetDescription);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->execute();
            $number_of_rows_description = $query->rowCount();
            $descriptionData = $query->fetchAll(PDO::FETCH_COLUMN, 0);

            // Select the language names that the user chose to teach
            $sqlShowTeachLanguage = "SELECT language.language_name
                                     FROM language_skill
                                     INNER JOIN language ON language_skill.language_id=language.language_id
                                     WHERE language_skill.language_teaching = :language_teaching AND language_skill.person_id = :person_id ";
            $query = $handler->prepare($sqlShowTeachLanguage);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->bindValue(':language_teaching', 1);
            $query->execute();
            $number_of_rows_language = $query->rowCount();
            $language = $query->fetchAll(PDO::FETCH_ASSOC);

            //  If the user chose languages to teach when he first logged in, send the user's description and the language names to the view
            if ($number_of_rows_description > 0 && $number_of_rows_language > 0) {
                $this->view('myAccount/updateTeacherInfo', ['descriptionData' => $descriptionData, 'languageTeach' => $language]);
            }

            // If not, send the user to choose only languages to teach
            if ($number_of_rows_language == 0) {
                $sqlLanguage = "SELECT language_id, language_name
                                FROM language";
                $query = $handler->query($sqlLanguage);
                $number_of_rows_all = $query->rowCount();
                $resultsLanguage = $query->fetchAll(PDO::FETCH_ASSOC);

                $sqlFindUserLearning = "SELECT language_learning
                                        FROM language_skill
                                        WHERE person_id = :person_id AND language_learning = :language_learning";
                $query = $handler->prepare($sqlFindUserLearning);
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
                }
            }
        }
    }

    /*
     * Update the teacher profile description
     */
    public function updateTeacherDescription()
    {
        $person = $this->model('Person');
        if (isset($_POST['action']) && $_POST['action'] == 'Update') {
            $person->description = $_POST['description'];
            $handler = ConnectionDatabase();
            $sqlUpdateProfileDescription = "UPDATE person
                                            SET description = :description, status = :status
                                            WHERE person_id = :person_id";

            $query = $handler->prepare($sqlUpdateProfileDescription);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->bindValue(':description', $person->description);
            $query->bindValue(':status', 1);
            $query->execute();
            $number_of_rows = $query->rowCount();

            if ($number_of_rows === 1) {
                header('location: /MVC/account/myProfile');
            }
        }
    }

    /*
     * Enables the user to stop being a teacher by updating his status, deleting his lessons and deleting his availabilities from the schedule
     * If he chooses to do so, when learners search for a language to learn, the user will not appear in the search results.
     */
    public function stopTeaching()
    {
        $person = $this->model('Person');
        if (isset($_POST['action']) && $_POST['action'] == 'stopTeaching') {
            $person->description = $_POST['description'];
            $handler = ConnectionDatabase();
            $sqlUpdateProfile = "UPDATE person
                                 SET status = :status
                                 WHERE person_id = :person_id";

            $query = $handler->prepare($sqlUpdateProfile);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->bindValue(':status', 0);
            $query->execute();
            $number_of_rows = $query->rowCount();

            if ($number_of_rows === 1) {
                // Delete every lesson that has not been completed (status 3)
                $sqlDeleteLesson = "DELETE FROM lesson
                                    WHERE teacher_id = :teacher_id AND status != 3";

                $query = $handler->prepare($sqlDeleteLesson);
                $query->bindValue(':teacher_id', $_SESSION['person_id']);
                $query->execute();

                $sqlDeleteSchedule = "DELETE FROM schedule
                                      WHERE person_id = :person_id";
                $query = $handler->prepare($sqlDeleteSchedule);
                $query->bindValue(':person_id', $_SESSION['person_id']);
                $query->execute();

                header('location: /MVC/account/myProfile');
            }
        }
    }

    /*
     * Search for a teacher depending on the languages being taught and the language being searched
     */
    public function searchTeacherLanguage()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'Search') {
            if (isset($_POST['searchLanguage'])) {
                $language = $_POST['searchLanguage'];

                $handler = ConnectionDatabase();

                // Select all the teachers except the user itself
                $sqlGetTeacher = "SELECT person_id
                                  FROM person WHERE status = :status
                                  AND person_id != :person_id";

                $query = $handler->prepare($sqlGetTeacher);
                $query->bindValue(':status', 1);
                $query->bindValue(':person_id', $_SESSION['person_id']);
                $query->execute();
                $number_of_rows_teacher = $query->rowCount();
                $resultsLanguageArray = $query->fetchAll(PDO::FETCH_ASSOC);

                // If teachers exist, select the language they can teach, their id and their username.
                // Send the selected data to the view
                // Else display an error message
                if ($number_of_rows_teacher > 0) {
                    $sqlgetLanguageInfo = "SELECT language.language_name, person.person_id, person.username_email FROM language_skill
                                           INNER JOIN person ON language_skill.person_id = person.person_id
                                           INNER JOIN language on language_skill.language_id = language.language_id
                                           WHERE language_skill.language_teaching = :language_teaching AND person.status = :person_status AND (language.language_name LIKE concat('%', :language_name, '%')) AND person.person_id != :person_id";

                    $number_of_rows_language = 0;
                    foreach ($resultsLanguageArray as $personId) {
                        $query = $handler->prepare($sqlgetLanguageInfo);
                        $query->bindValue(':language_teaching', 1);
                        $query->bindValue(':person_status', 1);
                        $query->bindValue(':language_name', $language);
                        $query->bindValue(':person_id', $_SESSION['person_id']);
                        $query->execute();
                        $number_of_rows_language = $query->rowCount();
                    }
                    $resultsLanguage = $query->fetchAll(PDO::FETCH_ASSOC);
                    if ($number_of_rows_language > 0) {
                        $this->view('teacher/ShowResultsSearch', ['teachers' => $resultsLanguage]);
                    } else {
                        $message = "No Teachers Found for that language";
                        $this->view('teacher/NoTeachersFound', ['messageNotFound' => $message]);
                    }
                } else {
                    $message = "No Teachers Found";
                    $this->view('teacher/NoTeachersFound', ['messageNotFound' => $message]);
                }
            }
        }
    }

    /*
     * Display the teacher's profile with the user's values
     */
    public function displayTeacherProfile()
    {
        $learnArray = ($_POST['teacher']);
        if (LoginCore::isLoggedIn()) {

            $handler = ConnectionDatabase();

            $sqlGetPersonInfo = "SELECT person_id, first_name, last_name, status, description
                                 FROM person
                                 WHERE person_id = :person_id";

            foreach ($learnArray as $personId) {
                $query = $handler->prepare($sqlGetPersonInfo);
                $query->bindValue(':person_id', $personId);
                $query->execute();
            }
            $number_of_rows = $query->rowCount();
            $personData = $query->fetchAll(PDO::FETCH_ASSOC);

            if ($number_of_rows === 1) {
                foreach ($personData as $data) {
                    $this->view('teacher/showteacherprofile', ['$personData' => $data]);
                }
            }
        }
    }

    /*
     * Display all questions asked on the forum
     */
    public function showAllQuestions()
    {
        $handler = ConnectionDatabase();
        $sqlallQuestions = "SELECT *
                            FROM question
                            WHERE title IS NOT NULL
                            ORDER BY question_id DESC";
        $query = $handler->prepare($sqlallQuestions);
        $query->execute();
        $number_of_rows = $query->rowCount();
        $resultsQuestions = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($number_of_rows == 0) {
            $this->view('question/shownoquestions');
        }
        if ($number_of_rows > 0) {
            $this->view('question/showallquestions', ['questionData' => $resultsQuestions]);

        }
    }

    /*
     * Filler method to redirect the user to a form where he can ask a question
     */
    public function askQuestionFiller()
    {
        if (isset($_POST['action'])) {
            $this->view('question/askquestion');
        }
    }

    /*
     * Ask a question in the forum depending on the user input and insert the data into the database
     */
    public function askquestion()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'PostQuestion') {
            $handler = ConnectionDatabase();
            $title = $_POST['title'];
            $message = $_POST['message'];

            // Insert the question into the database
            $sqlInsertQuestion = "INSERT INTO question (title, message, person_id, related_question)
                                  VALUES(:title, :message, :person_id, :related_question)";
            $query = $handler->prepare($sqlInsertQuestion);
            $query->bindValue(':title', $title);
            $query->bindValue(':message', $message);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->bindValue(':related_question', 0);
            $query->execute();

            $number_of_rows = $query->rowCount();

            // Select the question that was just inserted and assign an ID to the question
            // That value will be used to display the question and any provided answer related to it
            if ($number_of_rows > 0) {

                $sqlgetId = "SELECT question_id FROM question WHERE related_question = :related_question";
                $query = $handler->prepare($sqlgetId);
                $query->bindValue(':related_question', 0);
                $query->execute();
                $questionId = $query->fetch(PDO::FETCH_COLUMN, 0);

                $sqlUpdateQuestion = "UPDATE question
                                      SET related_question = question_id
                                      WHERE person_id = :person_id AND question_id = :question_id";

                $query = $handler->prepare($sqlUpdateQuestion);
                $query->bindValue(':person_id', $_SESSION['person_id']);
                $query->bindValue(':question_id', $questionId);
                $query->execute();
                $number_of_rows2 = $query->rowCount();
                if ($number_of_rows2) {
                    header('location:/MVC/teacher/showAllQuestions');
                }
            }
        }
    }

    /*
     * Users can look at specific questions for more details and information
     */
    public function lookspecificQuestion($relatedQuestion)
    {
        $handler = ConnectionDatabase();


        // Retrieve the username of the users posting in order to display who asked and answered the question
        $sqlallQuestions = "SELECT question.title, question.message, question.person_id, person.username_email
                            FROM question
                            INNER JOIN person ON question.person_id = person.person_id
                            WHERE related_question = :relatedQuestion
                            ORDER BY question_id ASC";

        $relatedQuestionId = $relatedQuestion;
        $query = $handler->prepare($sqlallQuestions);
        $query->bindValue(':relatedQuestion', $relatedQuestionId);
        $query->execute();
        $number_of_rows = $query->rowCount();
        $resultsQuestions = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($number_of_rows > 0) {
            $this->view('question/answerquestion', ['questionId' => $relatedQuestionId, 'questionData' => $resultsQuestions]);
        }
    }

    /*
     * Users can answer / reply to the specific question they are viewing
     */
    public function answerQuestion($questionId)
    {
        $answer = ($_POST['description']);

        $handler = ConnectionDatabase();
        $sqlInsertQuestion = "INSERT INTO question (message, person_id, related_question)
                              VALUES(:message, :person_id, :related_question)";
        $query = $handler->prepare($sqlInsertQuestion);
        $query->bindValue(':message', $answer);
        $query->bindValue(':person_id', $_SESSION['person_id']);
        $query->bindValue(':related_question', $questionId);
        $query->execute();


        header('location:/MVC/teacher/lookSpecificQuestion/' . $questionId);
    }

    /*
     * Display all messages that the user sent or received
     * When the user views all the messages, he will see the last message sent to the related message.
     */
    public function showAllMessages()
    {
        $handler = ConnectionDatabase();

        $sqlallMessages = "SELECT m1.*
                           FROM message m1
                           JOIN (SELECT MAX(message_id) AS messageID
                                 FROM message
                                 Group By related_message) max
                           ON max.messageID = m1.message_id
                           WHERE person_send_id = :person_send_id OR person_receive_id = :person_receive_id
                           ORDER BY m1.message_id DESC";

        $query = $handler->prepare($sqlallMessages);
        $query->bindValue(':person_send_id', $_SESSION['person_id']);
        $query->bindValue(':person_receive_id', $_SESSION['person_id']);
        $query->execute();
        $number_of_rows = $query->rowCount();
        $resultsMessages = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($number_of_rows == 0) {
            $this->view('message/shownomessages');
        }
        if ($number_of_rows > 0) {
            $this->view('message/showallmessages', ['messageData' => $resultsMessages]);
        }
    }

    /*
     * Search for a person to message using the person's email
     */
    public function searchPersonToMessage()
    {
        $handler = ConnectionDatabase();
        $searchPerson = ($_POST['searchPerson']);

        $sqlgetUser = "SELECT person_id, username_email
                       FROM person
                       WHERE person_id != :person_id AND username_email = :username_email";
        $query = $handler->prepare($sqlgetUser);
        $query->bindValue(':person_id', $_SESSION['person_id']);
        $query->bindValue(':username_email', $searchPerson);
        $query->execute();
        $number_of_rows = $query->rowCount();
        $resultsMessages = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($number_of_rows > 0) {
            $this->view('message/sendMessage', ['messageData' => $resultsMessages]);
        } else {
            $this->view('message/noUserMessageFound');
        }
    }

    /*
     * Send an initial message to a user
     */
    public function sendMessage()
    {
        $handler = ConnectionDatabase();

        $message = $_POST['message'];
        $user = $_POST['user'];

        // Insert the message into the database
        $sqlInsertMessage = "INSERT INTO message (message, person_send_id, person_receive_id, related_message)
                             VALUES(:message, :person_send_id, :person_receive_id, :related_message)";
        $query = $handler->prepare($sqlInsertMessage);
        $query->bindValue(':message', $message);
        $query->bindValue(':person_send_id', $_SESSION['person_id']);
        $query->bindValue(':person_receive_id', $user);
        $query->bindValue(':related_message', 0);
        $query->execute();
        $number_of_rows = $query->rowCount();

        // Update the message that was just inserted and assign an ID to it
        // That value will be used to display the specific message with any answers provided to it
        if ($number_of_rows > 0) {
            $sqlUpdateMessage = "UPDATE message
                                 SET related_message = message_id
                                 WHERE person_send_id = :person_id";

            $query = $handler->prepare($sqlUpdateMessage);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->execute();
            $number_of_rows2 = $query->rowCount();
            if ($number_of_rows2) {
                header('location:/MVC/teacher/showAllMessages');
            }
        }
    }

    /*
     * Users can look at a specific message for more details and information
     */
    public function lookspecificMessage($personSendId, $personReceiveId, $jointMessage)
    {
        $handler = ConnectionDatabase();
        $personSend = $personSendId;
        $personReceive = $personReceiveId;
        $relatedMessage = $jointMessage;
      
        // Select the data of the specific message you are currently viewing
        $sqlMessage = "SELECT message.message_id, message.message, message.person_send_id, message.person_receive_id, person.username_email 
                       FROM message
                       INNER JOIN person ON message.person_send_id = person.person_id
                       WHERE related_message = :related_message
                       ORDER BY message_id ASC";

        $query = $handler->prepare($sqlMessage);
        $query->bindValue(':related_message', $relatedMessage);
        $query->execute();
        $number_of_rows = $query->rowCount();
        $resultsMessage = $query->fetchAll(PDO::FETCH_ASSOC);

        // Select the last inserted person_send_id value from the message table
        $sqlCheckLastRecord = "SELECT person_send_id
                               FROM message
                               WHERE related_message = :related_message
                               ORDER BY message_id DESC
                               LIMIT 1";

        $query = $handler->prepare($sqlCheckLastRecord);
        $query->bindValue(':related_message', $relatedMessage);
        $query->execute();
        $number_of_rows = $query->rowCount();
        $resultLastPersonSend = $query->fetch(PDO::FETCH_COLUMN, 0);
       
        // If you are the sender, the person reiceiving the message is $personReceive
        // Else the person receiving the message will be the last inserted person_send_id value 
        // Me -> User B
        if($resultLastPersonSend == $_SESSION['person_id'])
        {    

            $this->view('message/answermessage', ['personReceiveId' => $personReceive, 'personSendId' => $personSend, 'messageData' => $resultsMessage, 'relatedMessage' => $relatedMessage]);            
        }
        // User B -> Me
        else
        {        
            $this->view('message/answermessage', ['personReceiveId' => $resultLastPersonSend, 'personSendId' => $personSend, 'messageData' => $resultsMessage, 'relatedMessage' => $relatedMessage]);           
        }
    }

    /*
     * Users can reply back to the user that sent the message
     * Once inserted, redirect the user to lookspecificmessage() function
     */
    public function answerMessage($personReceiveId, $personSendId)
    {
        $answer = ($_POST['message']);
        $relatedMessage = ($_POST['relatedMessage']);
        $personSend = $personSendId;
        $personReceive = $personReceiveId;

        $handler = ConnectionDatabase();
        $sqlInsert = "INSERT INTO message (message, person_send_id, person_receive_id, related_message)
                      VALUES(:message, :person_send_id, :person_receive_id, :related_message)";

        $query = $handler->prepare($sqlInsert);
        $query->bindValue(':message', $answer);
        $query->bindValue(':person_send_id', $_SESSION['person_id']);
        $query->bindValue(':person_receive_id', $personReceiveId);
        $query->bindValue(':related_message', $relatedMessage);
        $query->execute();

        header('location:/MVC/teacher/lookspecificMessage/' . $personSend . '/' . $personReceive . '/' . $relatedMessage);

    }

    /*
     * Create a schedule table that is populated with dates and times
     */
    public function setTeacherSchedule()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'setSchedule') {
            $handler = ConnectionDatabase();

            // Create a date array of 7 upcoming days
            $dateArray = array();
            for ($i = 0; $i < 7; $i++) {
                $dateArray[$i] = date("Y-m-d", strtotime("+$i day", strtotime(date("Y-m-d"))));
            }

            // Select everything from the schedule table in regards to the user
            $sqlScheduleTable = "SELECT person_id, time_id, date, status
                                 FROM schedule
                                 WHERE person_id = :person_id";
            $query = $handler->prepare($sqlScheduleTable);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->execute();
            $resultsScheduleTable = $query->fetchAll(PDO::FETCH_ASSOC);

            // Select only date and time from the schedule table in regards to the user
            // Both selects are needed in the view to create the grid table using 2 foreach
            $sqlScheduleTable = "SELECT `date`, time_id
                                 FROM schedule
                                 WHERE person_id = :person_id";
            $query = $handler->prepare($sqlScheduleTable);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->execute();
            $number_of_rows_date = $query->rowCount();
            $resultsDate = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($number_of_rows_date > 0) {
                foreach ($resultsDate as $date) {
                    // If the lesson is completed, delete it from the schedule and update the lesson status to 3 (completed)
                    if ($date['date'] < date("Y-m-d")) {
                        $sqlDeleteSchedule = "DELETE FROM schedule
                                              WHERE person_id = :person_id AND date = :date";
                        $query = $handler->prepare($sqlDeleteSchedule);
                        $query->bindValue(':person_id', $_SESSION['person_id']);
                        $query->bindValue(':date', $date['date']);
                        $query->execute();

                        $sqlUpdateLesson = "UPDATE lesson
                                            SET status = :status
                                            WHERE teacher_id = :teacher_id AND date = :date";
                        $query = $handler->prepare($sqlUpdateLesson);
                        $query->bindValue(':status', 3);
                        $query->bindValue(':teacher_id', $_SESSION['person_id']);
                        $query->bindValue(':date', $date['date']);
                        $query->execute();
                    }
                }
            }
            $sqlTimeTable = "SELECT time_id, time_hour
                             FROM time";
            $query = $handler->prepare($sqlTimeTable);
            $query->execute();
            $number_of_rows_timeTable = $query->rowCount();
            $resultsTimeTable = $query->fetchAll(PDO::FETCH_ASSOC);

            if ($number_of_rows_timeTable > 0) {
                $this->view('schedule/ScheduleSet', ['scheduleData' => $resultsScheduleTable, 'timeData' => $resultsTimeTable, 'dateData' => $dateArray]);
            }
        }
    }

    /*
     * The teacher can update his availabilities across various dates and times.
     */
    public function updateAvailabilities()
    {
        $handler = ConnectionDatabase();
        $time = $_POST['time'];
        $date = $_POST['date'];
        $status = $_POST['statusSchedule'];

        $sqlDeleteSchedule = "DELETE FROM schedule
                              WHERE person_id = :person_id AND time_id = :time_id AND date = :date AND status = :status";

        $sqlInsert = "INSERT INTO schedule (person_id, time_id, date, status)
                      VALUES(:person_id, :time_id, :date, :status)";

        for ($i = 0; $i < count($time); $i++) {
            // Delete the selected record(s) from the schedule table that were set to available
            // Else insert them into the schedule
            if ($status[$i] == 1) {
                $query = $handler->prepare($sqlDeleteSchedule);
                $query->bindValue(':person_id', $_SESSION['person_id']);
                $query->bindValue(':time_id', $time[$i]);
                $query->bindValue(':date', $date[$i]);
                $query->bindValue(':status', 1);
                $query->execute();
            } else {
                $query = $handler->prepare($sqlInsert);
                $query->bindValue(':person_id', $_SESSION['person_id']);
                $query->bindValue(':time_id', $time[$i]);
                $query->bindValue(':date', $date[$i]);
                $query->bindValue(':status', 1);
                $query->execute();
            }
        }
        header('location:/MVC/account/myProfile');
    }

    /*
     * A learner can look at a teacher's schedule
     */
    public function seeTeacherSchedule()
    {
        $teacher = $_POST['teacher'];
        $handler = ConnectionDatabase();

        // Create a date array of 7 upcoming days
        $dateArray = array();
        for ($i = 0; $i < 7; $i++) {
            $dateArray[$i] = date("Y-m-d", strtotime("+$i day", strtotime(date("Y-m-d"))));
        }

        // Select the teacher's schedule
        $sqlScheduleTable = "SELECT person_id, time_id, date, status
                             FROM schedule WHERE person_id = :person_id";
        $query = $handler->prepare($sqlScheduleTable);
        $query->bindValue(':person_id', $teacher);
        $query->execute();
        $resultsScheduleTable = $query->fetchAll(PDO::FETCH_ASSOC);

        // Check if the teacher is still a valid teacher 
        // status 0 = learner
        // status 1 = teacher
        $sqlCheckStatus = "SELECT status
                           FROM person
                           WHERE person_id = :person_id AND status = :status";
        $query = $handler->prepare($sqlCheckStatus);
        $query->bindValue(':person_id', $teacher);
        $query->bindValue(':status', 1);
        $query->execute();
        $resultsStatus = $query->fetchAll(PDO::FETCH_ASSOC);

        // If the teacher exists and has a schedule, display the information
        // Else display NoScheduleForTeacher view
        if (count($resultsScheduleTable) > 0 && count($resultsStatus) > 0) {

            $sqlTimeTable = "SELECT time_id, time_hour
                             FROM time";
            $query = $handler->prepare($sqlTimeTable);
            $query->execute();
            $number_of_rows_timeTable = $query->rowCount();
            $resultsTimeTable = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($number_of_rows_timeTable > 0) {
                $this->view('schedule/askForLesson', ['scheduleData' => $resultsScheduleTable, 'timeData' => $resultsTimeTable, 'dateData' => $dateArray]);
            }
        } else {
            $sqlTimeTable = "SELECT time_id, time_hour
                             FROM time";
            $query = $handler->prepare($sqlTimeTable);
            $query->execute();
            $number_of_rows_timeTable = $query->rowCount();
            $resultsTimeTable = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($number_of_rows_timeTable > 0) {
                $this->view('schedule/NoScheduleForTeacher', ['timeData' => $resultsTimeTable, 'dateData' => $dateArray]);
            }
        }
    }

    /*
     * A learner can ask a teacher for a lesson.
     * This lesson will be pending until the teacher takes action or the learner decides to cancel it.
     */
    public function InsertLessonPending()
    {
        $handler = ConnectionDatabase();

        $teacher = $_POST['teacherId'];
        $time = $_POST['time'];
        $date = $_POST['date'];
        $status = $_POST['statusSchedule'];

        // Select the language id of the language that the user is asking a lesson for
        $sqlgetLanguage = "SELECT language_skill.language_id
                           FROM language_skill
                           WHERE language_skill.person_id = :person_id AND language_skill.language_teaching = :language_teaching";
        $query = $handler->prepare($sqlgetLanguage);
        $query->bindValue(':person_id', $teacher);
        $query->bindValue(':language_teaching', 1);
        $query->execute();
        $language_id = $query->fetch(PDO::FETCH_COLUMN, 0);

        // Insert the pending lesson into the lesson table
        // Status 1 = pending
        $sqlInsert = "INSERT INTO lesson (status, date, lesson_start_time, client_id, teacher_id, language_id)
                      VALUES(:status, :date, :lesson_start_time, :client_id, :teacher_id, :language_id)";

        for ($i = 0; $i < count($time); $i++) {
            if ($status[$i] == 1) {
                $query = $handler->prepare($sqlInsert);
                $query->bindValue(':status', 1);
                $query->bindValue(':date', $date[$i]);
                $query->bindValue(':lesson_start_time', $time[$i]);
                $query->bindValue(':client_id', $_SESSION['person_id']);
                $query->bindValue(':teacher_id', $teacher);
                $query->bindValue(':language_id', $language_id);
                $query->execute();

            }
        }

        // Update the schedule table with pending lesson values
        $sqlUpdateSchedule = "UPDATE schedule
                              SET status = :status
                              WHERE person_id = :person_id AND time_id = :time_id AND date = :date";

        for ($i = 0; $i < count($time); $i++) {
            if ($status[$i] == 1) {
                $query = $handler->prepare($sqlUpdateSchedule);
                $query->bindValue(':person_id', $teacher);
                $query->bindValue(':time_id', $time[$i]);
                $query->bindValue(':date', $date[$i]);
                $query->bindValue(':status', 2);
                $query->execute();
            }
        }
        header('location:/MVC/account/myProfile');
    }

    /*
     * Display all the lessons that the learner is involved in
     */
    public function showLearnerLessons()
    {
        $handler = ConnectionDatabase();

        // Select all dates from the lesson table
        // Determine if these dates already passed
        // If they did, update the lesson status to 3 (completed) in regards to the specific date
        $sqlScheduleTable = "SELECT `date`
                             FROM lesson
                             WHERE client_id = :client_id";
        $query = $handler->prepare($sqlScheduleTable);
        $query->bindValue(':client_id', $_SESSION['person_id']);
        $query->execute();
        $number_of_rows_date = $query->rowCount();
        $resultsDate = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($number_of_rows_date > 0) {
            foreach ($resultsDate as $date) {
                if ($date['date'] < date("Y-m-d")) {

                    $sqlUpdateLesson = "UPDATE lesson
                                        SET status = :status
                                        WHERE client_id = :client_id AND date = :date";
                    $query = $handler->prepare($sqlUpdateLesson);
                    $query->bindValue(':status', 3);
                    $query->bindValue(':client_id', $_SESSION['person_id']);
                    $query->bindValue(':date', $date['date']);
                    $query->execute();
                }
            }
        }

        // Select all the lesson information
        $sqlallLessons = "SELECT *
                          FROM lesson WHERE client_id = :client_id";
        $query = $handler->prepare($sqlallLessons);
        $query->bindValue(':client_id', $_SESSION['person_id']);
        $query->execute();
        $number_of_rows = $query->rowCount();
        $resultsLesson = $query->fetchAll(PDO::FETCH_ASSOC);

        // Select the username of the teachers and the hour of the lessons
        // If the learner has no lessons, redirect him to the shownolessons view
        // Else send both select data values to the showLearnerLessons View
        $sqlgetTimeAndUser = "SELECT time.time_hour, person.username_email FROM lesson
                              INNER JOIN time ON lesson.lesson_start_time = time.time_id
                              INNER JOIN person ON lesson.teacher_id = person.person_id
                              WHERE lesson.client_id = :client_id AND (lesson.status = :pending OR lesson.status = :confirmed OR lesson.status = :completed)";

        $query = $handler->prepare($sqlgetTimeAndUser);
        $query->bindValue(':client_id', $_SESSION['person_id']);
        $query->bindValue(':pending', 1);
        $query->bindValue(':confirmed', 2);
        $query->bindValue(':completed', 3);
        $query->execute();
        $timeTimeAndUser = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($number_of_rows == 0) {
            $this->view('schedule/shownolessons');
        }
        if ($number_of_rows > 0) {
            $this->view('schedule/showLearnerLessons', ['lessonData' => $resultsLesson, 'timeUserData' => $timeTimeAndUser]);

        }
    }

    /*
     * Display all the lessons that the teacher is involved in
     */
    public function showTeacherLessons()
    {
        $handler = ConnectionDatabase();

        // Select all dates from the lesson table
        // Determine if these dates already passed
        // If they did, update the lesson status to 3 (completed) in regards to the specific date
        $sqlLessonDate = "SELECT `date`
                          FROM lesson
                          WHERE teacher_id = :teacher_id";
        $query = $handler->prepare($sqlLessonDate);
        $query->bindValue(':teacher_id', $_SESSION['person_id']);
        $query->execute();
        $number_of_rows_date = $query->rowCount();
        $resultsDate = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($number_of_rows_date > 0) {
            foreach ($resultsDate as $date) {
                if ($date['date'] < date("Y-m-d")) {

                    $sqlUpdateLesson = "UPDATE lesson
                                        SET status = :status
                                        WHERE teacher_id = :teacher_id AND date = :date";
                    $query = $handler->prepare($sqlUpdateLesson);
                    $query->bindValue(':status', 3);
                    $query->bindValue(':teacher_id', $_SESSION['person_id']);
                    $query->bindValue(':date', $date['date']);
                    $query->execute();
                }
            }
        }

        // Select all the lesson information
        $sqlallLessons = "SELECT *
                          FROM lesson
                          WHERE teacher_id = :teacher_id";
        $query = $handler->prepare($sqlallLessons);
        $query->bindValue(':teacher_id', $_SESSION['person_id']);
        $query->execute();
        $number_of_rows = $query->rowCount();
        $resultsLesson = $query->fetchAll(PDO::FETCH_ASSOC);

        // Select the username of the learners and the hour of the lessons
        // If the teacher has no lessons, redirect him to the shownolessons view
        // Else send both select data values to the showTeacherLessons view
        $sqlgetTimeAndUser = "SELECT time.time_hour, person.username_email FROM lesson
                              INNER JOIN time ON lesson.lesson_start_time = time.time_id
                              INNER JOIN person ON lesson.client_id = person.person_id
                              WHERE lesson.teacher_id = :teacher_id AND (lesson.status = :pending OR lesson.status = :confirmed OR lesson.status = :completed)";

        $query = $handler->prepare($sqlgetTimeAndUser);
        $query->bindValue(':teacher_id', $_SESSION['person_id']);
        $query->bindValue(':pending', 1);
        $query->bindValue(':confirmed', 2);
        $query->bindValue(':completed', 3);
        $query->execute();
        $timeTimeAndUser = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($number_of_rows == 0) {
            $this->view('schedule/shownolessons');
        }
        if ($number_of_rows > 0) {
            $this->view('schedule/showTeacherLessons', ['lessonData' => $resultsLesson, 'timeUserData' => $timeTimeAndUser]);
        }
    }

    /*
     * Enables the teacher to confirm or cancel a learner's lesson
     */
    public function decisionTeacherLesson()
    {
        $handler = ConnectionDatabase();

        // If the teacher confirms the lesson
        // Update the schedule status to 3 (busy)
        // Update the lesson status to 2 (confirmed)
        if (isset($_POST['action']) && $_POST['action'] == 'Confirm') {
            $date = $_POST['lessonDate'];
            $client = $_POST['clientId'];
            $lessonTime = $_POST['lessonTime'];
            $language = $_POST['language'];

            $sqlConfirmScheduleConfirm = "UPDATE schedule
                                          SET status = :status
                                          WHERE person_id = :person_id AND time_id = :time_id AND date = :date";

            $query = $handler->prepare($sqlConfirmScheduleConfirm);
            $query->bindValue(':status', 3);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->bindValue(':time_id', $lessonTime);
            $query->bindValue(':date', $date);
            $query->execute();

            $sqlUpdateProfile = "UPDATE lesson
                                 SET status = :status
                                 WHERE client_id = :client_id AND teacher_id = :teacher_id AND lesson_start_time = :time_id AND date = :date AND language_id = :languagee";

            $query = $handler->prepare($sqlUpdateProfile);
            $query->bindValue(':status', 2);
            $query->bindValue(':client_id', $client);
            $query->bindValue(':teacher_id', $_SESSION['person_id']);
            $query->bindValue(':time_id', $lessonTime);
            $query->bindValue(':date', $date);
            $query->bindValue(':languagee', $language);
            $query->execute();

            header('location:/MVC/account/myProfile');
        }
        // If the teacher cancels the lesson
        // Delete all the lesson information from the lesson table in regards to the lesson
        // Delete all the schedule informaton from the schedule table in regards to the lesson
        // The schedule record of the canceled lesson will be reset to unavailable. 
        // The teacher will thus have to update his schedule to re-allow lesson requests in the same time and date. 
        if (isset($_POST['action']) && $_POST['action'] == 'Cancel') {
            $date = $_POST['lessonDate'];
            $client = $_POST['clientId'];
            $lessonTime = $_POST['lessonTime'];
            $language = $_POST['language'];
            $status = $_POST['status'];

            $sqlDeleteLesson = "DELETE FROM lesson
                                WHERE client_id = :client_id AND teacher_id = :teacher_id AND lesson_start_time = :time_id AND date = :date AND language_id = :language AND status = :status";
            $query = $handler->prepare($sqlDeleteLesson);
            $query->bindValue(':client_id', $client);
            $query->bindValue(':teacher_id', $_SESSION['person_id']);
            $query->bindValue(':time_id', $lessonTime);
            $query->bindValue(':date', $date);
            $query->bindValue(':language', $language);
            $query->bindValue(':status', $status);
            $query->execute();

            $sqlUpdateSchedule = "DELETE FROM schedule
                                  WHERE person_id = :person_id AND time_id = :time_id AND date = :date";
            $query = $handler->prepare($sqlUpdateSchedule);
            $query->bindValue(':person_id', $_SESSION['person_id']);
            $query->bindValue(':time_id', $lessonTime);
            $query->bindValue(':date', $date);
            $query->execute();
            header('location:/MVC/account/myProfile');
        }
    }

    /*
     * Enables the learner to cancel his lesson with a specific teacher
     */
    public function decisionLearnerLesson()
    {
        $handler = ConnectionDatabase();

        // If the learner cancels the lesson
        // Delete all the lesson information from the lesson table in regards to the lesson
        // Delete all the schedule informaton from the schedule table in regards to the lesson
        // The schedule record of the canceled lesson will be reset to unavailable. 
        // The teacher will thus have to update his schedule to re-allow lesson requests in the same time and date. 
        if (isset($_POST['action']) && $_POST['action'] == 'Cancel') {
            $date = $_POST['lessonDate'];
            $teacher = $_POST['teacherId'];
            $lessonTime = $_POST['lessonTime'];
            $language = $_POST['language'];
            $status = $_POST['status'];

            $sqlDeleteLesson = "DELETE FROM lesson
                                WHERE client_id = :client_id AND teacher_id = :teacher_id AND lesson_start_time = :time_id AND date = :date AND language_id = :languagee AND status = :status";
            $query = $handler->prepare($sqlDeleteLesson);
            $query->bindValue(':client_id', $_SESSION['person_id']);
            $query->bindValue(':teacher_id', $teacher);
            $query->bindValue(':time_id', $lessonTime);
            $query->bindValue(':date', $date);
            $query->bindValue(':languagee', $language);
            $query->bindValue(':status', $status);
            $query->execute();

            $sqlUpdateSchedule = "DELETE FROM schedule
                                  WHERE person_id = :person_id AND time_id = :time_id AND date = :date";
            $query = $handler->prepare($sqlUpdateSchedule);
            $query->bindValue(':person_id', $teacher);
            $query->bindValue(':time_id', $lessonTime);
            $query->bindValue(':date', $date);
            $query->execute();

            header('location:/MVC/account/myProfile');
        }
    }

    /*
     * Display all the reviews related to the specific teacher
     * Being able to leave a review is restricted to only the learners that took a lesson with the teacher.
     */
    public function showAllReviews()
    {
        $handler = ConnectionDatabase();

        $teacher = $_POST['teacherId'];

        // Select all the completed user lessons with the specific teacher
        $sqlgetLessonInfo = "SELECT date, status, client_id, teacher_id
                             FROM lesson
                             WHERE client_id = :client_id AND teacher_id = :teacher_id AND status = :status";
        $query = $handler->prepare($sqlgetLessonInfo);
        $query->bindValue(':client_id', $_SESSION['person_id']);
        $query->bindValue(':teacher_id', $teacher);
        $query->bindValue(':status', 3);
        $query->execute();
        $number_of_rows_lesson_info = $query->rowCount();
        $resultInfo = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($number_of_rows_lesson_info > 0) {
            foreach ($resultInfo as $resultInformation) {
                // Verify that the learner had a completed lesson
                if ($resultInformation['date'] < date("Y-m-d") && $resultInformation['client_id'] == $_SESSION['person_id'] && $resultInformation['status'] == 3) {
                    // Select all reviews
                    $sqlgetreview = "SELECT * FROM review";
                    $query = $handler->prepare($sqlgetreview);
                    $query->execute();
                    $resultReviewsList = $query->fetchAll(PDO::FETCH_ASSOC);

                    // If there are reviews in the review table
                    // Select the lesson_id in regards to the correct status/date/teacher/client values
                    if (count($resultReviewsList) > 0) {
                        $sqlgetLessonInfo = "SELECT lesson_id
                                             FROM lesson WHERE status = :status AND teacher_id = :teacher_id AND date < :date AND client_id = :client_id";
                        $query = $handler->prepare($sqlgetLessonInfo);
                        $query->bindValue(':status', 3);
                        $query->bindValue(':teacher_id', $teacher);
                        $query->bindValue(':date', date("Y-m-d"));
                        $query->bindValue(':client_id', $_SESSION['person_id']);
                        $query->execute();
                        $resultLessonId = $query->fetchAll(PDO::FETCH_COLUMN, 0);

                        for ($i = 0; $i < count($resultLessonId); $i++) {

                            $boolean = true;
                            // Check if the user already left a review for that specific lesson
                            foreach ($resultReviewsList as $reviewData) {
                                if (($reviewData['lesson_id']) == $resultLessonId[$i]) {
                                    $boolean = false;
                                    break 1;
                                }
                            }
                            if ($boolean == false) {
                                continue;
                            // If the user had a lesson where he did not leave a review, he will be sent to showAllreviews view
                            } elseif ($boolean == true) {
                                $lessonIdData = $resultLessonId[$i];
                                $this->view('review/showallreviews', ['reviewData' => $resultReviewsList, 'lessonId' => $lessonIdData]);
                                exit;
                            // Else if he left a review for every lesson, he will not be able to leave a new review.
                            } else {
                                $this->view('review/showAllReviewsCantInsert', ['reviewData' => $resultReviewsList]);
                            }
                        }
                        $this->view('review/showAllReviewsCantInsert', ['reviewData' => $resultReviewsList]);
                    } 
                    // Else select the lesson_id in regards to the correct status/date/teacher/client values and send the data to the shownowreviews view
                    elseif (count($resultReviewsList) == 0) {
                        $sqlgetLessonInfo = "SELECT lesson_id
                                             FROM lesson WHERE status = :status AND teacher_id = :teacher_id AND date < :date AND client_id = :client_id LIMIT 1";
                        $query = $handler->prepare($sqlgetLessonInfo);
                        $query->bindValue(':status', 3);
                        $query->bindValue(':teacher_id', $teacher);
                        $query->bindValue(':date', date("Y-m-d"));
                        $query->bindValue(':client_id', $_SESSION['person_id']);
                        $query->execute();
                        $resultLessonId = $query->fetch(PDO::FETCH_COLUMN, 0);

                        $this->view('review/shownoreviews', ['lessonId' => $resultLessonId]);
                    }
                } else {
                    $this->view('review/noreviewallowed');
                }
            }
        } else {
            $this->view('review/noreviewallowed');
        }
    }

    /*
     * Enables the learner to place a review for the specific lesson
     */
    public function insertReview()
    {
        $handler = ConnectionDatabase();

        $rating = $_POST['rating'];
        $lessonId = $_POST['lessonId'];
        $comment = $_POST['comment'];

        // Insert a new review in the review table in regards to the lesson_id
        $sqlInsertReview = "INSERT INTO review (lesson_id, rating, comment, person_id)
                            VALUES(:lesson_id, :rating, :comment, :person_id)";
        $query = $handler->prepare($sqlInsertReview);
        $query->bindValue(':lesson_id', $lessonId);
        $query->bindValue(':rating', $rating);
        $query->bindValue(':comment', $comment);
        $query->bindValue(':person_id', $_SESSION['person_id']);
        $query->execute();

        header('location:/MVC/account/');
    }
}

?>