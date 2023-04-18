<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $tel = $_POST['tel'];
  $code = $_POST['code'];

  $oldNom = $nom;
  $oldPrenom = $prenom;
  $oldEmail = $email;
  $oldTel = $tel;
  $oldCode = $code;

  // Validate form data
  if (empty($nom) || empty($prenom) || empty($email) || empty($tel) || empty($code)) {
    $error = "Tous les champs sont obligatoires.";
    $oldNom = $nom;
    $oldPrenom = $prenom;
    $oldEmail = $email;
    $oldTel = $tel;
    $oldCode = $code;
  } else {
    // Connect to database
    $servername = "eu-cdbr-west-03.cleardb.net";
    $username = "b302dece02d1a0";
    $password = "90e0f565";
    $dbname = "heroku_0428dd0542836b5";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into database
    $sql = "INSERT INTO client (nom, prenom, email, tel, code) VALUES ('$nom', '$prenom', '$email', '$tel', '$code')";

    if ($conn->query($sql) === TRUE) {
      $success = "Client ajouté avec succès";
      $oldNom = "";
      $oldPrenom = "";
      $oldEmail = "";
      $oldTel = "";
      $oldCode = "";
    } else {
      $error = "Error: ". $conn->error;
    }

    // Close database connection
    $conn->close();
  }
}
else{
    $oldNom = "";
    $oldPrenom = "";
    $oldEmail = "";
    $oldTel = "";
    $oldCode = "";
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="ZXing for JS">

    <title>ASWAK SOUSS | Carte de fidélité</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>

<div class="container my-2">
<?php
        if (isset($error)) {
            echo "<div class='alert alert-danger' role='alert'>
             Error: $error
          </div>";  
            
        }
        if (isset($success)) {
            echo "<div class='alert alert-success' role='alert'>
                    Client ajouté avec succès $success
          </div>";   
        }
        ?>
</div>
   
    <main class="container my-5">
        <div id="client-info row">
            <div class="col-md-12">
                <form action="index.php" method="post" id="client_data">
                    <div class="form-group ">
                        <div >
                            <input type="text" class="form-control form-control-sm " id="nom" name="nom" value="<?php echo htmlspecialchars($oldNom); ?>" placeholder="Nom" required>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div >
                            <input type="text" class="form-control form-control-sm " id="prenom" name="prenom" value="<?php echo htmlspecialchars($oldPrenom); ?>" placeholder="Prénom" required>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div>
                            <input type="text" class="form-control form-control-sm " id="prenom" name="tel" value="<?php echo htmlspecialchars($oldTel); ?>" placeholder="Tel" required>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div>
                            <input type="email" class="form-control form-control-sm " id="email" name="email" value="<?php echo htmlspecialchars($oldEmail); ?>" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div>
                            <input type="text" class="form-control form-control-sm " id="result" name="code" value="<?php echo htmlspecialchars($oldCode); ?>" placeholder="code barre" required>
                        </div>
                    </div>
                </form>
            </div>
            </div>
            <div class="container row">
            <div class="col-md-6">
                    <input type="submit" value="Enregistrer" id="post" class="save-btn btn btn-success text-white my-3 rounded w-75">
            </div>
            <div class="col-md-6">
                        <div class="reader my-3">
                            <div>
                                <video id="video" width="100%" height="40%" title="Barre code Scanner v1"></video>
                            </div>
                            <div class="my-3">
                                <button id="startButton" class="btn btn-success mx-2 w-40">Start scanner</button>
                                <button id="resetButton" class="btn btn-warning mx-2 w-40">Reset scanner</button>
                            </div>
                           
                        </div>
            </div>
            </div>
    </main>
    <div class="modal fade" id="barcodeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Scanner</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <!-- Barcode data will be inserted here -->
        </div>
        </div>
    </div>
    </div>

 

    <audio id="validation-sound">
		<source src="/sounds/beep.wav" type="audio/mp3" hidden>
	</audio>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>

  
    <script type="text/javascript" src="/js/scaner.min.js"></script>
    <script type="text/javascript">
        window.addEventListener('load', function() {
            let selectedDeviceId;
            const codeReader = new ZXing.BrowserMultiFormatReader()
            console.log('ZXing code reader initialized')
            codeReader.listVideoInputDevices()
                .then((videoInputDevices) => {
                   
                    document.getElementById('startButton').addEventListener('click', () => {
                        codeReader.decodeFromVideoDevice(undefined, 'video', (result, err) => {
                            if (result) {
                                const validationSound = document.getElementById("validation-sound");
                                validationSound.play();
                                validationSound.addEventListener("ended", function() {
                                    this.currentTime = 0;
                                });
                               
                                
                                 // Send an AJAX request to the server
                                    const xhr = new XMLHttpRequest();
                                    xhr.open("POST", "check-code.php", true);
                                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                    xhr.onreadystatechange = function() {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                        // Parse the response from the server
                                        const response = JSON.parse(xhr.responseText);

                                        // Check if the code exists in the client table
                                        if (response.exists) {
                                            $('#barcodeModal .modal-body').text("Le code a déjà été attribué à un autre client.");
                                            $('#barcodeModal').modal('show');
                                        } else {
                                            document.getElementById('result').value = "";
                                            document.getElementById('result').value = result.text
                                            $('#barcodeModal .modal-body').text("Sanned : "+result.text);
                                            $('#barcodeModal').modal('show');
                                        }
                                        }
                                    };
                                    xhr.send("code=" + encodeURIComponent(result.text));

                               
                            }
                            if (err && !(err instanceof ZXing.NotFoundException)) {
                                console.error(err)
                                document.getElementById('result').textContent = err
                            }
                        })
                    //    console.log(`Started continous decode from camera with id ${selectedDeviceId}`)
                        console.log("Started continous decode from camera")

                    })

                    document.getElementById('resetButton').addEventListener('click', () => {
                        codeReader.reset()
                        document.getElementById('result').textContent = '';
                        console.log('Reset.')
                    })

                })
                .catch((err) => {
                    console.error(err)
                })
        })

        document.getElementById("post").addEventListener("click", function() {
        document.getElementById("client_data").submit();
        });
    </script>

</body>

</html>