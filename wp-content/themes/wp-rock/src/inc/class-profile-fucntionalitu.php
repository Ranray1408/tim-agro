<?php

/**
 * Class profile_functionality
 *
 * Handles various user profile functionalities.
 */
class profile_functionality {
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
        add_action('wp_ajax_add_programm_to_user', array($this, 'add_programm_to_user_action'));

        // Add programm access to user
        add_action('wp_ajax_set_new_password', array($this, 'set_new_password_action'));
        add_action('wp_ajax_nopriv_set_new_password', array($this, 'set_new_password_action'));

        add_action('wp_ajax_nopriv_FAKE_PAY_SYSTEM', array($this, 'FAKE_PAY_SYSTEM'));
        add_action('wp_ajax_FAKE_PAY_SYSTEM', array($this, 'FAKE_PAY_SYSTEM'));
    }
    /**
     * Adds JSON video data.
     *
     * @return void
     */
    public function add_json_video_data_action() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data)) {
            wp_send_json_error('Немає необхідних даних.');
        }

        if (empty($data['userId'])) {
            wp_send_json_error('Немає ідентифікатора користувача.');
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
     * @return void
     */
    private function update_user_programm($data, $user_id) {
        if (empty($data)) return false;

        $user_programm = get_field($data['programmType'], 'user_' . $user_id);

        if (!empty($user_programm)) {
            foreach ($user_programm as $key => $item) {
                if (empty($item['post_id'])) continue;

                $current_block = $data['programms']['programm-' . $item['post_id']];
                if (!empty($current_block)) {
                    $user_programm[$key]['programm_data'] = json_encode($current_block);
                }
            }

            return update_field($data['programmType'], $user_programm, 'user_' . $user_id);
        }
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

        $updated = wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $name,
            'user_email' => $email,
        ));

        if (is_wp_error($updated)) {
            wp_send_json_error('Помилка оновлення даних користувача.');
        }


        if (!empty($file)) {
            $avatar_id = media_handle_upload('avatar', 0);
            if (is_wp_error($avatar_id)) {
                wp_send_json_error('Помилка завантаження аватара.');
            }
            update_field('user_avatar', $avatar_id, 'user_' . $user_id);
        }

        $user_phone = update_field('user_phone', $phone, 'user_' . $user_id);

        if (is_wp_error($user_phone)) {
            wp_send_json_error('Помилка оновлення телефону.');
        }

        wp_send_json_success('Дані успішно оновлено.');
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
            wp_send_json_error('Будь ласка, введіть ім\'я користувача та пароль.');
        }

        $user = $this->get_user_by_email_or_name($username_or_email);

        if (is_wp_error($user)) {
            wp_send_json_error('Не вдається знайти користувача за електронною поштою чи іменем.');
        }

        $user_logined = $this->login_user($user, $password);

        if (is_wp_error($user_logined)) {
            wp_send_json_error('Невірний логін або пароль.');
        }

        wp_send_json_success('Вхід виповнено.');
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
                wp_send_json_error('Користувач з даним email не знайдений.');
            } else {
                $email_sent = $this->send_reset_user_password($user, $forgot_password_page);

                if ($email_sent) {
                    wp_send_json_success('Посилання для скидання пароля відправлено на ваш email.');
                } else {
                    wp_send_json_error('Сталася помилка під час відправлення email. Будь ласка, спробуйте ще раз.');
                }
            }
        }
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
        $forgot_password_page = filter_input(INPUT_POST, 'forgot_password_page', FILTER_SANITIZE_SPECIAL_CHARS);

        $program_id = filter_input(INPUT_POST, 'program_id', FILTER_VALIDATE_INT);
        $user = get_user_by('email', $user_email);

        if ($user) {
            //If have user login it
            wp_send_json_error('Такий користувач вже існує.');
        } else {
            // Register new user
            $user_data = $this->register_user($user_email, $user_name, $user_phone);
            if (!$user_data) {
                wp_send_json_error('Помилка реєстрації користувача.');
            }

            // After registering user send to email generated password
            $email_sent = $this->send_reset_user_password($user_data['user'], $forgot_password_page, $user_data['user_pass']);

            if (!$email_sent) {
                wp_send_json_error('Електронний лист не було надіслано.');
            }

            //Login registered user
            $logged_in = $this->login_user($user_data['user'], $user_data['user_pass']);
            if (is_wp_error($logged_in)) {
                wp_send_json_error($logged_in->get_error_message());
            }

            $result = $this->add_update_user_programm($program_id, $user_data['user']->ID);

            if ($result == false) {
                wp_send_json_error('Програма не була додана в профіль.');
            }
        }

        wp_send_json_error('Щось пішло не так.');
    }


    public function add_programm_to_user_action() {
        $user_id = filter_input(INPUT_POST, 'user-id', FILTER_VALIDATE_INT);
        $post_id = filter_input(INPUT_POST, 'post-id', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$post_id || !$user_id) {
            wp_send_json_error('Деякі дані порожні.');
        }

        $result = $this->add_update_user_programm($post_id, $user_id);

        if (!$result) {
            wp_send_json_error('Щось пішло не так зверніться до адміністратора.');
        }
    }

    private function add_update_user_programm($programm_id, $user_id) {
        if (!$programm_id || !$user_id) {
            wp_send_json_error('Some data is empty');
        }

        $start_date = new DateTime();
        $expire_date = clone $start_date;
        // Update programm for a 90 days
        $expire_date->modify('+90 days');

        $programm_type = get_post_type($programm_id);
        $user_programms_array = get_field($programm_type, 'user_' . $user_id);


        $existing_programm_key = $this->is_programm_exist($user_programms_array, $programm_id);

        if ($existing_programm_key !== false) {
            //If programm already existing update access date
            $user_programms_array[$existing_programm_key]['start_access_date'] = $start_date->format('d.m.Y');
            $user_programms_array[$existing_programm_key]['expire_access_date'] = $expire_date->format('d.m.Y');

            // Update the field with the new array
            update_field($programm_type, $user_programms_array, 'user_' . $user_id);
            wp_send_json_success('Доступ до курсу був продовжений.');
        } else {
            // If programm not exist add it to user
            $new_program_access = array(
                'post_id' => $programm_id,
                'start_access_date' => $start_date->format('d.m.Y'), // Convert DateTime object to string
                'expire_access_date' => $expire_date->format('d.m.Y'), // Convert DateTime object to string
            );
            $user_programms_array[] = $new_program_access;
            // Update the field with the new array
            update_field($programm_type, $user_programms_array, 'user_' . $user_id);
            wp_send_json_success('Курс був доданий до вашого профілю.');
        }
    }


    private function is_programm_exist($user_programms_array, $programm_id) {
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
    private function register_user($email, $name, $phone) {
        $userdata = array(
            'user_login' => $name,
            'user_email' => $email,
            'user_pass' => wp_generate_password(),
            'first_name' => $name,
        );

        $user_id = wp_insert_user($userdata);

        if (is_wp_error($user_id)) {
            return false;
        }

        update_field('user_phone', $phone, 'user_' . $user_id);

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
    private function login_user($user, $password) {

        $credentials = array(
            'user_login' => $user->user_login,
            'user_password' => $password,
        );

        $user = wp_signon($credentials, true);

        return $user;
    }
    /**
     * Retrieves a user by email or username.
     *
     * @return WP_User|false WP_User object if user is found, false otherwise.
     */
    private function get_user_by_email_or_name($username_or_email) {
        if (empty($username_or_email)) return false;

        if (is_email($username_or_email)) {
            return get_user_by('email', $username_or_email);
        } else {
            return get_user_by('login', $username_or_email);
        }

        return false;
    }

    private function send_reset_user_password($user, $forgot_password_page, $generate_pass = '') {
        if (empty($user) || empty($forgot_password_page)) return;

        $hash = md5($user->user_email);
        set_transient('hash_reset_password' . $hash, $user->user_email, 20 * 60);

        $headers = array('Content-Type: text/html; charset=UTF-8');

        $body    = "<h2>Ви відправили запит на відновлення паролю:</h2>
                    <p>Для зміни паролю будь ласка перейдіть за посиланням:</p>
                    <p><a href=\"$forgot_password_page?hash_reset_password=$hash\">Скинути пароль</a></p>";

        if(!empty($generate_pass)) {
            $body    = "<h2>Вам було надано автоматично сгенерований пароль: " . $generate_pass . "</h2>
                        <p>Для зміни сгенерованого паролю перейдіть за посиланням:</p>
                        <p><a href=\"$forgot_password_page?hash_reset_password=$hash\">Скинути пароль</a></p>";
        }


        return wp_mail($user->user_email, 'Forgot Password', $body, $headers);
    }


    public function set_new_password_action() {
        $user_email = filter_input(INPUT_POST, 'user-email', FILTER_SANITIZE_SPECIAL_CHARS);
        $user_new_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

        $user = get_user_by('email', $user_email);

        if (!$user) {
            wp_send_json_error('Користувач с таким email не знайдений.');
        }

        wp_set_password($user_new_password, $user->ID);

        wp_send_json_success('Пароль оновлено.');
    }

    public function FAKE_PAY_SYSTEM() {
        wp_send_json_success('Pay was successful');
    }
}
