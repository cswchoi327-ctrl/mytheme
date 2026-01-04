<?php
/**
 * Theme Name: jiwungumonlyskin
 * Theme URI: https://aros100.com
 * Description: 지원금 전용 스킨 - GitHub 블로그 호환 테마
 * Version: 1.0.0
 * Author: 아로스 (아백)
 * Author URI: https://aros100.com
 * Generated: 2026.01.04
 */

// 보안: 직접 접근 차단
if (!defined('ABSPATH')) {
    exit;
}

// 테마 설정
function support_fund_theme_setup() {
    // HTML5 지원
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // 타이틀 태그 지원
    add_theme_support('title-tag');
    
    // 포스트 썸네일 지원
    add_theme_support('post-thumbnails');
    
    // RSS 피드 링크
    add_theme_support('automatic-feed-links');
}
add_action('after_setup_theme', 'support_fund_theme_setup');

// CSS 및 JS 로드
function support_fund_enqueue_scripts() {
    // CSS 로드
    wp_enqueue_style(
        'support-fund-style',
        get_stylesheet_uri(),
        array(),
        '1.0.0'
    );
    
    // Google Fonts
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap',
        array(),
        null
    );
    
    // JavaScript 로드
    wp_enqueue_script(
        'support-fund-script',
        get_template_directory_uri() . '/script.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'support_fund_enqueue_scripts');

// 관리자 CSS 제거 (프론트엔드 스타일 유지)
function support_fund_remove_admin_bar_css() {
    remove_action('wp_head', 'wp_admin_bar_header');
}
add_action('get_header', 'support_fund_remove_admin_bar_css');

// body 클래스 추가
function support_fund_body_classes($classes) {
    $classes[] = 'support-fund-theme';
    return $classes;
}
add_filter('body_class', 'support_fund_body_classes');

// 광고 코드 단축코드 지원
function support_fund_ad_shortcode($atts) {
    $atts = shortcode_atts(array(
        'code' => '',
    ), $atts);
    
    if (empty($atts['code'])) {
        return '';
    }
    
    return '<div class="ad-card"><div style="display:flex; justify-content:center; width:100%;">' . 
           do_shortcode($atts['code']) . 
           '</div></div>';
}
add_shortcode('ad', 'support_fund_ad_shortcode');

// 이탈 방지 팝업 설정 (커스터마이저)
function support_fund_customize_register($wp_customize) {
    // 팝업 섹션 추가
    $wp_customize->add_section('support_fund_popup', array(
        'title' => '이탈 방지 팝업 설정',
        'priority' => 30,
    ));
    
    // 팝업 활성화
    $wp_customize->add_setting('popup_enabled', array(
        'default' => true,
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('popup_enabled', array(
        'label' => '팝업 활성화',
        'section' => 'support_fund_popup',
        'type' => 'checkbox',
    ));
    
    // 팝업 제목
    $wp_customize->add_setting('popup_title', array(
        'default' => '🎁 잠깐! 놓치신 혜택이 있어요',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('popup_title', array(
        'label' => '팝업 제목',
        'section' => 'support_fund_popup',
        'type' => 'text',
    ));
    
    // 팝업 내용
    $wp_customize->add_setting('popup_desc', array(
        'default' => '지금 확인 안 하면<br><strong>최대 300만원</strong> 지원금을 못 받을 수 있어요!',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('popup_desc', array(
        'label' => '팝업 설명',
        'section' => 'support_fund_popup',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'support_fund_customize_register');

// 팝업 데이터 JavaScript에 전달
function support_fund_popup_data() {
    if (!get_theme_mod('popup_enabled', true)) {
        return;
    }
    
    $popup_title = get_theme_mod('popup_title', '🎁 잠깐! 놓치신 혜택이 있어요');
    $popup_desc = get_theme_mod('popup_desc', '지금 확인 안 하면<br><strong>최대 300만원</strong> 지원금을 못 받을 수 있어요!');
    
    echo '<script>';
    echo 'var supportFundPopupData = {';
    echo 'enabled: true,';
    echo 'title: ' . json_encode($popup_title) . ',';
    echo 'desc: ' . json_encode($popup_desc);
    echo '};';
    echo '</script>';
}
add_action('wp_head', 'support_fund_popup_data');

// 커스텀 포스트 타입: 지원금 카드
function support_fund_register_card_post_type() {
    register_post_type('support_card', array(
        'labels' => array(
            'name' => '지원금 카드',
            'singular_name' => '카드',
            'add_new' => '새 카드 추가',
            'add_new_item' => '새 카드 추가',
            'edit_item' => '카드 편집',
            'new_item' => '새 카드',
            'view_item' => '카드 보기',
            'search_items' => '카드 검색',
            'not_found' => '카드가 없습니다',
            'not_found_in_trash' => '휴지통에 카드가 없습니다'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-money-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'support_fund_register_card_post_type');

// 카드 메타박스
function support_fund_add_card_metaboxes() {
    add_meta_box(
        'support_card_details',
        '카드 상세 정보',
        'support_fund_card_metabox_callback',
        'support_card',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'support_fund_add_card_metaboxes');

function support_fund_card_metabox_callback($post) {
    wp_nonce_field('support_fund_save_card_meta', 'support_fund_card_nonce');
    
    $amount = get_post_meta($post->ID, '_card_amount', true);
    $amount_sub = get_post_meta($post->ID, '_card_amount_sub', true);
    $target = get_post_meta($post->ID, '_card_target', true);
    $period = get_post_meta($post->ID, '_card_period', true);
    $link = get_post_meta($post->ID, '_card_link', true);
    $featured = get_post_meta($post->ID, '_card_featured', true);
    
    ?>
    <p>
        <label><strong>금액/혜택:</strong></label><br>
        <input type="text" name="card_amount" value="<?php echo esc_attr($amount); ?>" style="width:100%;" placeholder="예: 최대 4.5% 금리">
    </p>
    <p>
        <label><strong>부가 설명:</strong></label><br>
        <input type="text" name="card_amount_sub" value="<?php echo esc_attr($amount_sub); ?>" style="width:100%;" placeholder="예: 비과세 + 대출 우대">
    </p>
    <p>
        <label><strong>지원대상:</strong></label><br>
        <input type="text" name="card_target" value="<?php echo esc_attr($target); ?>" style="width:100%;" placeholder="예: 만 19~34세 청년" maxlength="20">
    </p>
    <p>
        <label><strong>신청시기:</strong></label><br>
        <input type="text" name="card_period" value="<?php echo esc_attr($period); ?>" style="width:100%;" placeholder="예: 상시">
    </p>
    <p>
        <label><strong>링크 URL:</strong></label><br>
        <input type="url" name="card_link" value="<?php echo esc_attr($link); ?>" style="width:100%;" placeholder="https://example.com">
    </p>
    <p>
        <label>
            <input type="checkbox" name="card_featured" value="1" <?php checked($featured, '1'); ?>>
            <strong>인기 카드로 표시</strong>
        </label>
    </p>
    <?php
}

function support_fund_save_card_meta($post_id) {
    if (!isset($_POST['support_fund_card_nonce']) || 
        !wp_verify_nonce($_POST['support_fund_card_nonce'], 'support_fund_save_card_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['card_amount'])) {
        update_post_meta($post_id, '_card_amount', sanitize_text_field($_POST['card_amount']));
    }
    
    if (isset($_POST['card_amount_sub'])) {
        update_post_meta($post_id, '_card_amount_sub', sanitize_text_field($_POST['card_amount_sub']));
    }
    
    if (isset($_POST['card_target'])) {
        update_post_meta($post_id, '_card_target', sanitize_text_field($_POST['card_target']));
    }
    
    if (isset($_POST['card_period'])) {
        update_post_meta($post_id, '_card_period', sanitize_text_field($_POST['card_period']));
    }
    
    if (isset($_POST['card_link'])) {
        update_post_meta($post_id, '_card_link', esc_url_raw($_POST['card_link']));
    }
    
    update_post_meta($post_id, '_card_featured', isset($_POST['card_featured']) ? '1' : '0');
}
add_action('save_post_support_card', 'support_fund_save_card_meta');

// 카드 출력 함수
function support_fund_display_cards($limit = -1) {
    $args = array(
        'post_type' => 'support_card',
        'posts_per_page' => $limit,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    
    $cards = new WP_Query($args);
    
    if ($cards->have_posts()) {
        echo '<div class="info-card-grid">';
        
        while ($cards->have_posts()) {
            $cards->the_post();
            
            $amount = get_post_meta(get_the_ID(), '_card_amount', true);
            $amount_sub = get_post_meta(get_the_ID(), '_card_amount_sub', true);
            $target = get_post_meta(get_the_ID(), '_card_target', true);
            $period = get_post_meta(get_the_ID(), '_card_period', true);
            $link = get_post_meta(get_the_ID(), '_card_link', true);
            $featured = get_post_meta(get_the_ID(), '_card_featured', true);
            
            $featured_class = ($featured == '1') ? ' featured' : '';
            $badge = ($featured == '1') ? '<span class="info-card-badge">🔥 인기</span>' : '';
            
            ?>
            <a class="info-card<?php echo $featured_class; ?>" href="<?php echo esc_url($link); ?>">
                <div class="info-card-highlight">
                    <?php echo $badge; ?>
                    <div class="info-card-amount"><?php echo esc_html($amount); ?></div>
                    <div class="info-card-amount-sub"><?php echo esc_html($amount_sub); ?></div>
                </div>
                <div class="info-card-content">
                    <h3 class="info-card-title"><?php the_title(); ?></h3>
                    <p class="info-card-desc"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <div class="info-card-details">
                        <div class="info-card-row">
                            <span class="info-card-label">지원대상</span>
                            <span class="info-card-value"><?php echo esc_html($target); ?></span>
                        </div>
                        <div class="info-card-row">
                            <span class="info-card-label">신청시기</span>
                            <span class="info-card-value"><?php echo esc_html($period); ?></span>
                        </div>
                    </div>
                    <div class="info-card-btn">
                        지금 바로 신청하기 <span class="btn-arrow">→</span>
                    </div>
                </div>
            </a>
            <?php
        }
        
        echo '</div>';
        wp_reset_postdata();
    }
}

// 단축코드로 카드 출력
function support_fund_cards_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => -1,
    ), $atts);
    
    ob_start();
    support_fund_display_cards($atts['limit']);
    return ob_start();
}
add_shortcode('support_cards', 'support_fund_cards_shortcode');

// 관리자 알림
function support_fund_admin_notice() {
    $screen = get_current_screen();
    if ($screen->id !== 'themes') {
        return;
    }
    ?>
    <div class="notice notice-success is-dismissible">
        <p><strong>지원금 스킨</strong>이 활성화되었습니다! 외모 → 사용자 정의하기에서 추가 설정이 가능합니다.</p>
        <p>제작: <a href="https://aros100.com" target="_blank">아백 (아로스)</a></p>
    </div>
    <?php
}
add_action('admin_notices', 'support_fund_admin_notice');

// 테마 활성화 시 실행
function support_fund_theme_activation() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'support_fund_theme_activation');

// 보안 헤더
function support_fund_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
}
add_action('send_headers', 'support_fund_security_headers');

?>
