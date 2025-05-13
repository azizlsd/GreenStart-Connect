<?php if (isset($error)): ?>
  <div class="alert alert-danger" role="alert">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>
<?php $title = "Connexion"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Login | Mantis Bootstrap 5 Admin Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/logoweb.png"
    type="image/x-icon">

  <!-- Fonts and Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet"
    href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/tabler-icons.min.css">
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/feather.css">
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/fontawesome.css">
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/fonts/material.css">

  <!-- CSS -->
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/css/style.css">
  <link rel="stylesheet" href="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/css/style-preset.css">
</head>

<body>
  <!-- Pre-loader -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>

  <div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="auth-header">
          <a href="#"><img src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/logoweb.png"
              alt="img" width="160" height="auto"></a>
        </div>
        <div class="card my-5">
          <div class="card-body">
            <form action="/GreenStart-Connect-main/GreenStartConnect/index.php?action=login" method="POST">
              <div class="d-flex justify-content-between align-items-end mb-4">
                <h3 class="mb-0"><b>Login</b></h3>
                <a href="index.php?action=create" class="link-primary">Don't have an account?</a>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Email Address</label>
                <input type="text" class="form-control" name="email" placeholder="Email Address">
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="mot_de_passe" placeholder="Password">
              </div>
              <div class="d-flex mt-1 justify-content-between">
                <div class="form-check">
                  <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="">
                  <label class="form-check-label text-muted" for="customCheckc1">Keep me sign in</label>
                </div>
                <h5 class="text-secondary f-w-400">Forgot Password?</h5>
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
              <div class="saprator mt-3">
                <span>Login with</span>
              </div>
              <div class="row">
                <div class="col-4">
                  <div class="d-grid">
                    <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                      <img
                        src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/authentication/google.svg"
                        alt="Google">
                      <span class="d-none d-sm-inline-block"> Google</span>
                    </button>
                  </div>
                </div>
                <div class="col-4">
                  <div class="d-grid">
                    <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                      <img
                        src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/authentication/twitter.svg"
                        alt="Twitter">
                      <span class="d-none d-sm-inline-block"> Twitter</span>
                    </button>
                  </div>
                </div>
                <div class="col-4">
                  <div class="d-grid">
                    <button type="button" class="btn mt-2 btn-light-primary bg-light text-muted">
                      <img
                        src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/images/authentication/facebook.svg"
                        alt="Facebook">
                      <span class="d-none d-sm-inline-block"> Facebook</span>
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- JS Scripts -->
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/popper.min.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/simplebar.min.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/bootstrap.min.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/fonts/custom-font.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/pcoded.js"></script>
  <script src="/GreenStart-Connect-main/GreenStartConnect/View/BackOffice/assets/js/plugins/feather.min.js"></script>

  <!-- Layout Scripts -->
  <script>layout_change('light');</script>
  <script>change_box_container('false');</script>
  <script>layout_rtl_change('false');</script>
  <script>preset_change("preset-1");</script>
  <script>font_change("Public-Sans");</script>
  <script>
    document.querySelector('form').addEventListener('submit', function (e) {
      const email = document.getElementById('email').value;
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!regex.test(email)) {
        e.preventDefault(); // Stoppe l'envoi
        alert("❌ Email invalide. Exemple : exemple@mail.com");
      }
    });
  </script>
<?php include __DIR__ . '/../includes/chatbot_widget.php'; ?>

</body>

</html>