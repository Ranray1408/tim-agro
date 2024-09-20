/* eslint-disable */
import { Style2 } from '../responsive-lib';

export const typoghraphy = new Style2('typoghraphy')
    .addMediaQuery(
        { orientation: 'landscape' },
        {
            '.h2-js': {
                fontSize: '71px',
            },
            '.h6-js': {
                fontSize: '17px',
            },
            '.h6-scaled-js': {
                fontSize: '15px',
            },
            '.subtitle2-js': {
                fontSize: '25px',
            },
            '.text1-js': {
                fontSize: '9.5px',
            },
        }
    )
    .update();
