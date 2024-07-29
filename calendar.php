<?php
session_start();
include 'backend/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danica - Outfit Planning Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fbeff7; 
        }
        .navbar {
            background-color: #ff69b4; 
        }
        .navbar-brand {
            color: blue;
            text-shadow: 2px 2px #000;
        }
        .calendar-container {
            margin: 20px auto;
            max-width: 900px;
        }
        .footer {
            text-align: center;
            padding: 1rem;
            background-color: #ff69b4;
            color: #ffffff; 
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Danica</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>   
                <li class="nav-item">
                    <a class="nav-link" href="backend/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="calendar-container">
            <h2 class="text-center welcome-text">Outfit Planning Calendar</h2>
            <div id="calendar"></div>
        </div>
    </div>

    <footer class="footer">
        &copy; 2024 Danica
    </footer>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'fetch_events.php',
                editable: true,
                selectable: true,
                select: function(info) {
                    var title = prompt('Enter Event Title:');
                    if (title) {
                        var eventData = {
                            title: title,
                            start: info.startStr,
                            end: info.endStr
                        };
                        fetch('add_event.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(eventData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                calendar.addEvent(eventData);
                            } else {
                                alert('Error adding event: ' + data.message);
                            }
                        });
                    }
                    calendar.unselect();
                },
                eventClick: function(info) {
                    var eventId = info.event.id;
                    var eventTitle = info.event.title;
                    var eventStart = info.event.startStr;
                    var eventEnd = info.event.endStr;
                    // Redirect to suggest.php with event details as query parameters
                    window.location.href = 'suggest.php?title=' + encodeURIComponent(eventTitle) +
                                             '&start=' + encodeURIComponent(eventStart) +
                                             '&end=' + encodeURIComponent(eventEnd);
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>
