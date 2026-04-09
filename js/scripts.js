// Préloader (fallback natif, même si jQuery n'est pas chargé)
window.addEventListener('load', function () {
    var preloader = document.getElementById('preloader');
    if (preloader) {
        preloader.style.display = 'none';
    }

    var mainWrapper = document.getElementById('main-wrapper');
    if (mainWrapper) {
        mainWrapper.classList.add('show');
    }
});

// Si jQuery n'est pas disponible (ex: assets vendor manquants),
// on évite de bloquer l'application avec des erreurs JS.
if (typeof window.jQuery === 'undefined') {
    console.warn('jQuery non chargé: certaines interactions UI sont désactivées.');
} else {
    (function ($) {
        "use strict";

        //to keep the current page active
        $(function () {
            for (
                var nk = window.location,
                    o = $(".settings-menu a, .menu a")
                        .filter(function () {
                            return this.href == nk;
                        })
                        .addClass("active")
                        .parent()
                        .addClass("active");
                ;

            ) {
                if (!o.is("li")) break;
                o = o.parent().addClass("show").parent().addClass("active");
            }
        });

        // Transaction history hover active
        $(".invoice-content").on("mouseover", "li", function () {
            $(".invoice-content li.active").removeClass("active");
            $(this).addClass("active");
        });

        // Balance Details widget of Home page
        $(".balance-stats").on("mouseover", function () {
            $(".balance-stats.active").removeClass("active");
            $(this).addClass("active");
        });

        // Bills widget of balance page
        $(".bills-widget-content").on("mouseover", function () {
            $(".bills-widget-content.active").removeClass("active");
            $(this).addClass("active");
        });

        $('.content-body').css({ 'min-height': (($(window).height())) + 50 + 'px' });
    })(window.jQuery);
}


// Dark light toggle switch
(function () {
    let onpageLoad = localStorage.getItem("theme") || "";
    let element = document.body;
    element.classList.add(onpageLoad);

    var themeNode = document.getElementById("theme");
    if (themeNode) {
        themeNode.textContent = localStorage.getItem("theme") || "light";
    }
})();

function themeToggle() {
    let element = document.body;
    element.classList.toggle("dark-theme");

    let theme = localStorage.getItem("theme");
    if (theme && theme === "dark-theme") {
        localStorage.setItem("theme", "");
    } else {
        localStorage.setItem("theme", "dark-theme");
    }
}
