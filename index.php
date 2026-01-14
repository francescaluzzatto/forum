<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <style>
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #222;
            font-family: Georgia;
            }
        .logo {
            color: #fff;
            font-size: 1.4rem;
            font-weight: bold;
            }
        .nav-links{
            list-style: none;
            display: flex;
            gap: 20px;
            margin-left: auto;
            }
        .nav-links li a {
            color: #fff;
            text-decoration: none;
            font-size: 20px;
            }
        .nav-links li a:hover {
            background-color: rgba(128, 128, 128, 0.3);
            border-radius: 10px;
            padding: 25px;
            }
        .hamburger{
            display:none;
            color: white;
        }
        @media (max-width: 768px) {
            .nav-links {
                position: absolute;
                top: 60px;
                right: 0;
                background: #222;
                flex-direction: column;
                width: 100%;
                text-align: center;
                padding-top: 20px;
                padding-bottom: 20px;
                display: none;
                border-radius: 10px;
            }

            .nav-links.active {
                display: flex;
            }

            .hamburger {
                display: flex;
                cursor: pointer;
            }
        }
        #discussionpost{
            position: relative;
            font-family: Georgia;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            text-align: center;
        }
        #pagefordiscussion{
            position: relative;
            height: 100vh;
          /* display:none;*/
        }
        #discussion{
            position: fixed;
            width: 700px;
            height: 500px;
            background-color: rgba(128, 128, 128, 0.7);
            bottom: 0;
            z-index: 999;
        }
        #chatbox{
            position: absolute;
            top:0;
            bottom:50px;
            width: 100%;
            overflow-y: scroll;
            z-index:-1;
            
        }   
        #write{
            position: absolute;
            bottom:0;
            width: 100%;
            display:flex;
        }
        #messageinput{
            width:80%; 
            height:30px; 
            border-radius:10px; 
            margin:10px;
        }
        #sendbutton{
            width:15%; 
            height:30px; 
            border-radius:10px; 
            margin:10px;
        }
        #closechatbox{
            position: absolute;
            top:10px;
            right:10px;
            z-index:1000;
        }

        
    </style>
</head>
<nav class="navbar">
  <div class="logo">Forum</div>

  <ul class="nav-links" id="navLinks">
    <li><a href="index.php">Home</a></li>
    <li><a href="creatediscussion.php">Create discussion</a></li>
  </ul>

  <div class="hamburger" id="hamburger">&#9776;
  </div>
</nav>
<form id="searchform" method="GET" action="index.php" style="display:flex; gap:10px; margin-top:10px; margin-left:10px;">
    <label for="search">Search Categories:</label>
    <input type="text" id="search" name="search" style="width:150px; height:20px; border-radius:10px;">
    <button type="submit">Search</button>
</form>
<?php
$conn = new mysqli('localhost', 'root', '', 'forum');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<script>
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');

    hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    });
</script>

<?php

$id= $_GET['id'] ?? null;
if (isset($id)){

    if ($_SERVER["REQUEST_METHOD"] === "POST") {        
            $stmt = $conn->prepare("SELECT chat FROM chatbox WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $chat = [];
            if ($row = $result->fetch_assoc()){
                
                // Output data of each row
                    if(!empty($row['chat'])){
                        $chat = json_decode($row['chat'], true);
                        $chat[]= $_POST['messageinput'];
                }
                    else{
                        $chat[]= $_POST['messageinput'];
                    }
            } 
            $chatdb= json_encode($chat);
            $update = $conn->prepare("UPDATE chatbox SET chat=? WHERE id= ?");
            $update->bind_param("si", $chatdb, $id);
            $update->execute();
            header("Location: index.php?id=" . $id);
            exit;
    }          
}
$searchTerm = $_GET['search'] ?? '';
$jsonSearch = json_encode($searchTerm);
if ($searchTerm) {
    $stmt = $conn->prepare(
        "SELECT * FROM discussion 
         WHERE title LIKE ? OR content LIKE ? OR JSON_CONTAINS(categories, ?)
         ORDER BY id DESC"
    );
    $like = "%$searchTerm%";
    $stmt->bind_param("sss", $like, $like, $jsonSearch);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $categories_array = json_decode($row['categories'], true);
            echo "<div id='discussionpost'>". "<h4 style= 'margin:5px;'>". $row['title'] ."</h4>". "<br>" . $row['content'] . "<br><br>" . implode(',', $categories_array) . "<br> <button style='margin-top: 20px'><a style='text-decoration:none;color:black;' href='?id=".$row['id']."'>Discuss</a></button><hr style= 'margin-top:20px;'></div>";
        }
    } else {
        echo "No results found";
    }
} else {
    $sql= "SELECT * FROM discussion ORDER BY id DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $categories_array = json_decode($row['categories'], true);
            echo "<div id='discussionpost'>". "<h4 style= 'margin:5px;'>". $row['title'] ."</h4>". "<br>" . $row['content'] . "<br><br>" . implode(',', $categories_array) . "<br> <button style='margin-top: 20px'><a style='text-decoration:none;color:black;' href='?id=".$row['id']."'>Discuss</a></button><hr style= 'margin-top:20px;'></div>";
        }
    } else {
        echo "0 results";
    }
}


?>

<div id= "pagefordiscussion" style="display: <?= isset($id) ? 'block' : 'none' ?>;">
    <div id= "discussion">
        <button id="closechatbox">close chatbox</button>
        <div id="chatbox">
            <?php
        $stmt = $conn->prepare("SELECT chat FROM chatbox WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()){
            // Output data of each row
                if(!empty($row['chat'])){
                    $messages = json_decode($row['chat'], true);
                    foreach($messages as $message){
                        echo "<p style='margin:10px; padding:5px; background-color:white; color: black;border-radius:10px; width:fit-content;'>".$message."</p>";
                    }
                    }
                } 
        ?>
        </div>
        <form method= "POST">
            <div id= "write">
                <input type= "text" id= "messageinput" name= "messageinput" placeholder="Type your message here..."><button type="submit" id= "sendbutton">Send</button>
            </div>
        </form>
    </div>
</div>
<?php 

$conn->close(); 
?>
<script>
    if(<?php echo isset($id) ? 'true' : 'false'; ?>){
        document.getElementById("pagefordiscussion").style.display= "block";
        var chatbox= document.getElementById("chatbox");
        chatbox.scrollTop = chatbox.scrollHeight;
        document.getElementById("closechatbox").addEventListener("click", function(){
            document.getElementById("pagefordiscussion").style.display= "none";
            // Remove ?id=... from the URL without reloading
                const url = new URL(window.location);
                url.searchParams.delete('id');
                window.history.replaceState({}, document.title, url.pathname);
        });
    }
  

</script>


