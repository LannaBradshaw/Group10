<!DOCTYPE html>
<html>
<head>
    <title>Print View</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
    body {
        background: white;
        color: black;
        text-align: center;
        font-family: Arial, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    td, th {
        border: 1px solid black;
        text-align: center;
        padding: 5px;
        height: 30px;
        width: 30px;
    }

    @page {
        size: letter portrait;
        margin: 1in;
    }

    @media print {
        button {
            display: none;
        }
    }
    </style>

</head>


<body>

<?php
$grid_size = $_GET['grid_size'];
$color_ct = $_GET['colors'];

$selectedColors = [];

for($i = 0; $i < $color_ct; $i++){
    $selectedColors[] = $_GET['color'.$i];
}
?>
<img src="images/logo_greyscale.png" alt="Company Logo" class="logo">
<h1>HueMaxer</h1>
<p>Color Coordinate Sheet</p>
<hr>

<h2>Selected Colors</h2>

<table>
<?php
for($i = 0; $i < $color_ct; $i++){
    echo "<tr>";
    echo "<td style='width:20%'>".($i+1)."</td>";
    echo "<td style='width:80%'>".$selectedColors[$i]."</td>";
    echo "</tr>";
}
?>
</table>

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
<button onclick="window.print()">Print</button>

</body>
</html>