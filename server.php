<?php
require "./config.php";
require "./func.php";

$data = file_get_contents('php://input');
//file_put_contents('1.json', $data);

$json = json_decode($data, true);

$message = "New request from the website!\n";

if ($json['step0']['question'] && $json['step0']['answers']) {

    $message .= "\n" . $json['step0']['question'] . ": " . implode(", ", $json['step0']['answers']);
} else {
    $response = [
        "status" => "error",
        "message" => "Something wrong..."
    ];
}

if ($json['step1']['question'] && $json['step1']['answers']) {

    $message .= "\n\n" . $json['step1']['question'] . ": " . implode(", ", $json['step1']['answers']);
} else {
    $response = [
        "status" => "error",
        "message" => "Something wrong..."
    ];
}

if ($json['step2']['question'] && $json['step2']['answers']) {

    $message .= "\n\n" . $json['step2']['question'] . ": " . implode(", ", $json['step2']['answers']);
} else {
    $response = [
        "status" => "error",
        "message" => "Something wrong..."
    ];
}

if ($json['step3']['question'] && $json['step3']['answers']) {
    //добавляем в строку сообщения
    $message .= "\n\n" . $json['step3']['question'] . ": " . implode(", ", $json['step3']['answers']);
} else {
    $response = [
        "status" => "error",
        "message" => "Что-то пошло не так..."
    ];
}

$data_empty = false;

foreach($json['step4'] as $item) {
    if (!$item) $data_empty = true;
}

if (! $data_empty) {
    //add to the message line

    $message .= "\n\nName: " . $json['step4']['name'];
    $message .= "\nPhone: " . $json['step4']['phone'];
    $message .= "\nEmail: " . $json['step4']['email'];
    $message .= "\nCall: " . $json['step4']['call'];

    $my_data = [
        "message" => $message
    ];

    get_my_data(BASE_URL . TOKEN . "/send?" . http_build_query($my_data));

    $response = [
        "status" => "ok",
        "message" => "Thank you! We will contact you soon!"
    ];
} else {

    if (!$json['step4']['name']) {
        $error_message = "Enter your name";
    } else if (!$json['step4']['phone']) {
        $error_message = "Enter your telephone number";
    } else if (!$json['step4']['email']) {
        $error_message = "Enter your email";
    } else if (!$json['step4']['call']) {
        $error_message = "Enter methods of communication.";
    } else {
        $error_message = "Something wrong...";
    }
    $response = [
        "status" => "error",
        "message" => $error_message
    ];
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode($response);