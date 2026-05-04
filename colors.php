<!DOCTYPE html>

<?php
    require_once("db.php");

    if(isset($_POST['new_color']) && isset($_POST['new_hex'])){
        
    }
?>

<html>
    <head>
        <title>Color Selection - HueMaxer</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <header>
            <img src="images/logo.png" alt="Company Logo" class="logo">
            <h1>Color Selection</h1>
        </header>

        <nav>
            <a href="index.php">Homepage</a>
            <a href="about.php">About</a>
            <a href="color.php">Color Coordinate</a>
            <a href="colors.php">Color Selection</a>
        </nav>

        <div class="page_body">
            <h2>Color Selection</h2>
            <p>Manage the colors in the Color Coordinator. You can add, edit, and remove colors from the list</p>
            <h2>Add a Color</h2>
            <form method="POST">
                Color Name: <input type = "text" name = "new_color" placeholder = "e.g. Mauve"/></br>
                Hex Value: <input type = "text" name = "new_hex" placeholder = "#E0AFFF"/>
                <button type="submit">Add Color</button>
            </form>
            <h2>Edit a Color</h2>
            <form method="POST">
                New Name: <input type = "text" name = "edit_color"/></br>
                New Hex Value: <input type = "text" name = "edit_hex"/>
                <button type="submit">Save Changes</button>
            </form>
            <h2>Delete a Color</h2>
            <h2>Current Colors</h2>
        </div>
    </body>
</html>