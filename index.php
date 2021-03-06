<?php
$title = 'Form';
require_once './includes/header.php';
require_once './db/conn.php';
class validations
{
    function validateDate($date, $format = 'd-m-Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    //verification de date
    function dateFormat($data)
    {
        if ($this->validateDate($data, 'd-m-Y')) {
            //reset date format pour mysql y-m-d
            $date = date("Y-m-d", strtotime($data));
            $today = date("Y-m-d");
            $diff = date_diff(date_create($date), date_create($today));
            //vef d'age
            if ($diff->format('%y') < 18) {
                return ("vous êtes un mineur");
            }
        } else {
            return ("invalid date format");
        }
    }
    function len($data)
    {
        if (strlen($data) < 15)
            return ("un minimum 15 caractères");
        else if (strlen($data) > 250)
            return ("un maximum de 250 caractères");
    }
    function checkEmail($email, $crud)
    {
        $results = $crud->listDB();
        while ($r = $results->fetch(PDO::FETCH_ASSOC)) {
            $mail = $r['email'];
            if (strcmp($mail, $email) === 0) {
                $dateCreation = strtotime($r['updateAt']);
                $dateModified = strtotime(date("Y-m-d H:i:s"));
                //reste de minutes / 3600s /24h + 1 si dates === dates res 0 
                $timePass = ($dateCreation - $dateModified) / 3600 / 24 + 1;
                if ($timePass < 0) {
                    $id = $r['id'];
                    $fname = $_POST['firstName'];
                    $lname = $_POST['lastName'];
                    $sex = $_POST['flexRadioDefault'];
                    //format date for mysql yyyy-mm-dd
                    $birth = date("Y-m-d", strtotime($_POST['dob']));
                    $phone = $_POST['contactNumber'];
                    $country = $_POST['country'];
                    $ip = isset($_SERVER['HTTP_CLIENT_IP'])
                        ? $_SERVER['HTTP_CLIENT_IP']
                        : (isset($_SERVER['HTTP_X_FORWARDED_FOR'])
                            ? $_SERVER['HTTP_X_FORWARDED_FOR']
                            : $_SERVER['REMOTE_ADDR']);;
                    $creatAt = $r['creatAt'];
                    $cnt = intval($r['cnt']) + 1;
                    $text = $r['textArea'];
                    //call crud
                    $isSuccess = $crud->updateDB($id, $fname, $lname, $sex, $email, $birth, $phone, $country, $ip, $creatAt, $cnt, $text);
                    if ($isSuccess)
                        return (1);
                } else
                    return (2);
                break;
            }
        }
        return (0);
    }
}
$regName = "Registration form";
$errorMsg = "";
$errorText = "";
$errorMail = "";
$textArea = "";
if (isset($_POST) && !empty($_POST)) {
    $var = new validations;
    $errorMsg = $var->dateFormat($_POST['dob']);
    $errorText = $var->len($_POST['question']);
    $errorMail = $var->checkEmail($_POST['email'], $crud);
    if (!isset($errorText) && !isset($errorMsg) && $errorMail === 0) {
        //extract post values from array
        $fname = $_POST['firstName'];
        $lname = $_POST['lastName'];
        $sex = $_POST['flexRadioDefault'];
        $email = $_POST['email'];
        //format date for mysql yyyy-mm-dd
        $birth = date("Y-m-d", strtotime($_POST['dob']));
        $phone = $_POST['contactNumber'];
        $country = $_POST['country'];
        $ip = isset($_SERVER['HTTP_CLIENT_IP'])
            ? $_SERVER['HTTP_CLIENT_IP']
            : (isset($_SERVER['HTTP_X_FORWARDED_FOR'])
                ? $_SERVER['HTTP_X_FORWARDED_FOR']
                : $_SERVER['REMOTE_ADDR']);;
        $text = $_POST['question'];
        //call crud
        $isSuccess = $crud->insert($fname, $lname, $sex, $email, $birth, $phone, $country, $ip, $text);
        if ($isSuccess)
            $regName = "Votre demande est validée";
    }
}
/*- ID
- firstname
- lastname
- type

- email
- birth
- phone
- country
- IP
- creatAt
- updateAt
- counter
*/
?>
<h1 class="text-center fw-lighter"><?php echo $regName ?></h1>
<form method="post" action="">
    <div class="form-group">
        <label for="firstName">Prénom</label>
        <input required type="text" class="form-control" id="firstName" name="firstName" oninvalid="this.setCustomValidity('Votre Nom')" onchange="this.setCustomValidity('')">
    </div>
    <div class="form-group">
        <label for="lastName">Nom</label>
        <input required type="text" class="form-control" id="lastName" name="lastName" oninvalid="this.setCustomValidity('Votre Prénom')" onchange="this.setCustomValidity('')">
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="flexRadioDefault" value="f" id="flexRadioDefault1" checked>
        <label class="form-check-label" for="flexRadioDefault1">
            Femme
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="flexRadioDefault" value="h" id="flexRadioDefault2">
        <label class="form-check-label" for="flexRadioDefault2">
            Homme
        </label>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input required type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="example@example.com" oninvalid="this.setCustomValidity('Votre mail : example@example.com')" onchange="this.setCustomValidity('')">
        <?php
        if ($errorMail == 1)
            echo "<span class='text-success'>votre demande a bien été enregistrée</span>";
        else if ($errorMail == 2)
            echo "<span class='text-danger'>Email est déjà enregistré </span>";
        ?>
    </div>
    <div class="form-group">
        <label for="dob">Date of birth</label>
        <input required type="text" class="form-control" id="dob" name="dob" oninvalid="this.setCustomValidity('Votre date de naissance')" onchange="this.setCustomValidity('')">
        <?php
        if (isset($errorMsg))
            echo "<span class='text-danger'> $errorMsg</span>"
        ?>
    </div>
    <div class="form-group">
        <label for="contactNumber">Téléphone</label>
        <input required type="text" class="form-control" id="contactNumber" name="contactNumber" aria-describedby="Persone Number" placeholder="example : 0041789153590" oninvalid="this.setCustomValidity('Votre téléphone : example@example.com')" onchange="this.setCustomValidity('')">
    </div>
    <label for="country">Pays</label>
    <?php require_once('pays.php') ?>
    <br>
    <div class="form-group">
        <label for="Question">Question</label>
        <textarea required class="form-control" name="question" id="exampleFormControlTextarea1" rows="3" oninvalid="this.setCustomValidity('Votre question')" onchange="this.setCustomValidity('')"></textarea>
        <small class="form-text text-muted"> <?php
                                                if (isset($errorText))
                                                    echo "<span class='text-danger'> $errorText</span>";
                                                else
                                                    echo '* Champ obligatoire et un minimum 15 caractères';
                                                ?></small>
    </div>
    <br>
    <br>
    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    <?php

    ?>
</form>
<?php require_once './includes/footer.php' ?>