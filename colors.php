<!DOCTYPE html>

<?php
    require_once("db.php");
    $error_msg = "";

    if(isset($_POST['new_color']) && isset($_POST['new_hex'])){
        $name = $_POST['new_color'];
        $hex = strtoupper($_POST['new_hex']);

        if ($name !== "" && $hex !== "" && testValueDupe($name, $hex)) {
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

        if ($name !== "" && $hex !== "" && testValueDupe($name, $hex)) {
            $to_update = $conn->prepare("UPDATE colors SET name = ?, hex_value = ? WHERE hex_value = ?");
            $to_update->bind_param("sss", $name, $hex, $color_to_replace);
            $to_update->execute();
            $to_update->close();
        }
    }


    $confirm_delete = false;
    $delete_color = null;
    if(isset($_POST['delete_request'])){
        $delete_color = $_POST['color'];

        $count = $conn->prepare("SELECT COUNT(*) AS total FROM colors");
        $count->execute();

        $result = $count->get_result();
        $row = $result->fetch_assoc();

        if($row['total'] < 2){
            $error_msg = "There must be at least 2 colors in the database";
        } else{
            $confirm_delete = true;
        }
    }

    if(isset($_POST['confirm_delete'])){
        $color = $_POST['delete_color'];
        $for_confirm = $conn->prepare("DELETE FROM colors WHERE hex_value = ?");
        $for_confirm->bind_param("s", $color);
        $for_confirm->execute();
        $for_confirm->close();

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

            $check = $conn->prepare("SELECT id FROM colors WHERE name = ? OR hex_value = ?");
            $check->bind_param("ss", $name, $hex);
            $check->execute();
            $result = $check->get_result();

            if($result->num_rows > 0){
                $error_msg = "Color name or hex already exists!";
                return false;
            }
            return true;
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
                echo "<tr>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>{$row['hex_value']}</td>";
                        $color = $row['hex_value'];
                        echo "<td style='background-color:$color; width:50px;'></td>";
                    echo "</tr>";
            }
        }
        else{
            echo "<tr><tdv colspan='3'>No data found</td></tr>";
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

            <?php if(!$confirm_delete){ ?>
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
                <button type ="submit" name="delete_request">Delete</button>
            </form>
            <?php } else { ?>


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
                <input type="hidden" name="delete_color" value="<?php echo $delete_color; ?>">
                <button type="submit" name="confirm_delete">Confirm</button>
            </form>

            <?php } ?>
            <h2>Current Colors</h2>
            <?php
                printColors();
            ?>
        </div>
    </body>
</html>
