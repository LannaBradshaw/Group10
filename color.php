<!DOCTYPE html>

<?php
    require_once("db.php");
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
            $result = $conn->query("SELECT COUNT(*) as total FROM colors");
            $row = $result->fetch_assoc();
            $max_colors = $row['total'];

            if($color_ct < 1 || $color_ct > $max_colors){
                $color_error = "Color count must be between 1 and $max_colors";
            }
        }

        if($grid_error == "" && $color_error == ""){
            $given_input = true;
        }
    }   
    function make_color_picker($color_num){
        global $conn;
        echo '<form method="GET" action="print.php">';
        echo '<table class="color_picker_table">';

        $result = $conn->query("SELECT name, hex_value FROM colors");   
        $colors = [];

        while($row = $result->fetch_assoc()){
            $colors[] = $row;
        }

        for($i = 0; $i < $color_num; $i++){
            echo '<tr>';

            echo '<td style="width: 30%">';

            echo '<input type="radio" name="activeColor" value="'.$i.'" '.($i==0?'checked':'').'> ';

            echo '<select name="color'.$i.'" onchange="checkDuplicate(this)">';
            for($j = 0; $j < count($colors); $j++){
                $selected = ($i == $j) ? "selected" : "";
                echo '<option value="'.$colors[$j]['hex_value'].'" '.$selected.'>';
                echo $colors[$j]['name'];
                echo '</option>';
            }
            echo '</select>';

            echo '</td>';
            echo '<td id="coords'.$i.'" style="width:70%"></td>';
            echo '</tr>';        
        }

    echo "</table>";

    echo '<input type="hidden" name="grid_size" value="'.$GLOBALS['grid_size'].'">';
    echo '<input type="hidden" name="colors" value="'.$GLOBALS['color_ct'].'">';

    echo '<br><button type="submit">Printable View</button>';
    echo 'input type="hidden" name="colorData" id="colorData">';
    echo '</form>';
    }

    function make_grid($row_ct){
        echo '<table class="grid">';
        echo '<tr><td></td>';
        for($c = 0; $c < $row_ct; $c++){
            echo "<th>".chr(65 + $c)."</th>";
        }
        echo "</tr>";

        for($r = 0; $r < $row_ct; $r++){
            echo "<tr>";
            echo "<th>".($r+1)."</th>";
            for($c = 0; $c < $row_ct; $c++){
                $coord = chr(65 + $c) . ($r + 1);
                echo "<td class='grid-cell' data-coord='".$coord."'></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
?>

<!DOCTYPE html>
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
            <a href="colors.php">Color Selection</a>
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
        let activeColorIndex = 0;
        let colorCoordinates = {};
        let previousValues = {};

        document.addEventListener("change", function(e) {
            if (e.target.name === "activeColor") {
                activeColorIndex = parseInt(e.target.value);
            }
        });
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("select").forEach((select, i) => {
                previousValues[i] = select.value;
            });
            document.querySelectorAll(".grid-cell").forEach(cell => {
                cell.addEventListener("click", function() {
                    let coord = this.dataset.coord;
                    let selects = document.querySelectorAll("select");
                    let color = selects[activeColorIndex].value;

                    let oldColor = this.dataset.color;
                    if(oldColor && colorCoordinates[oldColor]) {
                        colorCoordinates[oldColor] = colorCoordinates[oldColor].filter(c => c !== coord);
                    }
                    
                    this.style.backgroundColor = color;
                    this.dataset.color = color;

                    if (!colorCoordinates[color]) {
                        colorCoordinates[color] = [];
                    }
                    if (!colorCoordinates[color].includes(coord)) {
                        colorCoordinates[color].push(coord);
                        colorCoordinates[color].sort((a,b)=>{
                            if(a[0] === b[0]){
                                return parseInt(a.slice(1)) - parseInt(b.slice(1));
                            }
                            return a.charCodeAt(0) - b.charCodeAt(0);
                        });
                    }
                    updateCoordinateDisplay();
                });
            });
        });
        function updateCoordinateDisplay() {
            document.querySelectorAll("select").forEach((select, i) => {
                let color = select.value;
                let coords = colorCoordinates[color] || [];
                document.getElementById("coords" + i).textContent = coords.join(", ");
            });
        }  
        function checkDuplicate(selectElement) {
            const selects = document.querySelectorAll("select");
            let currentValue = selectElement.value;
            let index = Array.from(selects).indexOf(selectElement);

            if (!(index in previousValues)) {
                previousValues[index] = currentValue;
            }

            for(let i = 0; i < selects.length; i++){
                if (i !== index && selects[i].value === currentValue) {
                    selectElement.value = previousValues[index];
                    showMessage("That color is already selected!");
                    return;
                }
            }
            let oldColor = previousValues[index];
            let newColor = currentValue;

            recolorGrid(oldColor, newColor);
            previousValues[index] = currentValue;
        }
        
        function recolorGrid(oldColor, newColor) {
            document.querySelectorAll(".grid-cell").forEach(cell => {
                if (cell.dataset.color === oldColor) {
                    cell.dataset.color = newColor;
                    cell.style.backgroundColor = newColor;
                }
            });
            if (colorCoordinates[oldColor]) {
                colorCoordinates[newColor] = colorCoordinates[oldColor];
                delete colorCoordinates[oldColor];
            }
            updateCoordinateDisplay();
        }
        function showMessage(msg) {
            let box = document.getElementById("colorMessage");
            if (!box) {
                box = document.createElement("p");
                box.id = "colorMessage";
                box.style.color = "red";
                document.querySelector(".page_body").prepend(box);
            }
            box.textContent = msg;
            setTimeout(() => {box.textContent = "";}, 2000);
            }

        document.querySelector("form").addEventListener("submit", function() {
            document.getElementById("colorData").value = JSON.stringify(colorCoordinates);
        });
        </script>
    </body>
</html>