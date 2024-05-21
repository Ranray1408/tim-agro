const initAccordion = () => {
    const accordions = document.querySelectorAll('.js-wrock-accordion');

    accordions &&
        accordions.forEach((item) => {
            item.addEventListener('click', (event) => {
                const target = event.target as HTMLElement;
                const btn = target.closest('.js-wrock-accordion__btn');
                if (!btn) return;

                const element = btn.parentElement as HTMLElement;
                const content = element.querySelector('.js-wrock-accordion__content') as HTMLElement;
                const openItem = item.querySelector('.js-wrock-accordion__item.open') as HTMLElement;

                // if (openItem && openItem !== item) {
                //     openItem.classList.remove('open');
                // }

                element.classList.toggle('open');
            });
        });
};

export default initAccordion;
