<?php
require_once 'database-connect.php';
require_once 'Question.php';


function post_question($question) {
    global $pdo;

    $query = "INSERT INTO question (enonce, reponse, answer) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(1, $question->getEnonce(), PDO::PARAM_STR);
    $stmt->bindValue(2, $question->getReponses(), PDO::PARAM_STR);
    $stmt->bindValue(3, $question->getAnswer(), PDO::PARAM_INT);

    if ($stmt->execute()) return true;
    return false;
}

function delete_question($enonce) {
    global $pdo;

    $query = "DELETE FROM question WHERE enonce = ?";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1,$enonce, PDO::PARAM_STR);
    if ($stmt->execute()) return true;
    return false;


}
function get_question($enonce) {
    global $pdo;

    $query = "SELECT * FROM question WHERE enonce = ? LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1,$enonce, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return json_encode($result);


}


function get_questions() {
    global $pdo;

    $query = "SELECT * FROM question";
    $stmt = $pdo->query($query);
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($res);
}
?>