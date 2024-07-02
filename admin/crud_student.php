<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username']) && isset($_SESSION['userType'])) {

require '../db_conn.php';

    if (isset($_POST['delete_student_id'])) {
        $student_id = mysqli_real_escape_string($conn, $_POST['delete_student_id']);

        // Perform the necessary delete operation using the $student_id
        $deleteQuery = "DELETE FROM students WHERE id = $student_id";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            $_SESSION['message'] = "Student deleted successfully.";
        } else {
            $_SESSION['message_danger'] = "Error occurred while deletng the student.";
        }
    }

    // Add student
    if (isset($_POST['add_student'])) {
        // Retrieve form data

        $sr_code = mysqli_real_escape_string($conn, $_POST['sr_code']);
        $lrn = mysqli_real_escape_string($conn, $_POST['lrn']);
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
        $middleName = mysqli_real_escape_string($conn, $_POST['middleName']);
        $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
        $contactNumber = mysqli_real_escape_string($conn, $_POST['contactNumber']);
        $homeAddress = mysqli_real_escape_string($conn, $_POST['homeAddress']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $religion = mysqli_real_escape_string($conn, $_POST['religion']);
        $fatherName = mysqli_real_escape_string($conn, $_POST['fatherName']);
        $fatherOccupation = mysqli_real_escape_string($conn, $_POST['fatherOccupation']);
        $fatherContact = mysqli_real_escape_string($conn, $_POST['fatherContact']);
        $fatherEmail = mysqli_real_escape_string($conn, $_POST['fatherEmail']);
        $motherName = mysqli_real_escape_string($conn, $_POST['motherName']);
        $motherOccupation = mysqli_real_escape_string($conn, $_POST['motherOccupation']);
        $motherContact = mysqli_real_escape_string($conn, $_POST['motherContact']);
        $motherEmail = mysqli_real_escape_string($conn, $_POST['motherEmail']);
        $guardianName = mysqli_real_escape_string($conn, $_POST['guardianName']);
        $guardianOccupation = mysqli_real_escape_string($conn, $_POST['guardianOccupation']);
        $guardianContact = mysqli_real_escape_string($conn, $_POST['guardianContact']);
        $guardianEmail = mysqli_real_escape_string($conn, $_POST['guardianEmail']);
        $dateCreated = mysqli_real_escape_string($conn, $_POST['dateCreated']);
        $dateUpdated = mysqli_real_escape_string($conn, $_POST['dateUpdated']);


        // $checkQuery = "SELECT * FROM students WHERE lrn = '$lrn'";
        // $checkResult = mysqli_query($conn, $checkQuery);
        // if (mysqli_num_rows($checkResult) > 0) {
        //     $_SESSION['message_danger'] = "Student lrn $lrn already exists.";
        //     header('Location: students.php');
        //     exit();
        // }

        // Perform the database insertion
       $query = "INSERT INTO students (sr_code, lrn, firstName, middleName, lastName, gender, birthday, contactNumber, homeAddress, email, religion, fatherName, fatherOccupation, fatherContact, fatherEmail, motherName, motherOccupation, motherContact, motherEmail, guardianName, guardianOccupation, guardianContact, guardianEmail)
          VALUES ('$sr_code', '$lrn', '$firstName', '$middleName', '$lastName', '$gender', '$birthday', '$contactNumber', '$homeAddress', '$email', '$religion', '$fatherName', '$fatherOccupation', '$fatherContact', '$fatherEmail', '$motherName', '$motherOccupation', '$motherContact', '$motherEmail', '$guardianName', '$guardianOccupation', '$guardianContact', '$guardianEmail')";
        $result = mysqli_query($conn, $query);


        if ($result) {
            // Success message
            $_SESSION['message'] = "Student added successfully.";
            header('Location: students.php');
            exit();
        } else {
            // Error message
            $_SESSION['message_danger'] = "Error occurred while adding the student.";
            header('Location: students.php');
            exit();
        }
    }
    
    // edit student
    if (isset($_POST['edit_student'])) {
            // Get and sanitize form data
            $student_id = mysqli_real_escape_string($conn, $_POST['edit_student_id']);
            $sr_code = mysqli_real_escape_string($conn, $_POST['edit_sr_code']);
            $lrn = mysqli_real_escape_string($conn, $_POST['edit_lrn']);
            $firstName = mysqli_real_escape_string($conn, $_POST['edit_firstName']);
            $middleName = mysqli_real_escape_string($conn, $_POST['edit_middleName']);
            $lastName = mysqli_real_escape_string($conn, $_POST['edit_lastName']);
            $gender = mysqli_real_escape_string($conn, $_POST['edit_gender']);
            $birthday = mysqli_real_escape_string($conn, $_POST['edit_birthday']);
            $contactNumber = mysqli_real_escape_string($conn, $_POST['edit_contactNumber']);
            $homeAddress = mysqli_real_escape_string($conn, $_POST['edit_homeAddress']);
            $email = mysqli_real_escape_string($conn, $_POST['edit_email']);
            $religion = mysqli_real_escape_string($conn, $_POST['edit_religion']);
            $fatherName = mysqli_real_escape_string($conn, $_POST['edit_fatherName']);
            $fatherOccupation = mysqli_real_escape_string($conn, $_POST['edit_fatherOccupation']);
            $fatherContact = mysqli_real_escape_string($conn, $_POST['edit_fatherContact']);
            $fatherEmail = mysqli_real_escape_string($conn, $_POST['edit_fatherEmail']);
            $motherName = mysqli_real_escape_string($conn, $_POST['edit_motherName']);
            $motherOccupation = mysqli_real_escape_string($conn, $_POST['edit_motherOccupation']);
            $motherContact = mysqli_real_escape_string($conn, $_POST['edit_motherContact']);
            $motherEmail = mysqli_real_escape_string($conn, $_POST['edit_motherEmail']);
            $guardianName = mysqli_real_escape_string($conn, $_POST['edit_guardianName']);
            $guardianOccupation = mysqli_real_escape_string($conn, $_POST['edit_guardianOccupation']);
            $guardianContact = mysqli_real_escape_string($conn, $_POST['edit_guardianContact']);
            $guardianEmail = mysqli_real_escape_string($conn, $_POST['edit_guardianEmail']);

            // $checkQuery = "SELECT * FROM students WHERE lrn = '$lrn'";
            // $checkResult = mysqli_query($conn, $checkQuery);
            // if (mysqli_num_rows($checkResult) > 0) {
            //     $_SESSION['message_danger'] = "Student lrn $lrn already exists.";
            //     header('Location: students.php');
            //     exit();
            // }

            // Perform the database update
            $query = "UPDATE students SET 
                        sr_code='$sr_code', 
                        lrn='$lrn', 
                        firstName='$firstName', 
                        middleName='$middleName', 
                        lastName='$lastName', 
                        gender='$gender', 
                        birthday='$birthday', 
                        contactNumber='$contactNumber', 
                        homeAddress='$homeAddress', 
                        email='$email', 
                        religion='$religion', 
                        fatherName='$fatherName', 
                        fatherOccupation='$fatherOccupation', 
                        fatherContact='$fatherContact', 
                        fatherEmail='$fatherEmail', 
                        motherName='$motherName', 
                        motherOccupation='$motherOccupation', 
                        motherContact='$motherContact', 
                        motherEmail='$motherEmail', 
                        guardianName='$guardianName', 
                        guardianOccupation='$guardianOccupation', 
                        guardianContact='$guardianContact', 
                        guardianEmail='$guardianEmail'
                      WHERE id='$student_id'";
                      
            $result = mysqli_query($conn, $query);

            if ($result) {
                // Success message
                $_SESSION['message'] = "Student updated successfully.";
                header('Location: students.php');
                exit();
            } else {
                // Error message
                $_SESSION['message_danger'] = "Failed to update student.";
                header('Location: students.php');
                exit();
            }
        } else {
            // Redirect to the appropriate page if the form is not submitted
            header("Location: students.php");
            exit();
        }


    } else {
    header("Location: ../admin_login.php");
    exit();
    }
    ?>
