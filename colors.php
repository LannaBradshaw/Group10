<!DOCTYPE html>
<?php
require 'db.php';

$hex_error = "";
$color_add = "";


/* Add Color */
if(isset($_POST['color']) && isset($_POST['hex'])){

    $name = trim($_POST['color']);
    $hex = trim($_POST['hex']);

    if(!preg_match('/#?[0-9a-f]{6}/',$hex)){
        $hex_error = "Hex code must follow this format #000000";
    } else{
        $stmt = $conn -> prepare("INSERT INTO colors (name, hex_value) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $hex);
        if($stmt->execute()){
            $color_add = "Color '$name' successfully added";
        } else{
            $color_add = "Color or Hex already exists";
        }
    }
}

/*color helper for edit and delete dropdown*/
function getColors($conn){
    
    $colors = [];
    $query = $conn->query("SELECT * FROM colors ORDER BY name ASC");

    while($row = $query->fetch_assoc()){
        $colors [] = $row;
    }
    return $colors;
}

$selectedId = null;
$selectedColor = null;

/*Drop down helper*/
$colors = getColors($conn);
$colorCount = count($colors);

function makeColorDropdown($colors) {
    $selectedId = $_POST['id'] ?? null;

    echo '<select name="id" required>';

    for ($i = 0; $i < count($colors); $i++) {
        $selected = ($selectedId == $colors[$i]['id']) ? 'selected' : '';

        echo '<option value="'.$colors[$i]['id'].'" '.$selected.'>'.$colors[$i]['name'].'</option>';
    }

    echo '</select>';
}

/*Delete Color */
$color_delete = "";

if (isset($_POST['delete'])) {

    $selectedId = $_POST['id'];

    for ($i = 0; $i < count($colors); $i++) {
        if ($colors[$i]['id'] == $selectedId) {
            $selectedColor = $colors[$i];
            break;
        }
    }
}


if (isset($_POST['confirm'])) {

    $id = $_POST['id'];

    if ($colorCount < 2) {
        $color_delete = "Cannot have less than 2 colors";
    } else {

        $sql = "DELETE FROM colors WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}


    
        


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
                Color Name: <input type = "text" name = "color" /><br>
                Hex Value: <input type = "text" name = "hex" /><br>
                <button type="submit">Add a Color</button>
            </form>
            <p style="color:red;"><?php echo $hex_error; ?></p>
            <p style="color:red;"><?php echo $color_add; ?></p><br>

            <h2>Delete a Color</h2>
            <form method="POST">
                Select a color:<br>
                <?php makeColorDropdown($colors);?>
                <button type="submit" name="delete">Delete</button>
            </form>

            <?php if ($selectedColor && isset($_POST['delete'])): ?>
                <?php if($colorCount <2):?>
                    <p style="color:red;"><?php echo $color_delete; ?></p>
                <?php else: ?>
                    <p style="color:red;">Delete <?php echo $selectedColor['name']; ?>?</p><br>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
                            <button type="submit" name="confirm">Confirm Deletion</button>
                        </form>
                 <?php endif; ?>
            <?php endif; ?>  
        </div>
    </body>
</html>
