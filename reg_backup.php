<?php

// define variables and set to empty values
$name = $email = $password = "";
$nameErr = $emailErr = $passwordErr = "";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    // validate name
    if ( empty( $_POST["name"] ) ) {
        $nameErr = "Name is required";
    } else {
        $name = test_input( $_POST["name"] );
    }

    // validate email
    if ( empty( $_POST["email"] ) ) {
        $emailErr = "Email is required";
    } else {
        $email = test_input( $_POST["email"] );
        // check if email address is well-formed
        if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            $emailErr = "Invalid email format";
        }
    }

    // validate password
    if ( empty( $_POST["password"] ) ) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input( $_POST["password"] );
    }

    // validate profile picture
    $target_dir = "uploads/";
    $target_file = $target_dir . basename( $_FILES["profile_pic"]["name"] );
    $imageFileType = strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );
    
    $uploadOk = 1;
    // Check if image file is a actual image or fake image
    if ( isset( $_POST["submit"] ) ) {
        $check = getimagesize( $_FILES["profile_pic"]["tmp_name"] );
        if ( $check !== false ) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    // Check if file already exists
    if ( file_exists( $target_file ) ) {
        $uploadOk = 0;
    }
    // Check file size
    if ( $_FILES["profile_pic"]["size"] > 500000 ) {
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ( $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ( $uploadOk == 0 ) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        $timestamp = date( 'Y-m-d_H-i-s' );
        $new_filename = $target_dir . uniqid() . $timestamp . '_' . basename( $_FILES["profile_pic"]["name"] );
        if ( move_uploaded_file( $_FILES["profile_pic"]["tmp_name"], $new_filename ) ) {
            echo "The file " . htmlspecialchars( basename( $_FILES["profile_pic"]["name"] ) ) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // save user data to CSV file
    if ( $nameErr == "" && $emailErr == "" && $passwordErr == "" ) {
        $file = fopen( "users.csv", "a" );
        $data = [ $name, $email, $new_filename ];
        fputcsv( $file, $data );
        fclose( $file );
    }

    // Start session and set cookie
session_start();
$_SESSION["name"] = $name;
setcookie( "name", $name, time() + 3600 );

header( "Location: users.php" );
exit;

}

function test_input( $data ) {
    $data = trim( $data );
    $data = stripslashes( $data );
    $data = htmlspecialchars( $data );
    return $data;
}

?>