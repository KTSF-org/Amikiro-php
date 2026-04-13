<?php
/**
 * VUE : COMMON : footer.php
 */
?>
<script defer>
  function closeRGPD() {
    document.getElementById("rgpd-banner").classList.add("hidden");
    localStorage.setItem("rgpdBannerSeen", "true");
  }

  // verifier si bandeau déjà vu par l'utilisateur
  window.onload = function () {
    if (localStorage.getItem("rgpdBannerSeen") === "true") {
      document.getElementById("rgpd-banner").classList.add("hidden");
    }
  }
</script>
<footer class="footer bg-dark text-light">
  <script>$(document).ready(function () {
      $('body').css('padding-bottom', $('footer').height() + 'px');
    });</script>
  <div class="container text-center">
    &copy;<?= date('Y') . ' ' . APP_NAME ?> | <a href="confidentialite" target="_blank" style="color:#FFF"> Politique de
      confidentialité </a> | <a href="mentionslegales" target="_blank" style="color:#FFF"> Mentions légales</a>
  </div>
</footer>
<div id="rgpd-banner" class="rgpd-container">
  <p>Ce site respecte votre vie privée : nous ne collectons aucun cookie.</p>
  <div class="rgpd-buttons">
    <a href="confidentialite" class="btn-link">En savoir plus</a>
    <button onclick="closeRGPD()" class="btn btn-primary" type="button">Fermer</button>
  </div>
</div>

</body>

</html>