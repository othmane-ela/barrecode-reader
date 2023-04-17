<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $tel = $_POST['tel'];
  $code = $_POST['code'];

  // Validate form data
  if (empty($nom) || empty($prenom) || empty($email) || empty($tel) || empty($code)) {
    $error = "All fields are required";
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
      $success = "Client added successfully";
    } else {
      $error = "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
  }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="ZXing for JS">

    <title>ASWAK SOUSS | Carte de fidélité</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
        <section id="client-info row">
            <form action="index.php" method="post" id="client_data">
                <div class="form-group row">
                    <label for="nom" class="col-sm-2 col-form-label col-form-label-sm">1 introduire nom</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm w-75 mx-auto" id="nom" name="nom" placeholder="Nom" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="prenom" class="col-sm-2 col-form-label col-form-label-sm">2 introduire prénom</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm w-75 mx-auto" id="prenom" name="prenom" placeholder="Prénom" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tel" class="col-sm-2 col-form-label col-form-label-sm">3 introduire tel</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm w-75 mx-auto" id="prenom" name="tel" placeholder="Tel" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label col-form-label-sm">4 introduire email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control form-control-sm w-75 mx-auto" id="email" name="email" placeholder="Email" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="codeCarte" class="col-sm-2 col-form-label col-form-label-sm">6 scanner une carte de fidélité</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm w-75 mx-auto" id="result" name="code" placeholder="code barre" required>
                    </div>
                </div>
            </form>

            <div id="save" class="col-md-12 ">
                    <div class="w-100">
                        <input type="submit" value="Ajouter" id="post" class="save-btn btn btn-success text-white my-3 rounded">
                    </div>
            </div>

            <div class="col-md-12">
                <div class="reader w-50 ml-auto">
                    <div>
                        <video id="video" width="100%" height="40%"></video>
                    </div>
                    <div id="sourceSelectPanel" style="display:none" class="my-2">
                        <label for="sourceSelect">Change video source:</label>
                        <select id="sourceSelect" class="custom-select">
                        </select>
                    </div>
                    <div>
                        <button id="startButton" class="btn btn-success">Start</button>
                        <button id="resetButton" class="btn btn-warning">Reset</button>
                    </div>

                </div>
            </div>

        </section>
    </main>
    <div class="description w-90 mx-5">
        <p>Nous avons des carte de fidélité de numéro 1 à 10000<br> pour chaque client on lui demande ses infos (nom prenom etc)<br> on prend une carte on la scane et on attribue son numero au client<br> et on engregistre le tout dans une base de donnée
        </p>
    </div>

    <audio id="validation-sound">
		<source src="/sounds/beep.wav" type="audio/mp3" hidden>
	</audio>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/scaner.min.js"></script>
    <script type="text/javascript">
        window.addEventListener('load', function() {
            let selectedDeviceId;
            const codeReader = new ZXing.BrowserMultiFormatReader()
            console.log('ZXing code reader initialized')
            codeReader.listVideoInputDevices()
                .then((videoInputDevices) => {
                    const sourceSelect = document.getElementById('sourceSelect')
                    selectedDeviceId = videoInputDevices[0].deviceId
                    if (videoInputDevices.length >= 1) {
                        videoInputDevices.forEach((element) => {
                            const sourceOption = document.createElement('option')
                            sourceOption.text = element.label
                            sourceOption.value = element.deviceId
                            sourceSelect.appendChild(sourceOption)
                        })

                        sourceSelect.onchange = () => {
                            selectedDeviceId = sourceSelect.value;
                        };

                        const sourceSelectPanel = document.getElementById('sourceSelectPanel')
                        sourceSelectPanel.style.display = 'block'
                    }

                    document.getElementById('startButton').addEventListener('click', () => {
                        codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
                            if (result) {
                                const validationSound = document.getElementById("validation-sound");
                                validationSound.play();
                                validationSound.addEventListener("ended", function() {
                                    this.currentTime = 0;
                                });
                                alert("Scanned : " + result)
                                document.getElementById('result').value = "";
                                document.getElementById('result').value = result.text
                               
                            }
                            if (err && !(err instanceof ZXing.NotFoundException)) {
                                console.error(err)
                                document.getElementById('result').textContent = err
                            }
                        })
                        console.log(`Started continous decode from camera with id ${selectedDeviceId}`)
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