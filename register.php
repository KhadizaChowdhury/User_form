<?php
if ( $_SERVER['REQUEST_METHOD'] == "POST" ) {
    // Validate form inputs
    $name = trim( $_POST['name'] );
    $email = trim( $_POST['email'] );
    $password = $_POST['password'];

    if ( empty( $name ) || empty( $email ) || empty( $password ) ) {
        echo "Please fill out all fields";
        exit;
    }

    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        echo "Invalid email format";
        exit;
    }

    // Save profile picture to server
    $target_dir = "uploads/";
    $target_file = $target_dir . basename( $_FILES["profile_pic"]["name"] );
    $imageFileType = strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );

    // Generate unique filename
    $date = new DateTime();
    $timestamp = $date->getTimestamp();
    $new_filename = $target_dir . $timestamp . "." . $imageFileType;

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
        $new_filename = $target_dir .  uniqid(). '_' . $timestamp . '_' . basename( $_FILES["profile_pic"]["name"] );
        if ( move_uploaded_file( $_FILES["profile_pic"]["tmp_name"], $new_filename ) ) {
            echo "The file " . htmlspecialchars( basename( $_FILES["profile_pic"]["name"] ) ) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // save user data to CSV file
    if ( $nameErr == "" && $emailErr == "" && $passwordErr == "" ) {
        $file = fopen( "users.csv", "a" );
        $data = [$name, $email, $new_filename];
        fputcsv( $file, $data );
        fclose( $file );
    }

    // Start session and set cookie
    session_start();
    $loggedIn = $_SESSION["loggedIn"] = true;
    $_SESSION["name"] = $name;

    if ( $_SESSION["loggedIn"] == 1 ) {
        setcookie( "loggedIn", $loggedIn, time() + 3600 );
        setcookie( "name", $name, time() + 3600, "/", "", true, true );
        
    } else {
        unset( $_COOKIE["loggedIn"] );
    }

    header( "Location: users.php" );
    exit;
}
?>