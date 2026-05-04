<!DOCTYPE html>

<?php
    require_once("db.php");

    if(isset($_POST['new_color']) && isset($_POST['new_hex'])){
        $last_id = "SELECT * FROM colors WHERE id = (SELECT MAX(id) FROM colors)";
        $conn->query("INSERT INTO colors VALUES($last_id, new_color, new_hex)");
    }

    function printColors(){
        global $conn;
        $result = $conn->query("SELECT * FROM colors;");
        if($result -> num_rows > 0){
            echo "<table>
                    <tr>
                        <th>Color Name</th>
                        <th>Hex Code</th>
                        <th>Preview</th>
                    <tr>";
            while($row = $result->fetch_assoc()){
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['hex_value']}</td>
                        <td></td>
                    </tr>";
            }
        }
        else{
            echo "<tr><td colspan='3'>No data found</td></tr>";
        }
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
            <?php
                printColors();
            ?>
        </div>
    </body>
</html>