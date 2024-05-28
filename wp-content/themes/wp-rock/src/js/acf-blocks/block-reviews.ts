import Swiper, { Navigation, Pagination } from "swiper";

/* eslint-disable @wordpress/no-global-event-listener */
const initBlockReviews = () => {
    const reviewsSwiper = new Swiper('.js-reviews-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        modules : [Pagination, Navigation],
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },

        // Navigation arrows
        navigation: {
          nextEl: '.reviews-button-next',
          prevEl: '.reviews-button-prev',
        },
        breakpoints: {
            1200: {
                slidesPerView: 3,
                spaceBetween: 20
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 20
            },
        }
      });
};

document.addEventListener('DOMContentLoaded', initBlockReviews, false);

// Initialize dynamic block preview (editor).
if (window['acf']) {
    window['acf']?.addAction('render_block_preview', initBlockReviews);
}

export {};
