<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="NSBM-Event-Planning-n-Scheduling-System\assets\css\student_events_stylesheets\headerStyle.css">
        <script src="assets\js\animations\gsap.min.js">
        <script src="assets\js\animations\lenis.min.js">
    </head>
    <body>
        <header id="headerStudentEvent">
            <div class="hamburgerMenu">
                <div id="menuIcon">     <!--snap is edited as the logo in the stylesheet-->
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div id="linkList">
                    <a href="index.html">HOME</a>   <!--check the href-->
                    <a href="event_browse.php">BROWSE EVENTS</a>    <!--check the href-->
                    <a href="event_register.php">REGISTER FOR EVENTS</a>    <!--check the href-->
                    <a href="my_schedule.php">MY SCHEDULE</a>   <!--check the href-->
                </div>
            </div>

            <div id="logo">
                <a href="NSBM-Event-Planning-n-Scheduling-System\index.html">NSBM EVENTHUB</a>
            </div>

            <div class="userPanelButton">
                <button>user controls</button>
                <div id="userControlLinks">
                    <a href="dashboard.html">DASHBOARD</a>   <!--check the href-->
                    <form action="logout.php" method="POST">
                        <button type="submit" class="logout-link">LOGOUT</button>
                    </form>
                </div>
            </div>
        </header>
    </body>
</html>