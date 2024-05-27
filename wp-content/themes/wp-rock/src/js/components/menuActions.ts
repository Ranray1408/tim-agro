export const hoverClickEvent = (): void => {
    const menuItems = document.querySelectorAll('.site-header__menu > .menu-item');

    const canHover = window.matchMedia('(hover: hover)').matches;

    if (canHover) {
        menuItems.forEach(item => {
            const subMenu = item.querySelector('.sub-menu') as HTMLElement;

            if (subMenu) {
                const bounding = subMenu.getBoundingClientRect();
                const offset = 10;

                if (bounding.right > window.innerWidth) {
                    const overflow = bounding.right - window.innerWidth;
                    subMenu.style.left = `-${overflow + offset}px`;
                } else {
                    subMenu.style.left = '0px';
                }
            }

            item.addEventListener('mouseenter', () => {
                item.classList.add('hovered');
            });
            item.addEventListener('mouseleave', () => {
                item.classList.remove('hovered');
            });
        });
    } else {
        menuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopImmediatePropagation();

                const subMenu = item.querySelector('.sub-menu') as HTMLElement;

                if (item.classList.contains('hovered')) {
                    item.classList.remove('hovered');
                } else {
                    item.classList.add('hovered');
                }

                if (subMenu) {
                    const bounding = subMenu.getBoundingClientRect();
                    const offset = 20;
                    subMenu.style.left = '50%';

                    if (bounding.right > window.innerWidth) {
                        const overflow = bounding.right - window.innerWidth;
                        subMenu.style.left = `-${overflow + offset}px`;
                    }

                    subMenu.addEventListener('click', (e) => {
                        e.stopPropagation(); // Зупиняємо поширення події кліку
                    });
                }
            });
        });
    }
};

hoverClickEvent();
