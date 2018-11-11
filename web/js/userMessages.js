class userMessages {
    constructor () {
        this.$flashNotice = document.getElementById("flash-notice");
        this.$cross = document.getElementById("cross");

        this.$crossPopUp = document.getElementById("crossPopUp");
        this.$popUp = document.getElementById("popUp");
        
        this.$reinitButton = document.getElementById("supprimer");

        this.manageFlash();
        // this.managePopUp();
        // this.displayPopUp();
    }

    manageFlash() {
        this.$cross.addEventListener("click", () => {
            this.$flashNotice.style.display = "none";
        })
    }

    // managePopUp() {
    //     this.$crossPopUp.addEventListener("click", () => {
    //         this.$popUp.style.display = "none";
    //     })
    // }

    // displayPopUp() {
    //     this.$reinitButton.addEventListener("click", () => {
    //         this.$popUp.style.display = "block";
    //     })
    // }
}