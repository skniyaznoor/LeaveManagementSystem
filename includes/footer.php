<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <style>
      .footer-container {
        background-color: #112954;
        height: 150px;
        width: 100%;
        padding: 20px;
        display: flex;
        justify-content: space-between;
      }

      .logo img {
        width: 200px;
        margin: 0 0 0 16px;
      }

      .footer-end {
        background-color: #112954;
        color: white;
        text-align: center;
      }

      .footer-end p {
        padding-bottom: 7px;
        padding-top: 7px;
      }

      .footer-end .para-mid {
        font-size: 12px;
        padding: 5px;
      }

      .footer-end .para-end {
        font-size: 14px;
        padding-bottom: 20px;
      }

      .logo-image img {
        width: 30px;
      }

      .logo-image {
        padding: 8px;
      }

      .logo,
      .logo-image a {
        text-decoration: none;
      }

      .footer-address h4 {
        color: white;
        padding: 5px;
      }
    </style>
  </head>
  <body>
    <div class="footer-container">
    <?php
    $currentDir = basename(dirname($_SERVER['SCRIPT_NAME']));
    $base_path = ($currentDir === 'templates' || $currentDir === 'admin' || $currentDir === 'user' || $currentDir === 'calender' || $currentDir === 'update') 
        ? '../' 
        : './';
    ?>
    <div class="logo">
        <a href="https://hyscaler.com/" target="_blank">
          <img src="<?= $base_path ?>img/hyscaler-logo.svg" alt="logo" />
        </a>
        <div class="logo-image">
          <a href="https://www.instagram.com/skniyazoor" target="_blank">
            <img src="<?= $base_path ?>img/instagram1.png" alt="Instagram" />
          </a>
          <a href="https://www.linkedin.com/in/skniyaznoor" target="_blank">
            <img src="<?= $base_path ?>img/linkedin1.png" alt="LinkedIn" />
          </a>
          <a
            href="https://www.skniyaznoorpoetryandlovestories.com/"
            target="_blank"
          >
            <img src="<?= $base_path ?>img/blogger1.png" alt="Blogger" />
          </a>
        </div>
      </div>

      <div class="footer-address">
        <h4>+1-408-658-0677</h4>
        <h4>info@hyscaler.com</h4>
        <h4>sales@hyscaler.com</h4>
        <h4>support@hyscaler.com</h4>
      </div>
    </div>
    <hr />
    <div class="footer-end">
      <p>© 2024 NetTantra Technologies. All rights reserved.</p>
      <p class="para-mid">
        The name “HyScaler” and its associated logo are registered trademarks of
        NetTantra Technologies (India) Private Limited, denoted with the ®
        symbol. Unauthorized use, replication, or imitation without explicit
        consent is strictly prohibited. These marks, bearing the ® symbol,
        signify our commitment to quality and uniqueness. We actively monitor
        their use and will address infringements as necessary. We appreciate
        your respect for our intellectual property.
      </p>
      <p class="para-end">Visit old website at NetTantra.com</p>
    </div>
  </body>
</html>
