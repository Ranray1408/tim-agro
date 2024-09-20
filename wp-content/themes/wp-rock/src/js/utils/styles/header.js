/* eslint-disable */
import { Style2 } from '../responsive-lib';

export const header = new Style2('header')
    .addMediaQuery(
        { orientation: 'landscape' },
        {
            'header > .container': {
                maxWidth: '1272px',
            },
            '.site-header__logo-wrap': {
                marginRight: '40px',
            },
            '.site-header__logo': {
                width: '50px',
                height: '51px',
            },
            'header > .container > .site-header__header-wrapper ': {
                padding: '10px 40px',
            },
            '.site-header__menu': {
                gap: '37px',
            },

            '.site-header__menu .menu-item .like-link, .site-header__menu .menu-item a':
                {
                    fontSize: '14px',
                },
            '.site-header__header-wrapper .button': {
                fontSize: '16px',
                padding: '6px 17px',
                height: '31px',
            },
            '.site-header__header-wrapper .site-header__btn-1': {
                marginLeft: '53px',
            },
            '.site-header__header-wrapper .site-header__btn-2': {
                marginLeft: '25px',
            },
        }
    )
    .update();
