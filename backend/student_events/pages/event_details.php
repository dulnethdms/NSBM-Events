<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "database\eventsdb_connect.php";
        $Id = $_POST["eventId"];
        $sql = "SELECT eventName FROM event_details WHERE eventId=$Id;";

        $title = $conn->query($sql);
    ?>
    <title><?php echo $title; ?></title>
</head>
<body>
    <?php require_once "student_events\essentials\header.php" ?>
    <div class="eventContent">
        <h1>
            <?php echo $title; ?>
        </h1>
        
        <div class="imageCarousel">
            <>
        </div>
    </div>

    <?php require_once "student_events\essentials\footer.php" ?>
</body>
</html>