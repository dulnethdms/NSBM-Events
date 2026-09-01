<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BROWSE EVENTS</title>
    <?php require_once "database\db_onnect.php" ?>
    <?php require_once "student_events\pages\event_details.php" ?>
</head>
<body>
    <?php require_once "student_events\essentials\header.php" ?>

    <?php
        require_once "database\eventsdb_connect.php"
        $sql = "SELECT*FROM event_details"

        $result = $conn->query($sql);

        while ($event = $result->fetch_assoc()) {
    ?>

        <div class="eventCard">
            <img id="cardCoverPhoto" src="<?php echo $event["photoPath"]; ?>">
            <h2 id="cardHeading">
                <?php echo $event["eventName"]; ?>
            </h2>

            <p id="cardDescription">
                <?php echo $event["eventCategory"]; ?>
            </p>

            <form action="event_details.php" method="POST">
                <input type="hidden"
                name="eventId"
                value=<?php $event["eventId"]; ?>>
                <button type="submit">
                    VIEW MORE
                </button>
            </form>
        </div>

    <?php
    }
    ?>

    <?php require_once "student_events\essentials\footer.php" ?>
        
</body>
</html>