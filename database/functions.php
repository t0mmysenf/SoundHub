<?php
require_once('../classes/userClass.php');
require_once('../crypt/CryptFunctions.php');

// Database properties
$servername = "188.63.148.112:3307";
$username = "Rolf";
$password = "ak92EVJnsF1yrQBW";
$dbname = "SoundHub";



function RegisterNewUser($_newUsername, $_newHash, $_newPrivateKey, $_newPublicKey) {
  // variables
  global $servername;
  global $username;
  global $password;
  global $dbname;
  $date = date("Y-m-d");

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Check connection
  if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
  }

  // Attempt insert query execution
  $sql = "INSERT INTO Users (UserName, UserHash, UserPrivateKey, UserPublicKey, UserJoined)
          VALUES ('" . $_newUsername . "','" . $_newHash . "','" . $_newPrivateKey . "','" . $_newPublicKey . "', '" . $date . "')";
  if(mysqli_query($conn, $sql)){
    return true;
  } else{
    return false;
  }

  // Close connection
  mysqli_close($conn);
}

function GetHash($_checkUsername){
  // variables
  global $servername;
  global $username;
  global $password;
  global $dbname;

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT UserHash FROM Users WHERE UserName='" . $_checkUsername . "'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        return $row["UserHash"];
    }
  } else {
    return false;
  }

  // close connection
  mysqli_close($conn);
}

function GetUserClass($_checkUsername){
  // variables
  global $servername;
  global $username;
  global $password;
  global $dbname;

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT UserEmail, UserAvatar, UserBio, UserBirthday, UserJoined FROM Users WHERE UserName='" . $_checkUsername . "'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        // create new userClass
        $sessionUser = new User();

        $sessionUser->userName = $_checkUsername;
        $sessionUser->userEmail = $row["UserEmail"];
        $sessionUser->userAvatar = $row["UserAvatar"];
        $sessionUser->userBio = $row["UserBio"];
        $sessionUser->userBirthday = $row["UserBirthday"];
        $sessionUser->userJoined = $row["UserJoined"];

        return $sessionUser;
    }
  } else {
    return false;
  }

  // close connection
  mysqli_close($conn);
}

function UpdateUser($_updateUsername, $_updateEmail, $_updateBio, $_updateBirthday, $_updateAvatar){
  // variables
  global $servername;
  global $username;
  global $password;
  global $dbname;

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Check connection
  if($conn === false){
    die($ErrorMessage = "ERROR: Could not connect. " . mysqli_connect_error());
  }

  // Attempt update query execution
  $sql = "UPDATE Users SET
          UserEmail=(" . ($_updateEmail == '' ? 'NULL' : "'$_updateEmail'") . "),
          UserBio=(" . ($_updateBio == '' ? 'NULL' : "'$_updateBio'") . "),
          UserBirthday=(" . ($_updateBirthday == '' ? 'NULL' : "'$_updateBirthday'") . "),
          UserAvatar=(" . ($_updateAvatar == '' ? 'NULL' : "'$_updateAvatar'") . ")
          WHERE UserName='". $_updateUsername ."'";
  mysqli_query($conn, $sql) or die($ErrorMessage = mysqli_error($conn));
  if (isset($ErrorMessage)) {
    return $ErrorMessage;
  }

  // Close connection
  mysqli_close($conn);
}

function SearchUser($_searchUser, $_username){
  // variables
  global $servername;
  global $username;
  global $password;
  global $dbname;

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT UserAvatar, UserName FROM Users WHERE UserName
          LIKE '%". $_searchUser ."%' AND NOT UserName='".$_username."'";
  $result = $conn->query($sql);

  $searchedUsers = array();
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $searchedUsers[] = array($row["UserAvatar"], $row["UserName"]);
    }
  } else {
    return false;
  }
  return $searchedUsers;

  // close connection
  $conn->close();
}



function GetPublicKey($_username){
  // variables
  global $servername;
  global $username;
  global $password;
  global $dbname;

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT UserPublicKey FROM Users WHERE UserName='" . $_username . "'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        return $row["UserPublicKey"];
    }
  } else {
    return false;
  }

  // close connection
  mysqli_close($conn);
}

function GetPrivateKey($_username){
  // variables
  global $servername;
  global $username;
  global $password;
  global $dbname;

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT UserPrivateKey FROM Users WHERE UserName='" . $_username . "'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        return $row["UserPrivateKey"];
    }
  } else {
    return false;
  }

  // close connection
  mysqli_close($conn);
}

?>
