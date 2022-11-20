<?php
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Method:GET,POST,OPTIONS,PUT,DELETE");
header('Access-Control-Headers:Content-Type,Access-Control-Allow-Headers,X-Requested-With');
header('Content-Type:application/json;charset-8');
require 'conn.php';


$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


switch ($method) {
    case 'GET':
        $id = $_GET['task_id'];
        $sql = "SELECT * FROM `task`";
        break;
    case 'PUT':
        $task = $_POST['task'];

        $sql = "INSERT INTO `task`( `task`,`status`) VALUES('$task','')";
        break;

    case 'UPD':
        $task_id = $_GET['task_id'];
        $sql = "UPDATE `task` SET `status` = 'Done' WHERE `task_id` = $task_id";
        break;
    case 'DEL':
        $task_id = $_GET['task_id'];

        $sql = "DELETE FROM `task` WHERE `task_id` = $task_id";
        break;
}

// run SQL statement
$result = mysqli_query($conn, $sql);

// die if SQL statement failed
if (!$result) {
    http_response_code(404);
    die(mysqli_error($conn));
}

if ($method == 'GET') {
    if (!$id) echo '[';
    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
        echo ($i > 0 ? ',' : '') . json_encode(mysqli_fetch_object($result));
    }
    if (!$id) echo ']';
} elseif ($method == 'PUT') {
    echo json_encode($result);
} elseif ($method == 'UPD') {
    echo json_encode($result);
} elseif ($method == 'DEL') {
    echo json_encode($result);
} else {
    echo mysqli_affected_rows($conn);
}

$conn->close();
