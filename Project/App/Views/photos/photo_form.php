<?php 
$title = "Photos"
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photos</title>
  <link rel="stylesheet" href="/path/to/main.css">

</head>
<body>
    <div class="container flex">
        <button id="open-modal" type="submit" class="form-button">
        Partager une photo
        </button>
        <!-- Modal -->
        <div class="modal-overlay" id="modal-overlay">
            <div class="modal">
                <button class="close-button" id="close-button">&times;</button>
                <h2 class="modal-title">Modal Photos</h2>
                <body class="form-page">
                    <div class="form-container">
                    <div class="form-card">
                        <h1 class="form-title">Photo</h1>
                        <form method="POST" action="/insert" class="form">
                        <div class="input-group">
                            <input
                            type="email"
                            name="email"
                            placeholder="Username"
                            class="input-field"
                            />
                        </div>
                        <div class="input-group">
                            <input
                            type="password"
                            name="password"
                            placeholder="Password"
                            class="input-field"
                            />
                        </div>
                        <div class="options">
                            <label class="checkbox">
                            <input type="checkbox" name="remember_me" />
                            <span>Remember me</span>
                            </label>
                            <a href="/forgot-password" class="forgot-password"
                            >Forgot password</a
                            >
                        </div>
                        <button type="submit" class="form-button">Login</button>
                        <a href="/create-account" class="create-account">Create Account</a>
                        </form>
                    </div>
                    </div>
                </body>
            </div>
        </div>
    </div>

    <!-- Script JavaScript directement inclus -->
    <script>
      // Ouvrir la modal
      document.getElementById("open-modal").addEventListener("click", () => {
        document.getElementById("modal-overlay").classList.add("active");
      });

      // Fermer la modal
      document.getElementById("close-button").addEventListener("click", () => {
        document.getElementById("modal-overlay").classList.remove("active");
      });

      // Optionnel : Fermer la modal en cliquant sur l'overlay
      document
        .getElementById("modal-overlay")
        .addEventListener("click", (e) => {
          if (e.target === document.getElementById("modal-overlay")) {
            document.getElementById("modal-overlay").classList.remove("active");
          }
        });
    </script>
</body>
</html>