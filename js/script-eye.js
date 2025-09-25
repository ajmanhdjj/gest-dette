document.addEventListener("DOMContentLoaded", function() {
        // Sélectionnez les éléments du DOM nécessaires
        var soldeElement = document.getElementById('soldeValue');
        var maskedSoldeElement = document.getElementById('maskedSolde');
        var toggleButton = document.getElementById('toggleButton');
        var eyeIcon = toggleButton.querySelector('i');
        var isSoldeVisible = true;

        // Fonction pour basculer entre l'affichage et la masquage du solde
        function toggleSoldeVisibility() {
            if (isSoldeVisible) {
                // Masquer le solde et afficher l'icône d'œil barré
                soldeElement.style.display = 'none';
                maskedSoldeElement.style.display = 'inline';
                eyeIcon.classList.remove('bi-eye-slash-fill');
                eyeIcon.classList.add('bi-eye-fill');
                // Enregistrer le choix dans un cookie
                document.cookie = "soldeVisibility=hidden; expires=Fri, 31 Dec 9999 23:59:59 GMT";
            } else {
                // Afficher le solde et afficher l'icône d'œil ouvert
                soldeElement.style.display = 'inline';
                maskedSoldeElement.style.display = 'none';
                eyeIcon.classList.remove('bi-eye-fill');
                eyeIcon.classList.add('bi-eye-slash-fill');
                // Enregistrer le choix dans un cookie
                document.cookie = "soldeVisibility=visible; expires=Fri, 31 Dec 9999 23:59:59 GMT";
            }

            // Inverser l'état de visibilité
            isSoldeVisible = !isSoldeVisible;
        }

        // Ajoutez un gestionnaire d'événement au bouton de basculement
        toggleButton.addEventListener('click', toggleSoldeVisibility);

        // Vérifiez si le choix de visibilité est enregistré dans les cookies et appliquez-le
        var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)soldeVisibility\s*=\s*([^;]*).*$)|^.*$/, "$1");
        if (cookieValue === 'hidden') {
            toggleSoldeVisibility();
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Sélectionnez les éléments du DOM nécessaires
        var totalCreancesElement = document.getElementById('totalCreancesValue');
        var maskedTotalCreancesElement = document.getElementById('maskedTotalCreances');
        var toggleTotalCreancesButton = document.getElementById('toggleTotalCreancesButton');
        var eyeIconTotalCreances = toggleTotalCreancesButton.querySelector('i');
        var isTotalCreancesVisible = true;

        // Fonction pour basculer entre l'affichage et la masquage du total des créances
        function toggleTotalCreancesVisibility() {
            if (isTotalCreancesVisible) {
                // Masquer le total des créances et afficher l'icône d'œil barré
                totalCreancesElement.style.display = 'none';
                maskedTotalCreancesElement.style.display = 'inline';
                eyeIconTotalCreances.classList.remove('bi-eye-slash-fill');
                eyeIconTotalCreances.classList.add('bi-eye-fill');
                // Enregistrer le choix dans un cookie
                document.cookie = "totalCreancesVisibility=hidden; expires=Fri, 31 Dec 9999 23:59:59 GMT";
            } else {
                // Afficher le total des créances et afficher l'icône d'œil ouvert
                totalCreancesElement.style.display = 'inline';
                maskedTotalCreancesElement.style.display = 'none';
                eyeIconTotalCreances.classList.remove('bi-eye-fill');
                eyeIconTotalCreances.classList.add('bi-eye-slash-fill');
                // Enregistrer le choix dans un cookie
                document.cookie = "totalCreancesVisibility=visible; expires=Fri, 31 Dec 9999 23:59:59 GMT";
            }

            // Inverser l'état de visibilité
            isTotalCreancesVisible = !isTotalCreancesVisible;
        }

        // Ajoutez un gestionnaire d'événement au bouton de basculement
        toggleTotalCreancesButton.addEventListener('click', toggleTotalCreancesVisibility);

        // Vérifiez si le choix de visibilité est enregistré dans les cookies et appliquez-le
        var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)totalCreancesVisibility\s*=\s*([^;]*).*$)|^.*$/, "$1");
        if (cookieValue === 'hidden') {
            toggleTotalCreancesVisibility();
        }
    });