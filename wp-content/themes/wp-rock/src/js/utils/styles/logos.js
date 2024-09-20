/* eslint-disable */
import { Style2 } from '../responsive-lib';

export const logos = new Style2('logos')
    .addMediaQuery(
        { orientation: 'landscape' },
        {
            '.logos__logo-img-wrap img': {
                height: '35px',
            },
        }
    )
    .update();
