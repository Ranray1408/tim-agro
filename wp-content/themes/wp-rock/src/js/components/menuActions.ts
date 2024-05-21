export const hoverClickEvent = (): void => {
    const menuItems = document.querySelectorAll('.menu-item');

    const canHover = window.matchMedia('(hover: hover)').matches;

    if (canHover) {
        menuItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.classList.add('hovered');
            });
            item.addEventListener('mouseleave', () => {
                item.classList.remove('hovered');
            });
        });
    } else {
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                if (item.classList.contains('hovered')) {
                    item.classList.remove('hovered');
                } else {
                    item.classList.add('hovered');
                }
            });
        });
    }
};

hoverClickEvent();
