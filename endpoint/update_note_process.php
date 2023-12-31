<?php
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $noteID = $_POST['note_id'];
    $noteColor = $_POST['note_color'];
    $noteTitle = $_POST['note_title'];
    $noteContent = $_POST['note_content'];

    $stmt = $conn->prepare("UPDATE `tbl_notes` SET `color` = :color, `note_title` = :note_title, `note` = :note WHERE tbl_notes_id = :note_id");
    $stmt->bindParam(':note_id', $noteID);
    $stmt->bindParam(':color', $noteColor);
    $stmt->bindParam(':note_title', $noteTitle);
    $stmt->bindParam(':note', $noteContent);

    if ($stmt->execute()) {
        header("Location: ../index2.php");  // Redirect to index2.php after successful update
        exit();  // Exit to prevent further code execution
    } else {
        echo "Error updating note";
        header("Location: ../index2.php");  // Redirect to index2.php in case of an error
        exit();  // Exit to prevent further code execution
    }
}
?>
