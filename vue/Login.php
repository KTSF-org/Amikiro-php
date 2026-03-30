<?php
// VUE LOGIN
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>


<div class="d-flex justify-content-center align-items-center py-5">
    <div style="width: 100%; max-width: 400px;">
        <div class="border border-dark rounded p-4 bg-white shadow-sm">
            <h4 class="text-center mb-4">Connexion</h4>
            <form method="POST" action="/login">

                <div class="mb-3">
                    <label for="pseudo" class="form-label">Login</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="azerty@gmail.com">
                </div>

                <div class="mb-3">
                    <label for="pwd" class="form-label">Mot de passe</label>
                    <input type="password" name="pwd" id="pwd" class="form-control" placeholder="Votre mot de passe">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Connexion</button>
                </div>

            </form>
        </div>
    </div>
</div>


</body>

</html>