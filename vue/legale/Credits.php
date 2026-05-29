<?php

/**
 * VUE : Credits.php
 */

?>
<style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #ffffff;
      color: #000000;
    }

    /* ── Conteneur principal ── */
    .credits-container {
      max-width: 720px;
      margin: 0 auto;
      padding: 3rem 1.5rem;
    }

    /* ── En-tête ── */
    .credits-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .credits-header h1 {
      font-size: 32px;
      font-weight: 700;
      color: #bb0808;
      letter-spacing: 0.5px;
      margin-bottom: 0.75rem;
    }

    .credits-divider {
      width: 48px;
      height: 3px;
      background-color: #bb0808;
      margin: 0 auto 1rem;
      border-radius: 2px;
    }

    .credits-subtitle {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 2.5px;
      text-transform: uppercase;
      color: #3c5060;
    }

    /* ── Grille de cartes ── */
    .credits-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 14px;
      margin-bottom: 2.5rem;
    }

    /* ── Carte membre ── */
    .credit-card {
      background-color: #edecec;
      border-radius: 12px;
      padding: 1.5rem 1.25rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 10px;
    }

    /* ── Avatar initiales ── */
    .credit-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
      font-weight: 700;
      color: #edecec;
      flex-shrink: 0;
    }

    /* ── Nom ── */
    .credit-name {
      font-size: 15px;
      font-weight: 700;
      color: #000000;
    }

    /* ── Rôle ── */
    .credit-role {
      font-size: 12px;
      font-weight: 500;
      color: #3c5060;
    }

    /* ── Badge Lead ── */
    .credit-badge {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      background-color: #bb0808;
      color: #edecec;
      border-radius: 4px;
      padding: 3px 10px;
    }

    /* ── Pied de page ── */
    .credits-footer {
      text-align: center;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(60, 80, 96, 0.15);
    }

    .credits-footer p {
      font-size: 12px;
      font-weight: 500;
      color: #3c5060;
      margin-bottom: 10px;
    }

    .credits-version {
      display: inline-block;
      font-size: 11px;
      font-weight: 700;
      color: #edecec;
      background-color: #3c5060;
      border-radius: 20px;
      padding: 4px 16px;
    }
  </style>
</head>
<body>

  <div class="credits-container">

    <!-- En-tête -->
    <header class="credits-header">
      <h1>Crédits</h1>
      <div class="credits-divider"></div>
      <p class="credits-subtitle">L'équipe derrière l'application</p>
    </header>

    <!-- Grille des membres -->
    <div class="credits-grid">

      <!-- Membre 1 — Lead -->
      <div class="credit-card">
        <div class="credit-avatar" style="background-color: #bb0808;">FE</div>
        <p class="credit-name">Florian Édouard</p>
        <p class="credit-role">Développeur</p>
		<p>florian.edouard91@gmail.com</p>
      </div>

      <!-- Membre 2 -->
      <div class="credit-card">
        <div class="credit-avatar" style="background-color: #3c5060;">SM</div>
        <p class="credit-name">Simon Mahéo</p>
        <p class="credit-role">Développeur</p>
		<p>simon.maheo@free.fr</p>
      </div>

      <!-- Membre 3 -->
      <div class="credit-card">
        <div class="credit-avatar" style="background-color: #ce2b37;">TO</div>
        <p class="credit-name">Tanihiarii Opuu</p>
        <p class="credit-role">Développeur</p>
		<p>tanihiariidev@gmail.com</p>
      </div>

      <!-- Membre 4 -->
      <div class="credit-card">
        <div class="credit-avatar" style="background-color: #000000;">KP</div>
        <p class="credit-name">Kévin Pinel</p>
        <p class="credit-role">Développeur</p>
		<p>pinelkevin0@gmail.com</p>
      </div>

    </div>

    <!-- Pied de page -->
    <footer class="credits-footer">
      <p>Réalisé avec passion par toute l'équipe</p>
      <span class="credits-version">v1.0.0 — 2026</span>
    </footer>

  </div>
