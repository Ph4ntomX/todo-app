<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database_name = "todoapp";

    $database = new PDO("mysql:host=$host;dbname=$database_name", $username, $password);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["add_label"]) && !empty($_POST["add_label"])) {
            $label = $_POST["add_label"];
            $recipe = "INSERT INTO todos (label) VALUES (:label)";
            $statement = $database->prepare($recipe);
            $statement->execute([
                "label" => $label
            ]);
        }

        if (isset($_POST["complete_id"]) && isset($_POST["completed"])) {
            $complete_id = $_POST["complete_id"];
            $completed = $_POST["completed"];

            if ($completed == '0') {
                $completed = 1;
            } else {
                $completed = 0;
            }

            $recipe = "UPDATE todos SET completed = :completed WHERE id = :id";
            $statement = $database->prepare($recipe);
            $statement->execute([
                "id" => $complete_id,
                "completed" => $completed
            ]);
        }

        if (isset($_POST["delete_id"])) {
            $delete_id = $_POST["delete_id"];
            $recipe = "DELETE FROM todos WHERE id = :id";
            $statement = $database->prepare($recipe);
            $statement->execute([
                "id" => $delete_id
            ]);
        }

        header("Location: index.php");
    }
?>
<html>

<head>
    <title>TODO App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
    <style type="text/css">
        body {
            background: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="card rounded shadow-sm" style="max-width: 500px; margin: 60px auto;">
        <div class="card-body">
            <h3 class="card-title mb-3">My Todo List</h3>
            <ul class="list-group">
                <?php
                    $recipe = "SELECT * FROM todos";
                    $statement = $database->query($recipe);
                    $rows = $statement->fetchAll();
                ?>
                
                <?php foreach ($rows as $row) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <form class="mb-0" action="" method="post">
                            <input type="hidden" name="complete_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="completed" value="<?= $row['completed'] ?>">
                            <?php if ($row['completed'] == 0) : ?>
                                <button type="submit" class="btn py-2 btn-sm btn-light">
                                    <i class="bi bi-square"></i>
                                </button>

                                <span class="ms-2">
                                    <?= $row['label'] ?>
                                </span>
                            <?php else : ?>
                                <button type="submit" class="btn py-2 btn-sm btn-success">
                                    <i class="bi bi-check-square"></i>
                                </button>

                                <span class="ms-2 text-decoration-line-through">
                                    <?= $row['label'] ?>
                                </span>
                            <?php endif ?>
                        </form>
                    </div>

                    <form class="mb-0" action="" method="post">
                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn py-2 btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </li>
                <?php endforeach ?>
            </ul>

            <div class="mt-4">
                <form action="" method="post" class="d-flex justify-content-between align-items-center">
                    <input type="text" name="add_label" class="form-control" placeholder="Add new item..." required />
                    <button type="submit" class="btn py-2 btn-primary btn-sm rounded ms-2">Add</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>