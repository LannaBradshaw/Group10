<!DOCTYPE html>

<?php
    $grid_size = -1;
    $color_ct = -1;
    $message = "";
    $given_input = false;

    if(isset($_POST['grid_size']) && isset($_POST['colors'])){
        if(is_numeric($_POST['grid_size']) && is_numeric($_POST['colors'])){
            $grid_size = intval($_POST['grid_size']);
            $color_ct = intval($_POST['colors']);

            if($grid_size < 1 || $grid_size > 26){
                $message = "Grid size should be between 1 - 26";
            }
            elseif($color_ct < 1 || $color_ct > 10){
                $message = "Color count should be between 1 - 10";
            }
            else
                $given_input = true;
        }
        elseif(is_numeric($_POST['grid_size']) || is_numeric($_POST['colors'])){
            $message = "Please enter both a grid size and color count";
        }
        elseif($_POST["grid_size"] || $_POST["colors"]){
            $message = "Grid size and color count must be integers";
        }
    }

    function make_color_picker($color_num){
        echo '<table class="color_picker_table">';
        for($i = 0; $i < $color_num; $i++){
            echo 
            '<tr>
                <td style="width: 20%">
                    <select>
                        <option value="red">Red</option>
                        <option value="orange">Orange</option>
                        <option value="yellow">Yellow</option>
                        <option value="green">Green</option>
                        <option value="blue">Blue</option>
                        <option value="purple">Purple</option>
                        <option value="grey">Grey</option>
                        <option value="brown">Brown</option>
                        <option value="black">Black</option>
                        <option value="teal">Teal</option>
                    </select>
                </td>
                <td style="width: 80%"></td>
            </tr>
            ';
        }
        echo "</table>";
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
            <p><?php echo $message; ?></p>

            <?php 
                if($given_input){
                    echo "<h2>Color Selector</h2>";
                    make_color_picker($color_ct);
                    echo "<h2>Coordinate Grid</h2>";
                    make_grid($grid_size);
                }
            ?>
        </div>
    </body>
</html>