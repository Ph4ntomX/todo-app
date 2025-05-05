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
        $email = $_POST["email"];
        $password = $_POST["password"];
        $recipe = "SELECT * FROM users WHERE email = :email";
        $statement = $database->prepare($recipe);
        $statement->execute([
            "email" => $email
        ]);
        $user = $statement->fetch();

        if ($user) {
            if (password_verify($password, $user["password"])) {
                session_start();
                $_SESSION["user"] = $user;
                header("Location: index.php");
            } else {
                echo("Invalid password");
                echo "<a href='login.php'>Go back</a>";
            }
        } else {
            echo("User not found");
            echo "<a href='login.php'>Go back</a>";
        }
    }
?>