document.addEventListener("DOMContentLoaded", function() {
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("active");

            var panel = this.nextElementSibling;

            if (panel) {
                panel.classList.toggle("show");
            }
        });
    }

    document.querySelectorAll("input[type='number']").forEach(input => {
        input.addEventListener("input", function() {
            const parkYeri = this.value;
            const form = this.closest("form");
            const button = form.querySelector("button");

            if (parkYeri >= 1) {
                button.disabled = false; // Butonu aktif yap
            } else {
                button.disabled = true; // Butonu pasif yap
            }
        });
    });
});
