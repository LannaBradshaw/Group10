<!DOCTYPE html>

<?php
    require_once("db.php");
    $error_msg = "";

    if(isset($_POST['new_color']) && isset($_POST['new_hex'])){
        $name = $_POST['new_color'];
        $hex = strtoupper($_POST['new_hex']);

        if(testValueDupe($name, $hex)){
            $to_insert = $conn->prepare("INSERT INTO colors (name, hex_value) VALUES (?, ?)");
            $to_insert->bind_param("ss", $name, $hex);
            $to_insert->execute();
            $to_insert->close();
        }
    }

    if(isset($_POST['color']) && isset($_POST['edit_color']) && isset($_POST['edit_hex'])){
        $color_to_replace = $_POST['color'];
        $name = $_POST['edit_color'];
        $hex = strtoupper($_POST['edit_hex']);

        if(testValueDupe($name, $hex)){
            $to_update = $conn->prepare("UPDATE colors SET name = ?, hex_value = ? WHERE hex_value = ?");
            $to_update->bind_param("sss", $name, $hex, $color_to_replace);
            $to_update->execute();
            $to_update->close();
        }
    }

    function testValueDupe($name, $hex){
        global $conn;
        global $error_msg;
        
        if(!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $hex)){
            $error_msg = "Invalid hex code!";
        }
        else{
            $name_test = $conn->prepare("SELECT id FROM colors WHERE name = ?");
            $name_test->bind_param("s", $name);
            $name_test->execute();
            $name_result = $name_test->get_result();
            $name_test->close();

            $hex_test = $conn->prepare("SELECT id FROM colors WHERE hex_value = ?");
            $hex_test->bind_param("s", $hex);
            $hex_test->execute();
            $hex_result = $hex_test->get_result();
            $hex_test->close();

            if($name_result->num_rows > 0 && $hex_result->num_rows > 0){
                $error_msg = "Color name AND hex code are already used in the database!";
            }
            elseif($name_result->num_rows > 0){
                $error_msg = "Color name is already used in the database!";
            }
            elseif($hex_result->num_rows > 0){
                $error_msg = "Hex value is already used in the database!";
            } 
            else{
                return true;
            }
        }
        return false;
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
            <?php
                if(!empty($error_msg)) {
                    echo "<b><p class='error' style='color: red'>$error_msg</p></b>";
                }
            ?>
            <h2>Add a Color</h2>
            <form method="POST">
                Color Name: <input type = "text" name = "new_color" placeholder = "e.g. Mauve"/></br>
                Hex Value: <input type = "text" name = "new_hex" placeholder = "#E0AFFF"/>
                <button type="submit">Add Color</button>
            </form>

            <h2>Edit a Color</h2>
            <form method="POST">
                <?php
                    global $conn;

                    $colors = $conn->query("SELECT name, hex_value FROM colors;");

                    echo 'Select Color: <select name="color" onchange="checkDuplicate(this)">';
                    while($row = $colors->fetch_assoc()){
                        echo '<option value="'.$row['hex_value'].'">';
                        echo $row['name'];
                        echo '</option>';
                    }
                    echo '</select><br>';
                ?>
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