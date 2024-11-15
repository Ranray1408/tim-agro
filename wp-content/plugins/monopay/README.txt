=== Monobank WP Payment ===
Contributors: plata by mono
Tags: mono, cashier, payments, routing
Requires at least: 6.2
Tested up to: 6.5.4
Stable tag: 3.1.2
Requires PHP: 7.4
Requires Plugins: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Офіційний модуль від monobank для підключення інтернет-еквайрингу.

== Description ==

Офіційний модуль від monobank для підключення інтернет-еквайрингу.
Модуль WooCommerce для Wordpress.
Тариф: 1.3% з українських карток, 2% з іноземних карток.

Кроки для підключення:
1. Кроки для підключення:
1. Заповніть заявку за посиланням https://www.monobank.ua/e-comm
2. Отримайте підтвердження про відкриття послуги (у застосунок)
3. Завантажте модуль. Інструкція з установки модуля доступна за посиланням https://doc.clickup.com/d/4e3cn-16048/monobank-woo-commercepaymentgateway

= Why plata by mono? =
* Currency controller - Manage your currencies exposure with advanced converting capabilities.
* Personalized reporting machine - Slice & Dice your data to get instant insights on your payment flow
* Plug & payment - One simple integration either via secured embedded iframe, or with our open API
* PAAS - plugin relies on [plata by mono](https://www.monobank.ua/) as a service

= Additional Features =
* Quick installation and setup.
* All in one admin to manage and optimize providers & payments
* The ultimate plugin to create simple Mono payment buttons.
* View purchase orders from your WordPress admin dashboard.
* Quick overview of merchant performance
* Arrange your own payment flow quick and easy
* Restrict and block payments
* Set rules and behavior per country
* Set payment solution to handle lower payments than set amount
* Set payment solution to handle greater payments than set amount
* Set maximum volume for each payment provider
* Select country/currency/payment solution to process the transaction
* Select filters according to your need: PSP, Country, Currency, client email & more
* Set base currency for conversion
* Set payment limits according to needs
* Set minimum & maximum amount per payment provider and method

[Terms & Conditions](https://www.monobank.ua/legal/terms)
[Privacy Policy](https://www.monobank.ua/legal/privacy)

= Detailed Documentation =
For detailed documentation and instructions please check the plata by mono [Integration Docs](https://api.monobank.ua/docs/acquiring.html).

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to "Plugins->Add New" from your dashboard
2. Search for 'plata by mono Woocommerce'
3. Click 'Install Now'
4. Activate the plugin

= Uploading via WordPress Dashboard =
1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin
4. Enable/disable Live mode, depending on the integration.
5. Enter your API key. plata by mono will provide a set API Key of the environments.

== Frequently Asked Questions ==

= Can It be tested before going live? =

Yes, please ask for sandbox credentials and anble Test mode in the plugin configuration

= Where can I see full list of my transactions? =

You will be provided with credentials to Mono Merchant Admin, where you can fully manage your merchant and transactions.

== Changelog ==

= 1.0.0 =
First Release

= 1.0.1 =
Fix text

= 1.0.2 =
Change link to monobank and remove destination

= 1.0.3 =
Ability to make a return through the module panel
Added the ability to convert to currency

= 1.0.4 =
Add logo and change plugin name

= 1.0.5 = 
Changing the display of the final order

= 1.0.6 = 
Fixed bugs with refund

= 1.0.6.1 = 
Refund revision

= 1.0.6.2 = 
Update inside refund

= 2.0.0 =
- added 'code' parameter sending in invoice creation to allow fiscalization;
- added holds;
- fixed amount handling.

= 2.0.1 =
- fixed sending 'code' parameter for fiscalization when sku is absent.

= 2.0.2 =
- fixed bug with payment failure;
- removed logo from payment page;
- added ability to update payment status from admin panel.

= 2.0.3 =
- fixed bug with payment failure;
- removed logo from payment page;
- added ability to update payment status from admin panel.

= 2.0.4 =
- added thankyou page redirect.

= 2.1.0 =
- added support for WooCommerce 8.3+ versions;
- added shipping to cart.

= 2.1.1 =
- installation fix.

= 2.1.2 =
- success page fix for non-mono orders.

= 3.0.0 =
- оновлення назви monopay -> plata by mono;
- фікс передачі в корзину варіативних товарів;
- додано передачу електронної пошти для відправки фіскальних чеків від checkbox;
- фікс потенційних проблем із передачею кількості товарів;
- фікс потенційних проблем обробки вебхуків.

= 3.1.0 =
- додано логотип до методу оплати.

= 3.1.1 =
- фікс створення інвойсу, якщо під час оплати було використано купон;
- додано переведення ордеру в статус "Виконано" замість "В обробці" після успішної оплати;
- оновлено роботу з корзиною під час створення інвойсу.

= 3.1.2 =
- фікс створення інвойсу, в кейсах, коли кількість товару більше 1.