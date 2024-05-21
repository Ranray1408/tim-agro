<?php
/**
 * Custom accept cookie functionality
 *
 * @package WP-rock/custom-accept-cookie
 */

add_action(
    'wp_rock_after_site_page_tag',
    function () {
        global $global_options;
        $text = get_field_value($global_options, 'cookie_text');
        $btn_accept = get_field_value($global_options, 'cookie_button_accept');
        $btn_reject = get_field_value($global_options, 'cookie_button_reject');

        if (empty($text) || empty($btn_accept)) {
            return '';
        }
        ?>
        <style>
            .accept-cookie-box {
                z-index: 2000;
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                padding: 40px 0;
                transform: translateY(100%);
                background: #5C5D72;
                mix-blend-mode: normal;
            }

            @media screen and (max-width: 479px) {
                .accept-cookie-box {
                    padding: 16px 24px;
                }
            }

            .accept-cookie-box.opened {
                transform: translateY(0);
            }

            .accept-cookie-box__inner {
                display: flex;
                align-items: flex-start;
                justify-content: flex-start;
                flex-direction: column;
            }

            @media screen and (max-width: 816px) {
                .accept-cookie-box__inner {
                    flex-wrap: wrap;
                    align-items: flex-start;
                    justify-content: flex-start;
                }
            }

            .accept-cookie-box__text {
                font-family: Turbine, sans-serif;
                font-size: 20px;
                font-style: normal;
                font-weight: 400;
                line-height: 1.4;
                color: #FFFFFF;
                position: relative;
                margin-bottom: 16px;
            }

            .accept-cookie-box__text p {
                font-family: inherit;
                font-size: inherit;
                font-style: inherit;
                font-weight: inherit;
                line-height: inherit;
                color: inherit;
                margin-bottom: 10px;

                &:last-child {
                    margin-bottom: 0;
                }
            }
            .accept-cookie-box__text h4,
            .accept-cookie-box__text h3,
            .accept-cookie-box__text h5 {
                font-family: Turbine, sans-serif;
                font-size: 30px;
                color: #fff;
                font-style: normal;
                font-weight: 700;
                line-height: 1.26;
                margin: 0 0 16px;
                padding: 0;
            }

            .accept-cookie-box__buttons {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                gap: 16px;
            }

            .btn-accept,
            .btn-reject {
                appearance: none;
                height: 30px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0 8px;
                border-radius: 0;
                border: 1px solid var(--Orange, #CC7510);
                color: #FFF;
                font-family: Turbine, sans-serif;
                font-size: 24px;
                font-style: normal;
                font-weight: 400;
                line-height: 1.25;
                cursor: pointer;
            }

            .btn-accept {
                color: #fff;
                background-color: #CC7510;
            }

            @media screen and (max-width: 768px) {
                .accept-cookie-box__text {
                    font-size: 18px;
                }
                .accept-cookie-box__text h4,
                .accept-cookie-box__text h3,
                .accept-cookie-box__text h5 {
                    font-size: 24px;
                }

                .btn-accept,
                .btn-reject {
                    font-size: 16px;
                    height: 24px;
                }
            }

            @media screen and (max-width: 479px) {

            }
        </style>

        <div class="accept-cookie-box js-accept-cookie-box">
            <div class="container">
                <div class="accept-cookie-box__inner">
                    <div class="accept-cookie-box__text">
                        <?php
                        echo do_shortcode($text);
                        ?>
                    </div>
                    <div class="accept-cookie-box__buttons">
                        <button class="accept-cookie-box__reject-btn  btn-reject js-reject-cookie-btn"
                                data-role="reject-cookie">
                            <?php
                            echo do_shortcode($btn_reject);
                            ?>
                        </button>
                        <button class="accept-cookie-box__accept-btn  btn-accept js-accept-cookie-btn"
                                data-role="accept-cookie">
                            <?php
                            echo do_shortcode($btn_accept);
                            ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>

            window.addEventListener('load', (event) => {
                const ACCEPT_COOKIE_BOX   = document.querySelector('.js-accept-cookie-box');
                const CHAT_ICON           = document.querySelector('.chat-icon');
                const ACCEPT_COOKIE_CHECK = localStorage.getItem('accept-cookie');

                if ( !ACCEPT_COOKIE_CHECK || +ACCEPT_COOKIE_CHECK !== 1 ) {
                    (ACCEPT_COOKIE_BOX) && ACCEPT_COOKIE_BOX.classList.add('opened');
                    document.body.classList.add('accept-cookie-box-is-opened');
                }

                document.body.addEventListener('click', function(event) {
                    const ROLE = event.target.dataset.role;
                    if ( !ROLE ) return;

                    switch ( ROLE ) {
                        case 'accept-cookie':
                            localStorage.setItem('accept-cookie', 1);
                            (ACCEPT_COOKIE_BOX) && ACCEPT_COOKIE_BOX.classList.remove('opened');
                            document.body.classList.remove('accept-cookie-box-is-opened');
                            break;
                        case 'reject-cookie':
                            (ACCEPT_COOKIE_BOX) && ACCEPT_COOKIE_BOX.classList.remove('opened');
                            document.body.classList.remove('accept-cookie-box-is-opened');
                            break;
                    }
                });
            });

        </script>
        <?php
    }
);
