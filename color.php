<!DOCTYPE html>

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
            <form action="<?php $_PHP_SELF ?>" method="POST">
                Row (1 - 26): <input type = "text" name = "row" />
                Column (1 - 10): <input type = "text" name = "col" />
                <input type = "submit" />
            </form>
        </div>
    </body>
</html>