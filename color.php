<!DOCTYPE html>

<?php
    $grid_size = -1;
    $colors = -1;
    $message = "";

    if(isset($_POST['grid_size']) && isset($_POST['colors'])){
        if(is_numeric($_POST['grid_size']) && is_numeric($_POST['colors'])){
            $grid_size = intval($_POST['grid_size']);
            $colors = intval($_POST['colors']);

            if($grid_size < 1 || $grid_size > 26){
                $message = "Grid size should be between 1 - 26";
            }
            elseif($colors < 1 || $colors > 10){
                $message = "Color count should be between 1 - 10";
            }
            else
                $message = "Grid Size: ". $grid_size. "<br /> Color Count: ". $colors;
        }
        elseif(is_numeric($_POST['grid_size']) || is_numeric($_POST['colors'])){
            $message = "Please enter both a grid size and color count";
        }
        elseif($_POST["grid_size"] || $_POST["colors"]){
            $message = "Grid size and color count must be integers";
        }
    }
?>

<html>
    <head>
        <title>Color Coordinate - HueMaxer</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <header>
            <h1>Color Coordinate</h1>
        </header>

        <nav>
            <a href="index.php">Homepage</a>
            <a href="about.php">About</a>
            <a href="color.php">Color Coordinate</a>
        </nav>

        <div class="page_body">
            <form method="POST">
                Grid Size (1 - 26): <input type = "text" name = "grid_size" />
                Number of Colors (1 - 10): <input type = "text" name = "colors" />
                <input type = "submit" />
            </form>
            <p><?php echo $message; ?></p>
        </div>
    </body>
</html>