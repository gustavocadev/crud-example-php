<?php

//connect to mysql database
$host = "localhost";
$user = "root";
$pass = "";
$db = "test";

$conn = mysqli_connect($host, $user, $pass, $db);

// test if the connection is okay
if (mysqli_connect_errno()) {
    echo json_encode(
        array('mysqli' => 'Failed to connect to MySQL: ' . mysqli_connect_error())
    );
}

// insert a user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = json_decode(file_get_contents("php://input"));

    $username = $body->username;

    $sql = "INSERT INTO users (username) VALUES ('$username')";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(
            array('sql' => 'Failed to insert user: ' . $conn->error)
        );
    }

    echo json_encode(
        array('sql' => 'User inserted successfully')
    );
}

// get all users
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(
            array('sql' => 'Failed to get users: ' . $conn->error)
        );
    }

    $users = array();

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
}
