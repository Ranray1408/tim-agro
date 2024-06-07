/**
 * SASS
 */
import '../scss/frontend.scss';
import initAccordion, { initInnerAccordion } from './components/accordion';
import { hoverClickEvent } from './components/menuActions';
import { ProfileFunctionality } from './components/profileFunctionality';
/**
 * JavaScript
 */
import Sliders from './components/swiper-init';
import { anchorLinkScroll, checkFormFields, fetchLogin, loadFileName, restorePasswordFormEvent } from './parts/helpers';
import tabsNavigation from './parts/navi-tabs';
import Popup from './parts/popup-window';

function ready() {
    const siteHeader = document.querySelector('.js-site-header');
    const popupInstance = new Popup();
    const profileFunctionality = new ProfileFunctionality();
    popupInstance.init();
    profileFunctionality.init();

    anchorLinkScroll('a[href^="#"]:not(.js-open-popup-activator):not(.js-tab-link)', null, 100);

    hoverClickEvent();
    initAccordion();
    initInnerAccordion();
    tabsNavigation('.js-tab-link', '.js-tab-panel');
    loadFileName();
    fetchLogin();
    checkFormFields();
    restorePasswordFormEvent(popupInstance);

    if (window.scrollY > 100) {
        siteHeader && siteHeader.classList.add('scrolled');
    } else {
        siteHeader && siteHeader.classList.remove('scrolled');
    }

    window.document.addEventListener('scroll', () => {
        // Scroll actions

        if (window.scrollY > 100) {
            siteHeader && siteHeader.classList.add('scrolled');
        } else {
            siteHeader && siteHeader.classList.remove('scrolled');
        }
    });

    document.body.addEventListener('click', (e) => {
        const target = e.target as HTMLElement;
        const { role } = target.dataset;

        // const hoverQuery = window.matchMedia('(hover: hover)');

        // if (target.classList.contains('menu-item-has-children') && !hoverQuery.matches) {
        //     target.classList.toggle('opened');
        // }

        if (!role) return;

        switch (role) {
            case 'mobile-menu': {
                e.preventDefault();
                siteHeader && siteHeader.classList.toggle('menu-opened');
                document.body.classList.toggle('popup-opened');
                break;
            }
            default:
                break;
        }
    });

    window.document.addEventListener('wpcf7mailsent', (event) => {
        // Success send cf7
        const siteHeader = document.querySelector('.js-site-header') as HTMLElement;

        setTimeout(() => {
            if (siteHeader && siteHeader.dataset.thank_you_page) {
                window.location.replace(siteHeader.dataset.thank_you_page);
            }
        }, 2000);
    });
}

window.document.addEventListener('DOMContentLoaded', ready);
