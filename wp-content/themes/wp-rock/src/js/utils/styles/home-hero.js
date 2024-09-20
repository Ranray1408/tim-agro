/* eslint-disable */
import { Style2 } from '../responsive-lib';

export const homeHero = new Style2('home-hero')
    .addMediaQuery(
        { orientation: 'landscape' },
        {
            '.home-hero > .content-wrapper': {
                width: 763,
                marginTop: 85,
            },
            '.home-hero .square1 .square2 .square3 .content-wrapper .h2-js': {
                width: 550,
            },
            '.home-hero .square1 .square2 .square3 .content-wrapper .subtitle2-js':
                {
                    width: 800,
                },
            '.home-hero .square1 .square2 .square3 .content-wrapper .buttons': {
                gap: 18,
            },
            '.home-hero .square1 .square2 .figures .bigtriangle > div > .h6-js':
                {
                    width: 81,
                    marginTop: 20,
                },
            '.home-hero .square1 .square2 .figures .bigtriangle > div > .content-wrapper > .h6-scaled-js':
                {
                    width: 63,
                },
        }
    )
    .update();
