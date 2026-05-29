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

  window.onload = function () {
    if (localStorage.getItem("rgpdBannerSeen") === "true") {
      document.getElementById("rgpd-banner").classList.add("hidden");
    }
  }
</script>

    </main><!-- /.page-body — fermé ici, ouvert dans header.php après </nav> -->

<footer class="footer bg-dark text-light">
  <!--
    Plus de jQuery padding-bottom ici — le sticky footer est géré
    par body { display:flex; flex-direction:column } + main.page-body { flex:1 }
    dans main.css. Aucun JavaScript nécessaire.
  -->
  <div class="container text-center">
    &copy;<?= date('Y') . ' ' . APP_NAME ?>
    | <a href="confidentialite" target="_blank">Politique de confidentialité</a>
    | <a href="mentionslegales" target="_blank">Mentions légales</a>
    | <a href="credits" target="_blank">Crédits</a>
  </div>
</footer>

<div id="rgpd-banner" class="rgpd-container">
  <p class="mb-0">Ce site respecte votre vie privée : nous ne collectons aucun cookie.</p>
  <div class="rgpd-buttons">
    <a href="confidentialite" class="btn-link">En savoir plus</a>
    <button onclick="closeRGPD()" class="btn btn-primary btn-sm" type="button">Fermer</button>
  </div>
</div>

</body>

</html>
