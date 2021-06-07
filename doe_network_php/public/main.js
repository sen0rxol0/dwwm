document.onreadystatechange = () => {
    if (document.readyState === 'complete') {
        // document.querySelectorAll('[data-btn-id="edit_category__btn"]')
        //     .forEach(btn => {
        //         btn.addEventListener('click', (ev) => {
        //             // const categoryId = ev.target.parentElement.getAttribute('data-category-id');
        //             document.querySelector('#edit_category__form')
        //                 .querySelector('input[name="name"]')
        //                 .setAttribute('value', ev.target.parentElement.getAttribute('data-category-name'));

        //             const accordionBtn = document.querySelector('#edit_category__btn_accordion');
        //             if (accordionBtn.classList.contains('collapsed')) {
        //                 accordionBtn.dispatchEvent(new MouseEvent('click'));
        //                 // accordionBtn.classList.remove('d-none');
        //             }
        //         });
        //     });
    }
};