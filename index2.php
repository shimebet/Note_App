<?php
include('conn/conn.php');

// Function to get the available note colors from the database
function getNoteColors(PDO $conn)
{
    $stmt = $conn->query("SELECT DISTINCT color FROM tbl_notes");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Function to handle note submission
function handleNoteSubmission(PDO $conn)
{
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $noteTitle = $_POST['note_title'];
        $noteContent = $_POST['note_content'];
        $noteColor = $_POST['note_color'];

        // Use $noteColor in your database insertion logic
        $stmt = $conn->prepare("INSERT INTO tbl_notes (note_title, note, color) VALUES (:title, :content, :color)");
        $stmt->bindValue(':title', $noteTitle, PDO::PARAM_STR);
        $stmt->bindValue(':content', $noteContent, PDO::PARAM_STR);
        $stmt->bindValue(':color', $noteColor, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

handleNoteSubmission($conn);
$showChar = 100;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Take-Note App</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="jscolor.js"></script>
    <script src="jscolor.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Custom CSS */
        .main-panel,
        .card {
            margin: auto;
            height: 90vh;
            overflow-y: auto;
        }

        .note-content {
            max-height: 20em;
            overflow: hidden;
            text-overflow: ellipsis;
            /* white-space: nowrap; */
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark col-12">
        <a class="navbar-brand" href="#">Take-Note App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form class="form-inline " method="get" action="">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search by Title"
                            aria-label="Search" name="search_title" style="width:300px">
                        <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Account
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">View Account</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">Log Out</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-panel mt-4 ml-5 col-11">
        <div class="row">

            <!-- Add Note -->
            <div class="col-md-4 border-right">
                <div class="card">
                    <div class="card-header">
                        Add Note
                    </div>
                    <div class="card-body">
                        <form method="post" action="index2.php">
                            <div class="form-group">
                                <label for="noteColor">Select Color</label>
                                <input class="form-control jscolor" id="noteColor" name="note_color" value="#000000">
                            </div>

                            <div class="form-group">
                                <label for="noteTitle">Title</label>
                                <input type="text" class="form-control" id="noteTitle" name="note_title"
                                    placeholder="Title">
                                <small id="emailHelp" class="form-text text-muted">Title of your note</small>
                            </div>
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea class="form-control" id="note" name="note_content"
                                    rows="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Update and Delete Notes -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Notes Details
                        <a href="#" class="float-right">Actions</a>
                    </div>

                    <div class="card-body">
                        <div class="data-item">
                            <ul class="list-group">

                                <?php
                                $searchTitle = isset($_GET['search_title']) ? $_GET['search_title'] : '';

                                $stmt = $conn->prepare("SELECT * FROM tbl_notes WHERE note_title LIKE :searchTitle");
                                $stmt->bindValue(':searchTitle', '%' . $searchTitle . '%', PDO::PARAM_STR);
                                $stmt->execute();

                                $result = $stmt->fetchAll();

                                foreach ($result as $row) {
                                    $noteID = $row['tbl_notes_id'];
                                    $noteTitle = $row['note_title'];
                                    $noteContent = $row['note'];
                                    $noteDateTime = $row['date_time'];
                                    $noteColor = $row['color'];

                                    // Convert the date_time value to a formatted date and time string
                                    $formattedDateTime = date('F j, Y H:i A', strtotime($noteDateTime));
                                ?>

                                <li class="list-group-item mt-2">
                                    <div class="btn-group float-right">
                                        <a href="endpoint/update_note.php?edit=<?php echo $noteID ?>"><button
                                                type="button" class="btn btn-sm btn-light" title="Show"><i
                                                    class="fa fa-pencil"></i></button></a>
                                        <button onclick="delete_note('<?php echo $noteID ?>')" type="button"
                                            class="btn btn-sm btn-light" title="Remove"><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                    <h3 style="text-transform: uppercase; color: <?php echo $noteColor; ?>;"><b><?php echo $noteTitle ?></b></h3>
                                    <div id="note-container">
                                        <p class="note-content"><?php echo nl2br(htmlspecialchars(substr($noteContent, 0, $showChar))); ?><span class="morecontent"><span><?php echo nl2br(htmlspecialchars(substr($noteContent, $showChar))); ?></span></span></p>
                                        
                                    </div>

                                    <small
                                        class="block text-muted text-info">Created: <i
                                            class="fa fa-clock-o text-info"></i> <?php echo $formattedDateTime ?></small>
                                </li>
                                <?php
                                }
                                ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
      function delete_note(id) {
    if (confirm("Do you confirm to delete this note?")) {
        console.log("Deleting note with ID: ", id);
        window.location = "endpoint/delete_note.php?delete=" + id;
    }
}

    </script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

        <script>
$(document).ready(function () {
    var showChar = 100; // Adjust the number of characters to display initially
    var ellipsestext = "...";
    var moretext = "Read more";
    var lesstext = "Read less";

    $('.note-content').each(function () {
        var content = $(this).html();

        if (content.length > showChar) {
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html =
                c +
                '<span class="moreellipses">' +
                ellipsestext +
                '&nbsp;</span><span class="morecontent"><span>' +
                h +
                '</span>&nbsp;&nbsp;<a href="" class="morelink">' +
                moretext +
                '</a></span>';

            $(this).html(html);
        }
    });

    $(".morelink").click(function () {
        var content = $(this).prev().find("span:first-child");
        if ($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
            content.toggle();
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
            content.toggle();
        }
        return false;
    });
});

</script>

</body>

</html>
