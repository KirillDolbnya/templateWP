document.addEventListener('DOMContentLoaded', function (){

    const modals = document.querySelectorAll('[data-target]');

    document.body.addEventListener('click', function (event){
        const btnTarget = event.target.closest('[data-path]');
        const btnClose = event.target.closest('.modal__close');

        if (btnTarget){
            modals.forEach(modal => {
                if (modal.dataset.target === btnTarget.dataset.path) {
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            });
        }

        if (btnClose){
            const modal = btnClose.closest('[data-target]');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        modals.forEach(modal => {
           if (event.target === modal){
               modal.classList.remove('active');
               document.body.style.overflow = '';
           }
        });
    });

});
