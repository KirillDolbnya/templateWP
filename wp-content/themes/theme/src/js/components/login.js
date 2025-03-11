document.addEventListener('DOMContentLoaded', function (){
    document.body.addEventListener('submit', function (event){
        const form = event.target.closest(".modal-login__form");

        if (form) {
            event.preventDefault();

            const formData = new URLSearchParams(new FormData(form));
            formData.append("action", "custom_woo_login_register");

            fetch(ajaxData.ajaxurl, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data.success)
                    alert(data.data.message);
                    if (data.success && data.data.redirect) {
                        window.location.href = data.data.redirect;
                    }
                })
                .catch((error) => {
                    console.error("Ошибка запроса:", error);
                    alert("Произошла ошибка. Попробуйте еще раз.");
                });
        }
    });
});