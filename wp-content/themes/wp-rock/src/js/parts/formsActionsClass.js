import { setCookie } from './helpers';

export default class FormsActionsClass {
    constructor(popupInstance, validateField) {
        this.popupInstance = popupInstance;
        this.validateField = validateField; // function
    }

    async init() {
        this.checkFormFields();

        this.loginFormFetch();
        this.restorePasswordFormFetch();
        this.getAccessFormFetch();
        this.setNewPasswordFormFetch();
        this.buyProgrammFormFetch();
    }

    loginFormFetch() {
        const loginForm = document.querySelector('.js-login-form');
        if (!loginForm) return;

        loginForm &&
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                // @ts-ignore
                const res = await this.fetchToAction(
                    loginForm,
                    'user_login_ajax'
                );

                this.setDataToRespContainer(res, loginForm);
                if (res.success) {
                    window.location.reload();
                }
            });
    }

    checkFormFields() {
        const formInputs = document.querySelectorAll('input');
        if (!formInputs) return;
        let repeatPassword = '';

        const notValidText = (inputName) => {
            let textResult = '';

            switch (inputName) {
                case 'name':
                    textResult =
                        'Ведіть данні у форматі "First name" "Second name" або "First name"';
                    break;
                case 'email':
                    textResult = 'Невірний формат воду email';
                    break;
                case 'phone':
                    textResult =
                        'Невірний формат воду телефону "+380000000000"';
                    break;
                default:
                    textResult = 'Невірний формат воду';
            }

            return textResult;
        };

        const checkAllInputs = (target) => {
            let allInputsValid = true;

            const parentForm = target.closest('form');

            if (!parentForm) return;

            const allInnerInputs = parentForm.querySelectorAll('input');
            const formSubmit = parentForm.querySelector('input[type="submit"]');

            allInnerInputs.forEach((input) => {
                const inputContainer =
                    input.closest('.js-inner-input-wrapper') ?? null;

                if (
                    !input.name ||
                    !input.value ||
                    input.type === 'hidden' ||
                    input.name === 'password' ||
                    input.name === 'password-repeat'
                )
                    return;

                const isValid = this.validateField(input.name, input.value);

                // eslint-disable-next-line no-shadow
                const notValidTextParagraph =
                    inputContainer &&
                    inputContainer.querySelector('.js-not-valid-text');

                notValidTextParagraph && notValidTextParagraph.remove();

                if (isValid) {
                    input && input.classList.add('valid');
                    input && input.classList.remove('not-valid');
                    inputContainer && inputContainer.classList.add('valid');
                    inputContainer &&
                        inputContainer.classList.remove('not-valid');
                } else {
                    const p = document.createElement('p');
                    p.classList.add('js-not-valid-text');
                    p.classList.add('not-valid-text');
                    p.innerText = notValidText(input.name);

                    inputContainer && inputContainer.appendChild(p);

                    input && input.classList.add('not-valid');
                    input && input.classList.remove('valid');
                    inputContainer && inputContainer.classList.add('not-valid');
                    inputContainer && inputContainer.classList.remove('valid');
                    allInputsValid = false;
                }
            });
            if (formSubmit) {
                formSubmit.disabled = !allInputsValid;
            }
        };

        const checkPasswordMatch = (target) => {
            const parentForm = target.closest('form');
            if (!parentForm) return;

            if (target.name === 'password-repeat') {
                repeatPassword = target.value;

                const passwordInput = parentForm.querySelector(
                    'input[name="password"]'
                );
                if (!passwordInput || target.name === 'password') return;

                const passwordsMatch = passwordInput.value === repeatPassword;

                target.classList.toggle('valid', passwordsMatch);
                target.classList.toggle('not-valid', !passwordsMatch);

                const formSubmit = parentForm.querySelector(
                    'input[type="submit"]'
                );
                formSubmit && (formSubmit.disabled = !passwordsMatch);
            }
        };

        formInputs &&
            formInputs.forEach((input) => {
                input.addEventListener('change', (e) => {
                    checkAllInputs(e.target);
                    checkPasswordMatch(e.target);
                });
            });
    }

    restorePasswordFormFetch() {
        const forgotPasswordForm = document.querySelector(
            '.js-forgot-password-form'
        );

        if (!forgotPasswordForm) return;

        forgotPasswordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await this.fetchToAction(
                forgotPasswordForm,
                'forgot_password'
            );

            if (!res.success) {
                this.setDataToRespContainer(res, forgotPasswordForm);
            } else {
                this.popupInstance.openOnePopup('#forgot-password-popup');
            }
        });
    }

    getAccessFormFetch() {
        const getAccessForm = document.querySelector('.js-get-access-form');

        if (!getAccessForm) return;

        // Get access to programm: registering user and buying programm
        getAccessForm &&
            getAccessForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Set user data for regitering
                const res = await this.fetchToAction(
                    getAccessForm,
                    'register_login_user'
                );
                this.setOrderCookieInfo(e.target);
                if (res.success) {
                    const payRes = await this.fetchToActionPayload({
                        product_id: e.target['post-id'].value,
                        variation_id: 0,
                        amount: e.target.amount.value,
                        quantity: '1',
                    });
                    if (payRes) {
                        // If success return to pay page
                        window.location.href = payRes;
                    } else {
                        this.setDataToRespContainer(payRes, getAccessForm);
                    }
                } else {
                    this.setDataToRespContainer(res, getAccessForm);
                }
            });
    }

    async fetchToAction(form, actionName) {
        const formData = new FormData(form);
        const resJSON = await fetch(
            // eslint-disable-next-line no-undef
            `${var_from_php.ajax_url}?action=${actionName}`,
            {
                method: 'POST',
                body: formData,
            }
        );

        const res = await resJSON.json();
        return res;
    }

    async fetchToActionPayload(productData) {
        const bodyString = `action=mrkv_monopay_product&product=${encodeURIComponent(
            JSON.stringify(productData)
        )}`;

        const resJSON = await fetch(
            // eslint-disable-next-line no-undef
            `${var_from_php.ajax_url}`,
            {
                headers: {
                    Accept: '/',
                    'Accept-Language': 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                    'Cache-Control': 'no-cache',
                    'Content-Type':
                        'application/x-www-form-urlencoded; charset=UTF-8',
                },
                // referrer: window.location.href,
                // referrerPolicy: 'strict-origin-when-cross-origin',
                body: bodyString,
                method: 'POST', // Метод запроса (POST)
                mode: 'cors', // Режим CORS для запросов к другим доменам
                credentials: 'include',
            }
        );
        const res = await resJSON.text();
        return res;
    }

    setNewPasswordFormFetch() {
        const newPasswordForm = document.querySelector(
            '.js-set-new-password-form'
        );

        if (!newPasswordForm) return;

        newPasswordForm &&
            newPasswordForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const res = await this.fetchToAction(
                    newPasswordForm,
                    'set_new_password'
                );

                this.setDataToRespContainer(res, newPasswordForm);
            });
    }

    buyProgrammFormFetch() {
        const buyProgrammForm = document.querySelectorAll(
            '.js-buy-programm-form'
        );

        if (!buyProgrammForm) return;

        buyProgrammForm &&
            buyProgrammForm.forEach((form) => {
                // Buy programm by registered user
                form &&
                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();

                        const submitBtnWrapper =
                            e.target.querySelector('.bottom-wrapper');
                        submitBtnWrapper &&
                            submitBtnWrapper.classList.add('loading');

                        const enteredPromocode = e.target?.promocode?.value;

                        // FETCH TO MONO PAY SYSTEM ( Create pay account)
                        let periodAmount = [];
                        let amount = null;
                        if (
                            e.target?.['period-amount']?.value &&
                            !e.target?.amount?.value
                        ) {
                            periodAmount = e.target?.['period-amount']?.value
                                ? e.target['period-amount'].value.split('|')
                                : [];
                        }

                        if (periodAmount) {
                            // eslint-disable-next-line prefer-destructuring
                            amount = periodAmount[1];
                        } else {
                            amount = e.target?.amount?.value;
                        }

                        const payRes = await this.fetchToActionPayload({
                            product_id: e.target['post-id'].value,
                            variation_id: 0,
                            amount,
                            forcePrice: amount,
                            discount: enteredPromocode,
                            quantity: '1',
                        });
                        if (payRes) {
                            this.setOrderCookieInfo(form);
                            // If success return to pay page
                            window.location.href = payRes;
                        } else {
                            this.setDataToRespContainer(payRes, form);
                        }

                        submitBtnWrapper &&
                            submitBtnWrapper.classList.remove('loading');
                    });
            });
    }

    setDataToRespContainer(res, parentForm) {
        if (!parentForm || !res) return;

        const respContainer = parentForm.querySelector(
            '.js-response-container'
        );
        if (!respContainer) return;

        const additionalClass = res.success ? 'success' : 'error';
        const paragrahp = document.createElement('p');
        paragrahp.classList.add(additionalClass);
        paragrahp.innerText = res.data;
        respContainer.innerHTML = '';
        respContainer.appendChild(paragrahp);
    }

    setOrderCookieInfo(form) {
        const formData = new FormData(form);
        let data = {
            userEmail: formData.get('email'),
            userPhone: formData.get('phone'),
            userFullName: formData.get('user_full_name'),
            redirectPage: formData.get('redirect-page'),
            userRegistration: formData.get('registration'),
            continuePeriod: formData.get('continue-period'),
        };

        const periodAmount = formData.get('period-amount');
        if (periodAmount) {
            data = {
                ...data,
                continuePeriod: periodAmount.split('|')[0],
            };
        }
        setCookie(`creating_order`, JSON.stringify(data), 3);
    }
}
