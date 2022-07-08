<html>

<head>
    <title>E-Corp - Transfer response</title>
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
                            <a class="nav-link active" aria-current="page" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="bankTransfers.php">Payment History</a>
                        </li>

                    </ul>
                </div>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                        Welcome <b>Elliot</b>
                        </li>
                    </ul>
            </div>
        </nav>
    </div>
    <div class="container mt-5 text-center">
        <?php


        //Take parameters from post
        $recipient = $_POST['recipient'];
        $amount = $_POST['amount'];
        $causal = $_POST['causal'];
        $server = "database";
        $username = "user";
        $password = "password";
        $db_name = "db";


        // Create connection
        $conn = new mysqli($server, $username, $password, $db_name);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // create table if not exist
        $sql = "CREATE TABLE IF NOT EXISTS bank_transfers(
        id int AUTO_INCREMENT PRIMARY KEY,
        sender varchar(20),
        receiver varchar(20),
        amount int,
        causal text);";
        if ($conn->query($sql) === TRUE) {
            // echo "Table bank_transfers created successfully";
        } else {
            echo "Error creating table: " . $conn->error;
        }

        //sanitaze parameters to prevent xss
        // $recipient = htmlspecialchars($recipient);
        // $amount = htmlspecialchars($amount);
        // $causal = htmlspecialchars($causal);

        //This parameter in the real world has to be get from database
        $from = get_sender_from_coockie();


        // insert data into db
        $sql = "INSERT INTO bank_transfers(sender, receiver, amount, causal) VALUES(
        '$from',
        '$recipient', 
        $amount,
        '$causal');";
        // echo $sql;
        if ($conn->query($sql) === FALSE) {
            echo "Error executing query: " . $conn->error;
        }else{

            $curl = curl_init();

            $request_url = "http://backend:8081?recipient=$recipient&from=$from&amount=$amount&causal=$causal";

            //for testing purpouses
            // echo $request_url;

            curl_setopt_array($curl, array(
                CURLOPT_URL => $request_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);


        ?>
        <p class="display-6"><?php echo ($response); ?></p>
        <?php } ?>
        <a href="/">Go back home</a>
    </div>
</body>
                               <?php
        function get_sender_from_coockie()
        {
            return "Elliot";
        }
                               ?>

</html>
