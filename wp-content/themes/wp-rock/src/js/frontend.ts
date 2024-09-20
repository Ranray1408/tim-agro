/**
 * SASS
 */
import '../scss/frontend.scss';
import initAccordion, { initInnerAccordion } from './components/accordion';
import hoverClickEvent from './components/menuActions';
import ProfileFunctionality from './components/profileFunctionality';
/**
 * JavaScript
 */
import FormsActionsClass from './parts/formsActionsClass';
import { anchorLinkScroll, isInViewport, loadFileName, validateField } from './parts/helpers';
import tabsNavigation from './parts/navi-tabs';
import Popup from './parts/popup-window';

function ready() {
    const blocks = document.querySelectorAll('.js-anim-activate');
    const siteHeader = document.querySelector('.js-site-header');
    const popupInstance = new Popup();
    const profileFunctionality = new ProfileFunctionality();
    const formsActionClass = new FormsActionsClass(popupInstance, validateField);

    popupInstance.init();
    profileFunctionality.init();
    formsActionClass.init();

    const paySuccessResponse = document.querySelector('#pay-success-response');

    if (paySuccessResponse) {
        popupInstance.openOnePopup('#pay-success-response');
        if (paySuccessResponse.classList.contains('profile-page')) {
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        }
    }

    anchorLinkScroll(
        'a[href^="#"]:not(.js-open-popup-activator):not(.js-tab-link)',
        (event) => {
            const currentUrl = window.location.href;
            const cleanUrl = currentUrl.split('#')[0];
            // @ts-ignore
            const siteUrl = `${var_from_php.site_url}/`;
            // @ts-ignore
            const linkUrl = event?.target?.getAttribute('href');
            const anchorBlock = document.querySelector(linkUrl);

            siteHeader && siteHeader.classList.remove('menu-opened');
            document.body.classList.remove('popup-opened');
            popupInstance.closePopup();

            if (siteUrl !== cleanUrl && !anchorBlock) {
                window.location.href = `${siteUrl}${linkUrl}`;
            }
        },
        -70
    );

    hoverClickEvent();
    initAccordion();
    initInnerAccordion();
    tabsNavigation('.js-tab-link', '.js-tab-panel');

    loadFileName();

    if (window.scrollY > 100) {
        siteHeader && siteHeader.classList.add('scrolled');
    } else {
        siteHeader && siteHeader.classList.remove('scrolled');
    }

    blocks &&
        blocks.forEach((el) => {
            const block = el as HTMLElement;

            isInViewport(block) && block.classList.add('viewed');
        });

    window.document.addEventListener('scroll', () => {
        // Scroll actions

        if (window.scrollY > 100) {
            siteHeader && siteHeader.classList.add('scrolled');
        } else {
            siteHeader && siteHeader.classList.remove('scrolled');
        }

        blocks &&
            blocks.forEach((el) => {
                const block = el as HTMLElement;

                isInViewport(block) && block.classList.add('viewed');
            });
    });

    document.body.addEventListener('click', (e) => {
        const target = e.target as HTMLElement;
        const { role } = target.dataset;

        if (target.classList.contains('js-popup-in-popup')) {
            popupInstance.forceCloseAllPopup();
            setTimeout(() => {
                popupInstance.openOnePopup(target.dataset.href);
            }, 1000);
        }

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

    window.document.addEventListener('wpcf7mailsent', () => {
        // Success send cf7
        const header = document.querySelector('.js-site-header') as HTMLElement;

        setTimeout(() => {
            if (header && header.dataset.thank_you_page) {
                window.location.replace(header.dataset.thank_you_page);
            }
        }, 2000);
    });
}

window.document.addEventListener('DOMContentLoaded', ready);
