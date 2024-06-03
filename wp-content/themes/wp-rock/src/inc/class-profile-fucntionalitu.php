<?php
class profiel_functionality {
    public $property1;

    public function init() {
        $this->addActions();
    }

    private function addActions() {
        // Code goes here
        add_action('wp_ajax_save_video_data', array($this, 'add_json_video_data'));
        add_action('wp_ajax_update_user_data', array($this, 'update_user_data'));
    }

    public function add_json_video_data() {
        // Code goes here
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['programmType']) || empty($data)) {
            wp_send_json_error('No requred data');
        }

        if (empty($data['userId'])) {
            wp_send_json_error('No user id');
        }

        $user_programm = get_field($data['programmType'], 'user_' . $data['userId']);

        if (!empty($user_programm)) {
            foreach ($user_programm as $key => $item) {
                if (empty($item['post_id'])) continue;

                $current_block = $data['programms']['programm-' . $item['post_id']];
                if (!empty($current_block)) {
                    $user_programm[$key]['programm_data'] = json_encode($current_block);
                }
            }

            $result = update_field($data['programmType'], $user_programm, 'user_' . $data['userId']);

            if ($result) {
                wp_send_json_success($result);
            }
        }
    }

    public function update_user_data() {
        $nickname = $_POST['nickname'];
        $user_id = $_POST['user_id'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $user_programm = update_field($phone, 'user_' . $user_id);

        $updated = wp_update_user(array(
            'ID' => $user_id,
            'user_nicename' => $nickname,
            'user_email' => $email,
        ));


        if (is_wp_error($updated)) {
            wp_send_json_error('Somethink went wrong');
        }
        if($updated && $user_programm) {
            wp_send_json_success('Data updated successful');
        } else if($updated && $user_programm) {
            wp_send_json_success('Data updated successful, phone was not updated');
        }
    }
}
