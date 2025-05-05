<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database_name = "todoapp";

    $database = new PDO("mysql:host=$host;dbname=$database_name", $username, $password);

    session_start();
    if(isset($_SESSION["user"])) {
        header("Location: index.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            echo("<p>All fields are required</p>");
            echo "<a href='signup.php'>Go back</a>";
            exit();
        }

        $recipe = "SELECT * FROM users WHERE email = :email";
        $statement = $database->prepare($recipe);
        $statement->execute([
            "email" => $email
        ]);
        $user = $statement->fetch();

        if ($user) {
            echo("<p>Email is already used</p>");
            echo "<a href='signup.php'>Go back</a>";
        } else if ($password != $confirm_password) {
            echo("<p>Passwords do not match</p>");
            echo "<a href='signup.php'>Go back</a>";
        } else {
            $recipe = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $statement = $database->prepare($recipe);
            $statement->execute([
                "name" => $name,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT)
            ]);
            header("Location: login.php");
        }
    }
?>