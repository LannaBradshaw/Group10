<!DOCTYPE html>

<?php
    $grid_error = "";
    $color_error = "";
    $given_input = false;

    if(isset($_POST['grid_size']) && isset($_POST['colors'])){

        if(!is_numeric($_POST['grid_size'])){
            $grid_error = "Grid size must be an integer";
        } else {
            $grid_size = intval($_POST['grid_size']);
            if($grid_size < 1 || $grid_size > 26){
                $grid_error = "Grid size must be between 1 and 26";
            }
        }

        if(!is_numeric($_POST['colors'])){
            $color_error = "Color count must be an integer";
        } else {
            $color_ct = intval($_POST['colors']);
            if($color_ct < 1 || $color_ct > 10){
                $color_error = "Color count must be between 1 and 10";
            }
        }

        if($grid_error == "" && $color_error == ""){
            $given_input = true;
        }
    }   

function make_color_picker($color_num){
    echo '<form method="GET" action="print.php">';
    echo '<table class="color_picker_table">';

    $colors = ["Red","Orange","Yellow","Green","Blue","Purple","Grey","Brown","Black","Teal"];

    for($i = 0; $i < $color_num; $i++){
        echo '<tr><td style="width: 20%">';

        echo '<select name="color'.$i.'" onchange="checkDuplicate(this)">';

        for($j = 0; $j < count($colors); $j++){
            $selected = ($i == $j) ? "selected" : "";
            echo '<option value="'.$colors[$j].'" '.$selected.'>'.$colors[$j].'</option>';
        }

        echo '</select>';

        echo '</td><td style="width: 80%"></td></tr>';
    }

    echo "</table>";

    echo '<input type="hidden" name="grid_size" value="'.$GLOBALS['grid_size'].'">';
    echo '<input type="hidden" name="colors" value="'.$GLOBALS['color_ct'].'">';

    echo '<br><button type="submit">Printable View</button>';
    echo '</form>';
}

    function make_grid($row_ct){
        $letter = 'A';
        $number = 1;

        echo 
        '<table class="grid">
            <tr>
                <td></td>
        ';
        for($i = 0; $i < $row_ct; $i++){
            echo "<th>$letter</th>";
            $letter++;
        }
        for($r = 0; $r < $row_ct; $r++){
            echo "<tr><th>$number</th>";
            for($c = 0; $c < $row_ct; $c++)
                echo "<td></td>";
            echo "</tr>";
            $number++;
        }
        echo "</table>";
    }
?>

<html>
    <head>
        <title>Color Coordinate - HueMaxer</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <header>
            <img src="images/logo.png" alt="Company Logo" class="logo">
            <h1>Color Coordinate</h1>
        </header>

        <nav>
            <a href="index.php">Homepage</a>
            <a href="about.php">About</a>
            <a href="color.php">Color Coordinate</a>
        </nav>

        <div class="page_body">
            <h2>Color Coordinator</h2>
            <p>Please select your grid size (row and column count) and number of colors</p>
            <form method="POST">
                Grid Size (1 - 26): <input type = "text" name = "grid_size" /></br>
                Number of Colors (1 - 10): <input type = "text" name = "colors" />
                <button type="submit">Generate</button>
            </form>
            <p style="color:red;"><?php echo $grid_error; ?></p>
            <p style="color:red;"><?php echo $color_error; ?></p>

            <?php 
                if($given_input){
                    echo "<h2>Color Selector</h2>";
                    make_color_picker($color_ct);
                    echo "<h2>Coordinate Grid</h2>";
                    make_grid($grid_size);
                    
                }
            ?>
        </div>

        <script>
        let previousValues = {};
        function checkDuplicate(selectElement) {
            const selects = document.querySelectorAll("select");
            let currentValue = selectElement.value;
            let index = Array.from(selects).indexOf(selectElement);
            if (!(index in previousValues)) {
                previousValues[index] = currentValue;
            }

            for (let i = 0; i < selects.length; i++) {
                if (i !== index && selects[i].value === currentValue) {
                    selectElement.value = previousValues[index];
                    showMessage("That color is already selected!");
                return;
                }
            }
            previousValues[index] = currentValue;
        }
        
        function showMessage(msg) {
            let messageBox = document.getElementById("colorMessage");

            if (!messageBox) {
                messageBox = document.createElement("p");
                messageBox.id = "colorMessage";
                messageBox.style.color = "red";
                document.querySelector(".page_body").prepend(messageBox);
            }
            messageBox.textContent = msg;
            setTimeout(() => {
                messageBox.textContent = "";
            }, 2000);
        }
        </script>
    </body>
</html>