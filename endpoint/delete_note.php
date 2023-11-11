<?php
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete'])) {
    $noteID = $_GET['delete'];

    // Delete the note from the database
    $stmt = $conn->prepare("DELETE FROM `tbl_notes` WHERE tbl_notes_id = :note_id");
    $stmt->bindParam(':note_id', $noteID);

    if ($stmt->execute()) {
        echo "Note deleted successfully!";
        // Redirect back to the update.php page with a success message
        header("Location: ../index2.php");
        exit();
    } else {
        echo "Failed to delete note. Please try again.";
        // Redirect back to the update.php page with an error message
        header("Location: ../index2.php");
        exit();
    }
}
?>
