<html>

<head>
    <title>E-Corp - Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">E-Corp</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Assistance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Payment History</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container text-center">
        <h1>Make a transfer</h1>
    </div>
    <div class="container mt-5">
        <table class="table table-borderless table-dark">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Sender</th>
                    <th scope="col">Reciever</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Causal</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $server = "localhost";
                $username = "user";
                $password = "password";
                $db_name = "db";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // selecting data from db
                $sql = "SELECT * FROM bank_transfers;";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                ?>

                        <tr>
                            <th scope="row"><?= $row['id'] ?></th>
                            <th><?= $row['sender'] ?></th>
                            <th><?= $row['receiver'] ?></th>
                            <th><?= $row['amount'] ?></th>
                            <th><?= $row['causal'] ?></th>
                        </tr>
                <?php
                    }
                } else {
                    echo "0 results";
                }

                ?>
            </tbody>
        </table>
    </div>
</body>

</html>