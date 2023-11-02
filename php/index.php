<?php
// Connexion à la base de données
$serveur = "monsql";
$MYSQL_USER = "test";
$MYSQL_PASSWORD = "pass";
$MYSQL_DATABASE = "sophrologie";

$connexion = new mysqli($serveur, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE);

// Vérifier la connexion à la base de données
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}

// Fonction pour ajouter un patient
function ajouterPatient($nom, $prenom, $age, $date_naissance, $symptomes) {
    global $connexion;
    $stmt = $connexion->prepare("INSERT INTO patients (nom, prenom, age, date_naissance, symptomes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $nom, $prenom, $age, $date_naissance, $symptomes);
    $stmt->execute();
    
}

// Fonction pour ajouter une séance pour un patient
function ajouterSeance($patient_id, $date, $notes) {
    global $connexion;
    $stmt = $connexion->prepare("INSERT INTO seances (patient_id, date, notes) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $patient_id, $date, $notes);
    $stmt->execute();
    $stmt->close();
    
}

// Fonction pour afficher les informations d'un patient
function afficherInformationsPatient($patient_id) {
    global $connexion;
    $stmt = $connexion->prepare("SELECT id, nom, prenom, age, date_naissance, symptomes FROM patients WHERE id=?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $stmt->bind_result($id, $nom, $prenom, $age, $date_naissance, $symptomes);
    $result = $stmt->get_result();
    ?>
    <br>
    <p><strong align-center>Informations du patient :</strong></p>
    
    <table>
        <tr></tr>
        <tr>
            <th>N°</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Age</th>
            <th>Date de naissance</th>
            <th>Symptômes</th>
        </tr>
        
        <tr>
            <?php
            while ($row = $result->fetch_assoc()) {
            
           ?>
            <td> <?php echo $key = $row['id']; ?></td>
            <td> <?php echo $key = $row['nom']; ?></td>
            <td> <?php echo $key = $row['prenom']; ?></td>
            <td> <?php echo $key = $row['age']; ?></td>
            <td> <?php echo $key = $row['date_naissance']; ?></td>
            <td> <?php echo $key = $row['symptomes']; ?></td>
        
        </tr>   
        <?php
}
    
    
?>
    </table>

<?php
    // Afficher l'historique des séances
    global $connexion;
    $stmt = $connexion->prepare("SELECT date, notes FROM seances WHERE patient_id=?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
     ?>
     
     <p><strong>Historique des séances :</strong></p>
     <table>
        <tr>
            <th>Date séance</th>
            <th>Notes</th>
            
        </tr>
        <tr>
            <?php
            while ($row = $result->fetch_assoc()) {
            
           ?>
        
            <td> <?php echo $key = $row['date']; ?></td>
            <td> <?php echo $key = $row['notes']; ?></td>
            </tr>
            <?php
    }
}
?>
    </table>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Gestion des patients</title>
</head>
<body>
    <h1 align="center">  Gestion des patients</h1>
    <h2 align="center">Ajouter un nouveau patient</h2>
    <br>
    <form align="center" class="formulaire" action="" method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom"><br>
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom"><br>
        <label for="age">Âge :</label>
        <input type="number" name="age"><br>
        <label for="date_naissance">Date de naissance :</label>
        <input type="date" name="date_naissance"><br>
        <label for="symptomes">Symptômes :</label>
        <input type="text" name="symptomes"><br>
        <input type="submit" name="ajouter_patient" value="Ajouter">
    </form>
    
    
    <br>
    <h2 align="center">Ajouter une séance pour un patient existant</h2>
    <br>
    <form align="center" width="100" class="formulaire" action="" method="post">
        ID du Patient : <input align="center" width="100" type="number" name="patient_id"><br>
        Date : <input align="center" width="100" type="date" name="date"><br>
        Notes : <input align="center" width="100" type="text" name="notes"><br>
        <input align="center" type="submit" name="ajouter_seance" value="Ajouter">
    </form>
    <br>
    <h2 align="center">Afficher les informations d'un patient</h2>
    <br>
    <form align="center" class="formulaire" action="" method="post">
        Patient ID : <input type="number" name="patient_id_afficher"><br>
        <input type="submit" name="afficher_informations" value="Afficher">
    </form>

    <?php
    if (isset($_POST['ajouter_patient'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $age = $_POST['age'];
        $date_naissance = $_POST['date_naissance'];
        $symptomes = $_POST['symptomes'];
        ajouterPatient($nom, $prenom, $age, $date_naissance, $symptomes);
        echo "Patient ajouté avec succès!";
    }

    if (isset($_POST['ajouter_seance'])) {
        $patient_id = $_POST['patient_id'];
        $date = $_POST['date'];
        $notes = $_POST['notes'];
        ajouterSeance($patient_id, $date, $notes);
        echo "Séance ajoutée avec succès!";
    }

    if (isset($_POST['afficher_informations'])) {
        $patient_id_afficher = $_POST['patient_id_afficher'];
        afficherInformationsPatient($patient_id_afficher);
    }
    ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>
