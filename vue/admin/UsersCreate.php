<?php /** VUE : Admin / Create account */ ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Créer un compte</h1>
                <a href="<?= $actual_link ?>parametres/utilisateurs" class="btn btn-outline-secondary">← Retour</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= $actual_link ?>parametres/utilisateurs?page=create">

                <div class="mb-3">
                    <label for="name" class="form-label">Prénom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="surname" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="surname" name="surname" required>
                </div>

                <div class="mb-3">
                    <label for="mail" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="mail" name="mail" required>
                </div>

                <div class="mb-3">
                    <label for="codeRole" class="form-label">Rôle</label>
                    <select class="form-select" id="codeRole" name="codeRole">
                        <option value="<?= ROLE_INVITE ?>">Invité</option>
                        <option value="<?= ROLE_ADHERENT ?>">Adhérent</option>
                        <option value="<?= ROLE_NATURALISTE ?>">Naturaliste</option>
                    </select>
                </div>

                <div id="accessDates" class="mb-4 p-3 border rounded" style="display:none;">
                    <p class="fw-semibold mb-2">Période d'accès <span class="text-muted fw-normal">(optionnel)</span></p>
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="startDate" class="form-label">Début</label>
                            <input type="date" class="form-control" id="startDate" name="startDate">
                        </div>
                        <div class="col-6">
                            <label for="endDate" class="form-label">Fin</label>
                            <input type="date" class="form-control" id="endDate" name="endDate">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Créer le compte</button>

            </form>

            <script>
                (function () {
                    const select = document.getElementById('codeRole');
                    const block  = document.getElementById('accessDates');
                    function toggle() {
                        block.style.display = (select.value === '<?= ROLE_ADHERENT ?>') ? '' : 'none';
                    }
                    select.addEventListener('change', toggle);
                    toggle();
                })();
            </script>

        </div>
    </div>
</div>
