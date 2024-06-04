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

        if (empty($data)) {
            wp_send_json_error('No requred data');
        }

        if (empty($data['userId'])) {
            wp_send_json_error('No user id');
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

    private function update_user_programm($data, $user_id) {
        if(empty($data)) return false;

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

    public function update_user_data() {
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $file = $_POST['avatar'];

        $updated = wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $name,
            'user_email' => $email,
        ));

        if (is_wp_error($updated)) {
            wp_send_json_error('Error updating user data');
        }


        if (!empty($file)) {
            $avatar_id = media_handle_upload('avatar', 0);
            if (is_wp_error($avatar_id)) {
                wp_send_json_error('Error uploading avatar');
            }
            update_field('user_avatar', $avatar_id, 'user_' . $user_id);
        }

        $user_phone = update_field('user_phone', $phone, 'user_' . $user_id);

        if (is_wp_error($user_phone)) {
            wp_send_json_error('Error updating phone');
        }

        wp_send_json_success('Data updated successfully');
    }
}
