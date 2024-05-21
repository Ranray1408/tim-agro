/* eslint-disable */
const configuration = () => {
    const body = document.body;
    body.style.height = '100vh';
    body.style.overflow = 'hidden';
    body.classList.add("ci/cd-works!");

    const wrapper = document.body.querySelector('#wrapper');
    wrapper.setAttribute('role', 'main');
    wrapper.style.height = '100%';
    wrapper.style.width = '100%';
    wrapper.style.overflowY = 'auto';
    wrapper.style.overflowX = 'hidden';

    const main_wrapper = document.body.querySelector('#main-wrapper');
    main_wrapper.style.height = 'auto';


    const expertAndMatter = document.querySelector('.expert-l-matter');

    if(expertAndMatter) {
        const expertLens = document.querySelector('#expert-lens');
        const matter = document.querySelector('#matter');

        const container = document.createElement('div');
        container.classList.add('expertLens-matter');
        const container2 = document.createElement('div');
        container2.classList.add('expertLens-matter-wrapper');
    
  /*      if (expertAndMatter) {
            container2.classList.add('expert-l-matter');
            expertAndMatter.remove();
        }*/

        main_wrapper.append(container);
        container.append(container2);

        if (expertLens) {
            container2.append(expertLens);
        }

        if (matter) {
            container2.append(matter);
        }
    }
};
export default configuration;
