const observer = new IntersectionObserver(
    (entries) => {
        entries &&
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
    },
    {
        root: null,
        rootMargin: '0px',
        threshold: 0.5,
    }
);

const initAnimation = () => {
    const animatedElements = document.querySelectorAll('.animated-element');

    animatedElements.forEach((element) => {
        observer.observe(element);
    });
};

export default initAnimation;
