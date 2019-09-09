    <form role="form" action="/index.php/login" method="post" class="m-x-auto text-center app-login-form">

      <a href="../index.html" class="app-brand m-b-lg">
        <img src="../assets/img/brand.png" alt="brand">
      </a>
      
      <?php if(isset($_GET["activate"]) && $_GET["activate"] == 1):?>
      <div style="text-align:center;">
        <p style="color:red;">
          アカウントの有効化が完了しました。MytPORTAL WEB版は開発中の為アプリ版をお楽しみください。
        </p>
      </div>
      <?php endif;?>

      <div class="form-group">
        <input class="form-control" name="name" placeholder="Username">
      </div>

      <div class="form-group m-b-md">
        <input type="password" name="password" class="form-control" placeholder="Password">
      </div>

      <div class="m-b-lg">
        <button class="btn btn-primary">Log In</button>
        <button class="btn btn-default">Sign up</button>
      </div>

      <footer class="screen-login">
        <a href="#" class="text-muted">Forgot password</a>
      </footer>
    </form>