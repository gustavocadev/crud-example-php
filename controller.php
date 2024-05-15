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
    // catch the user by id
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
        echo json_encode($user);
        return;
    }

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

// delete from params
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $params = explode('/', $_SERVER['PATH_INFO']);
    $id = $params[1];

    $sql = "DELETE FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(
            array('sql' => 'Failed to delete user: ' . $conn->error)
        );
    }

    echo json_encode(
        array('sql' => 'User deleted successfully')
    );
}


if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $body = json_decode(file_get_contents("php://input"));

    $id = $_GET['id'];
    $name = $body->name;

    $sql = "UPDATE users SET username = '$name' WHERE id = $id";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(
            array('sql' => 'Failed to update user: ' . $conn->error)
        );
    }

    echo json_encode(
        array('sql' => 'User updated successfully')
    );
};
