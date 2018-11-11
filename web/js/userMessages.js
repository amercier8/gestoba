class userMessages {
    constructor () {
        this.$flashNotice = document.getElementById("flash-notice");
        this.$cross = document.getElementById("cross");

        this.displayPopUp();
        this.manageFlash();
    }

    manageFlash() {
        this.$cross.addEventListener("click", () => {
            this.$flashNotice.style.display = "none";
        })
    }

    displayPopUp() {
        var elems = document.getElementsByClassName('supprimer');
        var confirmIt = function (e) {
            if (!confirm('Etes-vous sûr de vouloir continuer?')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }
    }
}