<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <style>
        body{
            font-family: Georgia;
        }
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
        h1{
            text-align: center;
        }
        #discussionformdiv{
            text-align: center;
            margin-top: 30px;
        }
        form{
            text-align: center;
            background-color: rgba(128, 128, 128, 0.3);
            display: inline-block;
            padding: 20px;
            border-radius: 10px;
        }
        label{
            display: block;
            margin-bottom: 5px;
        }
        #anothercategory{
            display: inline-block;
            font-size: 24px;
            cursor: pointer;
            margin-right: 10px;
            background-color: rgba(128, 128, 128, 0.3);
            border-radius: 50%;
            padding: 5px 10px;
        }
        #submit{
            padding: 10px;
            background-color: #0965ba;
            border-radius: 10px;
            border: none;
            color: white;
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
<script>
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');

    hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    });
</script>
<body>
<h1> Create a discussion </h1>
<div id="discussionformdiv">
<form action="savediscussion.php" method="POST">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" style="border-radius: 10px;" required><br><br>
    <label for="content">Content:</label><br>
    <textarea id="content" name="content" rows="10" cols="50" style="border-radius: 10px;" required></textarea><br><br>
    <label for="categories">Add Categories:</label><br>
    <select id="categories" name="categories">
        <option value="Apple">Apple</option>
        <option value="Banana">Banana</option>
        <option value="Cherry">Cherry</option>
        <option value="Mango">Mango</option>
    </select>
    <br>
    <p id= "anothercategory"> + </p><p style="margin: -10px;">(keep clicking + to add more categories)</p> 
    <br>
    <div id= "plusdiv" ></div>
    <input style= "margin-top: 10px; border-radius: 10px;text-align:center;" type="text" id="newcategory" name="newcategory" placeholder="Add new category">
    <br><br>
    <input id="submit" type="submit" value="Submit">
</form>
</div>
<script>
    const addcategory = document.getElementById("anothercategory");
    const br= document.getElementById("plusdiv");
    i= 1;
    addcategory.onclick= function(){
        const select = document.createElement("select");
        select.style= "margin: 10px;"
        plusdiv.insertAdjacentElement("beforeend", select);
        select.appendChild(document.createElement("option")).text = "Apple";
        select.appendChild(document.createElement("option")).text = "Banana";
        select.appendChild(document.createElement("option")).text = "Cherry";
        select.appendChild(document.createElement("option")).text = "Mango";
        select.insertAdjacentElement("afterend", document.createElement("br"));
        select.name = "categories" + [i];
        i +=1;
    }
</script>
</body>
</html>
