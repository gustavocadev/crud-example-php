<?php

//connect to mysql database
$host = "localhost";
$user = "root";
$pass = "root";
$db = "test";
$port = 3306;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

// test if the connection is okay
if (mysqli_connect_errno()) {
  echo json_encode([
    'msg' => 'Failed to connect to MySQL: ' . mysqli_connect_error()
  ]);
}

// if the connection is okay, create a table if not exists
mysqli_query($conn, "
  CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  )
");

// insert a user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $body = json_decode(file_get_contents("php://input"));

  $name = $body->name;
  $email = $body->email;

  $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo json_encode([
      'msg' => 'Failed to insert user: ' . $conn->error
    ]);
  }

  echo json_encode([
    'msg' => 'User inserted successfully'
  ]);
}

// get all users
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // catch the user by id
  if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    $user = mysqli_fetch_assoc($result);
    echo json_encode($user);
    return;
  }

  $sql = "SELECT * FROM users";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo json_encode([
      'msg' => 'Failed to get users: ' . $conn->error
    ]);
  };

  $users = [];

  while ($row = mysqli_fetch_assoc($result)) {
    // ugly way to push to an array
    // $users[] = $row;

    // an elegant way to push to an array
    array_push($users, $row);
  }

  echo json_encode($users);
}

// delete from params
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  $params = explode('/', $_SERVER['PATH_INFO']);
  $id = $params[1];

  $sql = "DELETE FROM users WHERE id = $id";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo json_encode(
      ['msg' => 'Failed to delete user: ' . $conn->error]
    );
  }

  echo json_encode([
    'msg' => 'User deleted successfully'
  ]);
}


if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
  $body = json_decode(file_get_contents("php://input"));

  $id = $_GET['id'];
  $name = $body->name;
  $email = $body->email;

  $sql = "UPDATE users SET name = '$name', email = '$email' WHERE id = $id";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo json_encode([
      'msg' => 'Failed to update user: ' . mysqli_error($conn)
    ]);
  }

  echo json_encode([
    'msg' => 'User updated successfully'
  ]);
};
