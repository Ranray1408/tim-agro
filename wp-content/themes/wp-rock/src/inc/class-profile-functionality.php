<?php

/**
 * Class profile_functionality
 *
 * Handles various user profile functionalities.
 */
class Profile_Functionality {
    private $monobank;
    private $global_options;
    private $client;

    public function __construct($monobank, $global_options, $client) {
        $this->global_options = $global_options;
        $this->monobank = $monobank;
        $this->client = $client;
    }
    /**
     * Initializes the class by adding action hooks.
     */
    public function init() {
        $this->addActions();
    }
    /**
     * Adds necessary WordPress action hooks.
     */
    private function addActions() {
        // Save video data
        add_action('wp_ajax_save_video_data', array($this, 'add_json_video_data_action'));
        // Update user data
        add_action('wp_ajax_update_user_data', array($this, 'update_user_data_action'));
        // User login via AJAX
        add_action('wp_ajax_nopriv_user_login_ajax', array($this, 'user_login_action'));
        // Forgot password functionality
        add_action('wp_ajax_forgot_password', array($this, 'forgot_password_action'));
        add_action('wp_ajax_nopriv_forgot_password', array($this, 'forgot_password_action'));
        // User registration and login
        add_action('wp_ajax_register_login_user', array($this, 'register_login_user_action'));
        add_action('wp_ajax_nopriv_register_login_user', array($this, 'register_login_user_action'));

        // Add programm access to user
        add_action('wp_ajax_set_new_password', array($this, 'set_new_password_action'));
        add_action('wp_ajax_nopriv_set_new_password', array($this, 'set_new_password_action'));

        add_action('wp_ajax_nopriv_save_register_user_data', array($this, 'save_register_user_data_action'));
    }
    /**
     * Adds JSON video data.
     *
     * @return void
     */
    public function add_json_video_data_action() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data)) {
            wp_send_json_error(__('Немає необхідних даних.', 'wp-rock'));
        }

        if (empty($data['userId'])) {
            wp_send_json_error(__('Немає ідентифікатора користувача.', 'wp-rock'));
        }
        $result_courses = $this->update_user_programm($data['courses'], $data['userId']);
        $result_lectures = $this->update_user_programm($data['lectures'], $data['userId']);

        wp_send_json_success(
            array(
                'courses' => $result_courses,
                'lectures' => $result_lectures
            )
        );
    }
    /**
     * Updates user programm.
     *
     * @return bool
     */
    public function update_user_programm($data, $user_id) {
        if (empty($data) || empty($user_id)) {
            return false;
        }

        $user_programm = get_field($data['programmType'], 'user_' . $user_id);

        if (empty($user_programm)) {
            return false;
        }

        if (!empty($user_programm)) {
            foreach ($user_programm as $key => $item) {
                if (empty($item['post_id']))
                    continue;

                $current_block = $data['programms']['programm-' . $item['post_id']];
                if (!empty($current_block)) {
                    $user_programm[$key]['programm_data'] = json_encode($current_block);
                }
            }
            $result = update_field($data['programmType'], $user_programm, 'user_' . $user_id);
        }

        return $result;
    }
    /**
     * Updates user data.
     *
     * @return void
     */
    public function update_user_data_action() {
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $file = $_FILES['avatar'];

        $updated = wp_update_user(
            array(
                'ID' => $user_id,
                'display_name' => $name,
                'user_email' => $email,
            )
        );

        if (is_wp_error($updated)) {
            wp_send_json_error(__('Помилка оновлення даних користувача.', 'wp-rock'));
        }


        if (!empty($file)) {
            $avatar_id = media_handle_upload('avatar', 0);
            if (is_wp_error($avatar_id)) {
                wp_send_json_error(__('Помилка завантаження аватара.', 'wp-rock'));
            }
            update_field('user_avatar', $avatar_id, 'user_' . $user_id);
        }

        $user_phone = update_field('user_phone', $phone, 'user_' . $user_id);

        if (is_wp_error($user_phone)) {
            wp_send_json_error(__('Помилка оновлення телефону.', 'wp-rock'));
        }

        wp_send_json_success(__('Дані успішно оновлено.', 'wp-rock'));
    }
    /**
     * Handles user login via AJAX.
     *
     * @return void
     */
    public function user_login_action() {
        $username_or_email = filter_input(INPUT_POST, 'user-name-email', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'user-password', FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($username_or_email) || empty($password)) {
            wp_send_json_error(__('Будь ласка, введіть ім\'я користувача та пароль.', 'wp-rock'));
        }

        $user = $this->get_user_by_email_or_name($username_or_email);

        if (is_wp_error($user)) {
            wp_send_json_error(__('Не вдається знайти користувача за електронною поштою чи іменем.', 'wp-rock'));
        }

        $user_logined = $this->login_user($user, $password);

        if (is_wp_error($user_logined)) {
            wp_send_json_error(__('Невірний логін або пароль.', 'wp-rock'));
        }

        wp_send_json_success(__('Вхід виповнено.', 'wp-rock'));
    }
    /**
     * Handles forgot password functionality.
     *
     * @return void
     */
    public function forgot_password_action() {
        $user_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $forgot_password_page = filter_input(INPUT_POST, 'forgot-password-page', FILTER_SANITIZE_SPECIAL_CHARS);

        if (isset($user_email)) {
            $user_email = sanitize_email($user_email);

            $user = get_user_by('email', $user_email);
            if (!$user) {
                wp_send_json_error(__('Користувач з даним email не знайдений.', 'wp-rock'));
            } else {
                $email_sent = $this->send_reset_password($user, $forgot_password_page);

                if ($email_sent) {
                    wp_send_json_success(__('Посилання для скидання пароля відправлено на ваш email.', 'wp-rock'));
                } else {
                    wp_send_json_error(__('Сталася помилка під час відправлення email. Будь ласка, спробуйте ще раз.', 'wp-rock'));
                }
            }
        }
    }

	public function add_days_to_current_date($days) {
		$current_date = new DateTime();
		$current_date->modify('+' . $days . ' days');
		return $current_date->format('d.m.Y');
	}

	/**
	 * Generate Promocode
	 * @param $expire_period
	 *
	 * @return string
	 */
	public function generate_promocode($expire_period) {

		$promo_code = 'PROMO_' . wp_generate_password(8, false);

		$coupon_data = array(
			'post_title'    => $promo_code,
			'post_content'  => '',
			'post_excerpt'  => 'Скидка 300 грн',
			'post_status'   => 'publish',
			'post_author'   => get_current_user_id(),
			'post_type'     => 'shop_coupon'
		);

		$new_coupon_id = wp_insert_post($coupon_data);

		update_post_meta($new_coupon_id, 'discount_type', 'fixed_cart');
		update_post_meta($new_coupon_id, 'coupon_amount', 300);
		update_post_meta($new_coupon_id, 'individual_use', 'yes');
		update_post_meta($new_coupon_id, 'usage_limit', 1);
		update_post_meta($new_coupon_id, 'expiry_date', $expire_period);
		update_post_meta($new_coupon_id, 'free_shipping', 'no');

		return array(
			'promo_code' => $promo_code,
			'expiry_date' => $this->add_days_to_current_date($expire_period),
		);
	}






		/**
     * Registers and logs in a user.
     *
     * @return void
     */
    public function register_login_user_action() {
        //Registration data
        $user_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $user_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $user_phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $forgot_password_page = filter_input(INPUT_POST, 'forgot-password-page', FILTER_SANITIZE_SPECIAL_CHARS);
        $profile_page = filter_input(INPUT_POST, 'profile-page', FILTER_SANITIZE_SPECIAL_CHARS);

        $user = get_user_by('email', $user_email);

        if ($user) {
            //If have user already registered
            wp_send_json_error(__('Такий користувач вже існує.', 'wp-rock'));
        } else {
            // Register new user
            $user_data = $this->register_user($user_email, $user_name, $user_phone);
            if (!$user_data) {
                wp_send_json_error(__('Помилка реєстрації користувача.', 'wp-rock'));
            }

            $result = $this->generate_promocode(180);

            // After registering user send to email generated password
            $send_email = $this->send_registered_info($user_data['user'], $forgot_password_page, $user_data['user_pass'], $result, $profile_page);

             if(!$send_email) {
                 wp_send_json_error('mail send error');
             }

            //Login registered user
            $logged_in = $this->login_user($user_data['user'], $user_data['user_pass']);
            if (is_wp_error($logged_in)) {
                wp_send_json_error($logged_in->get_error_message());
            }


            wp_send_json_success(__('Користувач був зареєстрований', 'wp-rock'));
        }

        wp_send_json_error(__('Щось пішло не так.', 'wp-rock'));
    }

    public function add_update_user_programm($programm_id, $user_id, $days_period, $just_get_information = false) {

		$programm_id = trim($programm_id);
		$user_id = trim($user_id);
		$days_period = 90;


        if ( empty($programm_id) || empty($user_id) || empty($days_period) ) {
            wp_send_json_error(__('Not all data were sent: $days_period ='.$days_period, 'wp-rock'));
        }

        $start_date = new DateTime();
        $expire_date = clone $start_date;
        // Update programm for a N days
        $expire_date->modify('+' . $days_period . ' days');

        $programm_type = get_post_type($programm_id);
        $user_programms_array = get_field($programm_type, 'user_' . $user_id);

        $result = array('success' => false, 'text' => 'Unknown error');

        $existing_programm_key = $this->is_user_have_programm($user_programms_array, $programm_id);

        if ($existing_programm_key !== false) {

			if ( !$just_get_information ) {
				//If programm already existing update access date
				$user_programms_array[$existing_programm_key]['start_access_date'] = $start_date->format('d.m.Y');
				$user_programms_array[$existing_programm_key]['expire_access_date'] = $expire_date->format('d.m.Y');

				// Update the field with the new array
				update_field($programm_type, $user_programms_array, 'user_' . $user_id);
			}

            $result['text'] = __('Доступ до курсу був продовжений.', 'wp-rock');
            $result['success'] = true;
        } else {

			if ( !$just_get_information ) {
				// If programm not exist add it to user
				$new_program_access = array(
					'post_id' => $programm_id,
					'start_access_date' => $start_date->format('d.m.Y'), // Convert DateTime object to string
					'expire_access_date' => $expire_date->format('d.m.Y'), // Convert DateTime object to string
				);
				$user_programms_array[] = $new_program_access;
				// Update the field with the new array
				update_field($programm_type, $user_programms_array, 'user_' . $user_id);
			}

            $result['text'] = __('Курс був доданий до вашого профілю.', 'wp-rock');
            $result['success'] = true;
        }

		if ( !$just_get_information ) {
			$current_user_id = get_current_user_id();
			$user = get_user_by('id', $current_user_id);

			if (!is_wp_error($user)) {
				$this->send_mail_to_admin($user, $programm_id, date('d.m.Y'));
				$this->send_mail_to_user($user, $programm_id, date('d.m.Y'));
			}
		}

        return $result;
    }

    private function send_mail_to_admin($user, $post_id, $current_date) {
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $programm_title = get_the_title($post_id);
        $result = true;

        $body = "<p>" . __('Користувач: ' . $user->first_name . ' - ' . $user->user_email, 'wp-rock') . "</p>
                <p>" . __('Придбав курс - "' . $programm_title . '", дата покупки ' . $current_date, 'wp-rock') . "</p>";


        $email_title = 'TimAgro - User buying proramm';

        // Send email for buying programm by user to all emails in array
        $emails_to_send_programm_sells = get_field_value($this->global_options, 'emails_to_send_programm_sells');
        if (!empty($emails_to_send_programm_sells)) {
            foreach ($emails_to_send_programm_sells as $item) {
                $result = wp_mail($item['email'], $email_title, $body, $headers);
            }
        }

        return $result;
    }

    private function send_mail_to_user($user, $post_id, $current_date) {
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $programm_title = get_the_title($post_id);
        $result = true;

        $body = "<p>" . __('Дякую за вашу покупку. Ви придбали курс - "' . $programm_title . '"', 'wp-rock') . "</p>";


        $email_title = 'TimAgro - Buying proramm';


        $result = wp_mail($user->user_email, $email_title, $body, $headers);


        return $result;
    }


    public function is_user_have_programm($user_programms_array, $programm_id) {
        if (!$user_programms_array)
            return false;

        // Check if the program already exists in the user's array
        foreach ($user_programms_array as $key => $program) {
            if ($program['post_id'] == $programm_id) {
                // If the program already exists
                return $key;
            }
        }

        return false;
    }
    /**
     * Registers a new user.
     *
     * @return array|false Array containing user ID, user object, and user password, or false on failure.
     */
    public function register_user($email, $name, $phone) {
        $userdata = array(
            'user_login' => $email,
            'user_email' => $email,
            'user_pass' => wp_generate_password(),
            'first_name' => $name,
        );

        $user_id = wp_insert_user($userdata);

        if (is_wp_error($user_id)) {
            $error_message = $user_id->get_error_message();
            return false;
        }

        update_field('user_phone', $phone, 'user_' . $user_id);
        update_field('billing_first_name', $name, 'user_' . $user_id);
        update_field('billing_email', $email, 'user_' . $user_id);

        return array(
            'user_id' => $user_id,
            'user' => get_user_by('id', $user_id),
            'user_pass' => $userdata['user_pass'],
        );
    }
    /**
     * Logs in a user.
     *
     * @return WP_User|WP_Error WP_User object on success, WP_Error object on failure.
     */
    public function login_user($user, $password) {

        $credentials = array(
            'user_login' => $user->user_login,
            'user_password' => $password,
        );

        $user = wp_signon($credentials, true);
		if (is_wp_error($user)) {
			// Error handler
			return $user; // Return error
		}
		wp_set_auth_cookie($user->ID);

        return $user;
    }
    /**
     * Retrieves a user by email or username.
     *
     * @return WP_User|false WP_User object if user is found, false otherwise.
     */
    private function get_user_by_email_or_name($username_or_email) {
        if (empty($username_or_email))
            return false;

        if (is_email($username_or_email)) {
            return get_user_by('email', $username_or_email);
        } else {
            return get_user_by('login', $username_or_email);
        }
    }

    public function send_registered_info($user, $forgot_password_page, $generate_pass = '', $promocode = array(), $profile_page = '') {

        if (empty($profile_page) || empty($user) || empty($forgot_password_page) || empty($generate_pass)) {
            return false;
        }

        $hash = md5($user->user_email);
        set_transient('hash_reset_password' . $hash, $user->user_email, 20 * 60);

        $headers = array('Content-Type: text/html; charset=UTF-8');

        $promocode_value = $promocode['promo_code'] ?? '';
        $promocode_expire_date = !empty($promocode['expiry_date']) ? $promocode['expiry_date'] : '';

        $promocode_text = !empty($promocode)
            ? '<p>
                ' . __('Для вас було сгенеровано промокоде на покупку наступного курсу:
                <h2>' . $promocode_value . '</h2>', 'wp-rock') .
            '</p>

                <p>' . __('Промокод одноразовий, строк дії 6 місяців, дійсний до - ' . $promocode_expire_date, 'wp-rock') . '</p>
                '
            : '';

        $body =
            "<p>" . __('Вітаємо: ' . $user->first_name, 'wp-rock') . "</p>
            <p>" . __('Ви успішно зареєструвались.', 'wp-rock') . "</p>
            <p>" . __('Ваш логін для входу: ' . $user->user_email, 'wp-rock') . "</p>
            <p>" . __('Ваш пароль для входу: ' . $generate_pass, 'wp-rock') . "</p>
            </br>
            <p>" . __('Перейти в свій особистий кабінет ви можете за посиланням.
            <a href="' . $profile_page . '">Перейти в особистий кабінет</a>
            ', 'wp-rock') . "</p>
            </br>
            <p>" . __('Для зміни сгенерованого паролю перейдіть за посиланням:', 'wp-rock') . "</p>
                    <p><a href=\"$forgot_password_page?hash_reset_password=$hash\">
                    " . __('Скинути пароль', 'wp-rock') . "</a></p>
                    " . $promocode_text;


        return wp_mail($user->user_email, 'TimAgro - Notification', $body, $headers);
    }


    private function send_reset_password($user, $forgot_password_page) {
        if (empty($user) || empty($forgot_password_page)) {
            return;
        }

        $hash = md5($user->user_email);
        set_transient('hash_reset_password' . $hash, $user->user_email, 20 * 60);

        $headers = array('Content-Type: text/html; charset=UTF-8');

        $body = "<h2>" . __('Ви відправили запит на відновлення паролю:', 'wp-rock') . "</h2>
                    <p>" . __('Для зміни паролю будь ласка перейдіть за посиланням:', 'wp-rock') . "</p>
                    <p><a href=\"$forgot_password_page?hash_reset_password=$hash\">
                        " . __('Скинути пароль', 'wp-rock') . "</a></p>";

        return wp_mail($user->user_email, 'TimAgro - Password reset', $body, $headers);
    }

    public function set_new_password_action() {
        $user_email = filter_input(INPUT_POST, 'user-email', FILTER_SANITIZE_SPECIAL_CHARS);
        $user_new_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

        $user = get_user_by('email', $user_email);

        if (!$user) {
            wp_send_json_error(__('Користувач с таким email не знайдений.', 'wp-rock'));
        }

        wp_set_password($user_new_password, $user->ID);

        wp_send_json_success(__('Пароль оновлено.', 'wp-rock'));
    }

    public function is_programm_access_expire($user_id, $post_id) {
        if (!$post_id && !$user_id) {
            return new WP_Error('No data');
        }

        $start_date = new DateTime();
        $expire_date = clone $start_date;
        $post_type = get_post_type($post_id);
        $user_fields = get_field($post_type, 'user_' . $user_id);

        $curren_post_expire = null;

        if ($user_fields) {
            foreach ($user_fields as $post) {
                if ($post['post_id'] === $post_id) {
                    $curren_post_expire = $post['expire_access_date'];
                }
            }
        }

        if (!$curren_post_expire) {
            return new WP_Error('No post found');
        }

        $expire_date = DateTime::createFromFormat('d.m.Y', $curren_post_expire);

        if (!$expire_date || $start_date->format('Y-m-d') > $expire_date->format('Y-m-d')) {
            return true;
        }

        return false;
    }

    public function load_programm_content($user_id, $vimeo_blocks_folder, $client) {
        $args = array('sort' => 'alphabetical', 'direction' => 'asc');

        $resutl_array = array();

        //API to load video from vimeo
        $blocks_folder = $client->request("/users/$user_id/projects/$vimeo_blocks_folder/items", $args, 'GET');

        if (!empty($blocks_folder['body']['data'])) {
            foreach ($blocks_folder['body']['data'] as $key => $block) {
                if ($block['type'] === 'video' || empty($block['folder']['uri']))
                    continue;

                // Block push
                $resutl_array[] = array(
                    'block_title' => $block['folder']['name'],
                    'videos' => array()
                );

                $videos = $client->request($block['folder']['uri'] . '/videos', $args, 'GET');

                // Videos push
                if (!empty($videos['body']['data'])) {
                    foreach ($videos['body']['data'] as $video) {

                        $clear_video_id = str_replace('/videos/', '', $video['uri']);

                        $resutl_array[$key]['videos'][] = array(
                            'video_id' => $clear_video_id,
                            'video_name' => $video['name'],
                        );
                    }
                }
            }
        }

        return $resutl_array;
    }

    public function set_loaded_data_to_acf($data_array, $post_id) {
        if (empty($data_array)) {
            return array();
        }

        return update_field('field_66b1e23346bc6', $data_array, $post_id);
    }
}
