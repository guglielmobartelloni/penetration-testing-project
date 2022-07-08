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

    <div class="container text-center">
        <h1>Make a transfer</h1>
    </div>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-6">
                <form action="createTransfer.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Recipient</label>
                        <input type="text" name="recipient" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Causal</label>
                        <input type="text" name="causal" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
