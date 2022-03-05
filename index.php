<?php

/**
 * @author Laura Hidalgo Rivera
 * 
 * Dado el mes y año almacenados en variables, escribir un programa que muestre el calendario mensual
 * correspondiente. Marcar el día actual en verde y los festivos en rojo.
 */

include "arrays.php";

session_start();
if (!isset($_SESSION['tareas'])) {
    $_SESSION['tareas'] = array();
}

$monthSelected = date('m');
$yearSelected = date('Y');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        if (isset($_POST["month"])) {
            $monthSelected = $_POST["month"];
        }

        if (isset($_POST["year"])) {
            $yearSelected = $_POST["year"];
        }
    }
}

$firstDay = date("l", mktime(0, 0, 0, date("$monthSelected"), 1, date("$yearSelected")));
$firstDayPosition = $weekdays[$firstDay];
$day = 0;
$dayNextMonth = 1;
$today = date('d');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Laura Hidalgo Rivera">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <link rel="stylesheet" href="calendario.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="7">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <select name="month">
                            <?php
                            foreach ($months as $key => $value) {
                                $selected = ($monthSelected == $value["value"]) ? 'selected' : '';
                                echo "<option value = \"" . $value["value"] . "\" $selected >" . $value["literal"] . "</option>";
                            }
                            ?>
                        </select>
                        <input type="number" name="year" min="1922" max="2122" value="<?= $yearSelected; ?>">
                        <input type="submit" name="submit" value="&#10003;" id="submit">
                    </form>
                </th>
            </tr>
            <tr>
                <?php
                foreach ($initials as $key) {
                    echo "<th>$key</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < 5; $i++) {
                echo "<tr>";
                for ($j = 1; $j < 8; $j++) {
                    echo "<td";
                    if ($i == 0 && $j == $firstDayPosition) {
                        $day++;
                    }
                    if (isHoliday($day, $monthSelected, $holidays)) {
                        echo (" class=\"" . typeOfHoliday($day, $monthSelected, $holidays) . "\"");
                    }
                    if ($day == $today && $monthSelected == date('m')) {
                        echo " id=\"today\"";
                    }
                    if ($j == 7) {
                        echo (isNextMonth($day, $monthSelected, $yearSelected) ? " class=\"nextMonth\"" : " class=\"sunday\"");
                    }
                    if (isNextMonth($day, $monthSelected, $yearSelected)) {
                        echo " class=\"nextMonth\">$dayNextMonth</td>";
                        $dayNextMonth++;
                    } elseif ($i == 0 && $j < $firstDayPosition) {
                        echo "></td>";
                    } else {
                        echo "><a href=\"tarea.php?id=$day\">$day </a></td>";
                        $day++;
                    }
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="legend">
        <p class="sunday">Domingo</p>
        <p class="national">Festivo nacional</p>
        <p class="comm">Festivo Comunidad</p>
        <p class="local">Festivo local</p>
    </div>
</body>

</html>

<?php


/**
 * Devuelve el número de días del mes indicado por parámetro
 */
function daysInMonth($month, $year)
{
    return cal_days_in_month(CAL_GREGORIAN, $month, $year);
}

/**
 * Devuelve si el día corresponde al mes siguiente
 */
function isNextMonth($day, $month, $year)
{
    return $day > daysInMonth($month, $year);
}

function isHoliday($day, $month, $holidays)
{
    foreach ($holidays as $key => $value) {
        if ($month == $value['month'] && $day == $value['day']) {
            return true;
        }
    }
    return false;
}

function typeOfHoliday($day, $month, $holidays)
{
    foreach ($holidays as $key => $value) {
        if ($month == $value['month'] && $day == $value['day']) {
            switch ($value['type']) {
                case 'national':
                    return "national";
                case 'comm':
                    return "comm";
                case 'local':
                    return "local";
                default:
                    return;
            }
        }
    }
    return;
}

?>