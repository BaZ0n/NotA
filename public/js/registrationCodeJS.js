document.addEventListener("DOMContentLoaded", function() {
    const emailForm = document.getElementById("emailForm");
    if (emailForm) {
        document.getElementById("emailForm").addEventListener("submit", function(event) {
            event.preventDefault();
            emailForm.classList.add("d-none")
            codeForm = document.getElementById("codeForm");
            codeForm.classList.remove("d-none");
            //this.submit();
        });
    }

    // const codeForm = document.getElementById("codeForm");
    // if (codeForm) {
    //     document.getElementById("codeForm").addEventListener("submit", function(event)) {
    //         event.preventDefault();
    //         // this.submit();
    //     }
    // }
});