document.addEventListener('DOMContentLoaded', function (){

    const inputsName = document.querySelectorAll('input[name="name"]');
    const inputsPhone = document.querySelectorAll('input[name="phone"]');
    const inputsEmail = document.querySelectorAll('input[name="email"]');

    inputsName.forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-Zа-яА-ЯёЁ\s]/g, "");
        });
    });

    inputsPhone.forEach(input => {
        input.addEventListener('input', function () {
            let val = this.value.replace(/\D/g, "");
            if (val.length > 11) val = val.slice(0, 11);
            let formatted = "+7";
            if (val.length > 1) formatted += " (" + val.substring(1, 4);
            if (val.length > 4) formatted += ") " + val.substring(4, 7);
            if (val.length > 7) formatted += "-" + val.substring(7, 9);
            if (val.length > 9) formatted += "-" + val.substring(9, 11);
            this.value = formatted;
        });
    });

    inputsEmail.forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^a-zA-Z0-9@.\-]/g, "");

            if (!this.value.includes("@")) {
                this.style.border = "1px solid red";
            } else {
                this.style.border = "";
            }
        });
    });

});