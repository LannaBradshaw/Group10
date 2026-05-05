<?php
require_once("db.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print View</title>
    <link rel="stylesheet" type="text/css" href="print.css">

</head>


<body>

<?php
$grid_size = $_GET['grid_size'];
$color_ct = $_GET['colors'];
$colorData = json_decode($_GET['colorData'], true);

$selectedColors = [];

for($i = 0; $i < $color_ct; $i++){
    $hex = $_GET['color'.$i];

    $result = $conn->query("SELECT name FROM colors WHERE hex_value='$hex'");
    $row = $result->fetch_assoc();

    $selectedColors[] = [
        "name" => $row['name'],
        "hex" => $hex
    ];
}
?>
<a href="color.php" class="back-btn">← Back to Color Coordinator</a>
<img src="images/logo_greyscale.png" alt="Company Logo" class="logo">
<h1>HueMaxer</h1>
<p>Professional Color Coordinate Sheet - Printable View</p>
<hr>


<?php
    echo "<table>";

        echo "<tr>";
        echo "<th class='table-header'>Color</th>";
        echo "<th class='table-header'>Coordinates</th>";
        echo "</tr>";

for($i = 0; $i < $color_ct; $i++){
    $hex = $selectedColors[$i]['hex'];
    $coords = isset($colorData[$hex]) ? implode(", ", $colorData[$hex]) : "";

    echo "<tr>";

    echo "<td class='color-cell'>";
    echo "<strong>".$selectedColors[$i]['name']."</strong> — ".$hex;
    echo "</td>";

    echo "<td class='coord-cell'>";
    echo $coords;
    echo "</td>";

    echo "</tr>";
}
echo "</table>";
?>

<h2>Coordinate Grid</h2>

<table>
<tr>
    <td></td>
<?php
for($i = 0; $i < $grid_size; $i++){
    echo "<th>".chr(65 + $i)."</th>";
}
echo "</tr>";

for($r = 1; $r <= $grid_size; $r++){
    echo "<tr>";
    echo "<th>$r</th>";

    for($c = 1; $c <= $grid_size; $c++){
        echo "<td></td>";
    }

    echo "</tr>";
}
?>
</table>

<br>
<button class="print-btn" onclick="window.print()">Print</button>

</body>
</html>
