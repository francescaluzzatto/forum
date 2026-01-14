<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <style>
        body{
            font-family: Georgia;
            align-items:center;
            justify-content:center;
        }
        .p{
            font-size: 1.5rem;
            margin: 20px;
            text-align:center; 
        }
        </style>
</head>    
<?php 
$conn = new mysqli('localhost', 'root', '', 'forum');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$title = $_POST['title'];
$content = $_POST['content'];
$categories = [];


if(isset($_POST['categories']) && !empty($_POST['categories'])) {
    $categories[] = $_POST['categories'];
}

//var_dump($categories);
$i = 1;
while(isset($_POST['categories'.$i])) {
    if (!empty($_POST['categories'.$i])) {
        $categories[] = $_POST['categories'.$i];
    }
    $i++;
}
//var_dump($categories);

if(isset($_POST['newcategory']) && !empty($_POST['newcategory'])) {
    $newCategory = $_POST['newcategory'];
    $categories[] = $newCategory;
}
//var_dump($categories);
$categories_json= json_encode($categories);
$stmt = $conn->prepare("INSERT INTO discussion (title, content, categories) values(?, ?, ?)");
$stmt->bind_param("sss", $title, $content, $categories_json);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "<div><p class= 'p'>Discussion saved successfully. Go have a look on the home page to see it!</p></div><br>
            <p class='p'> <a href= 'index.php'>Home Page</a>";
    } else {
        echo "<div><p class= 'p'>No changes made or error occurred.</p></div><br>
            <p class='p'> <a href= 'index.php'>Home Page</a>";
    }
    $stmt->close();
$sql= "SELECT id FROM discussion WHERE title= '$title' AND content= '$content'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $id= $row['id'];
    }
} else {
    echo "0 results";
}
$stmt = $conn->prepare("INSERT INTO chatbox (id) values(?)");
$stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
$conn->close();

?>
