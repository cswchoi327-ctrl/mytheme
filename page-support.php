<?php
/**
 * Template Name: 지원금 메인 페이지 (관리자 편집 가능)
 * Description: 워드프레스 관리자에서 카드를 직접 추가/편집할 수 있는 템플릿
 */

get_header();

// 메인 URL
$main_url = get_option('support_main_url', 'https://index1.jiwungum100.qzz.io');

// 탭 데이터
$tabs = get_option('support_tabs', [
    ['name' => '전국민 지원금', 'link' => 'https://index1.jiwungum100.qzz.io', 'active' => true],
    ['name' => '청년지원금 ', 'link' => 'https://index1.jiwungum100.qzz.io', 'active' => false],
    ['name' => '소상공인 지원금', 'link' => 'https://index1.jiwungum100.qzz.io', 'active' => false]
]);

// 광고 코드
$ad_code = get_option('support_ad_code', '');
$ad_platform = get_option('support_ad_platform', 'adsense');

// 커스텀 포스트 타입에서 카드 데이터 가져오기
$cards_query = new WP_Query([
    'post_type' => 'support_card',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC'
]);

?>

<div class="support-main-wrapper">
    <div class="support-container">
        
        <!-- 탭 네비게이션 -->
        <?php if (!empty($tabs)): ?>
        <div class="tab-wrapper">
            <div class="support-container">
                <nav class="tab-container">
                    <ul class="tabs">
                        <?php foreach ($tabs as $tab): ?>
                        <li class="tab-item">
                            <a class="tab-link <?php echo $tab['active'] ? 'active' : ''; ?>" 
                               href="<?php echo esc_url($tab['link']); ?>">
                                <?php echo esc_html($tab['name']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>

        <!-- 상단 인트로 -->
        <div class="intro-section">
            <span class="intro-badge">신청마감 D-3일</span>
            <p class="intro-sub">숨은 보험금 1분만에 찾기!</p>
            <h2 class="intro-title">숨은 지원금 찾기</h2>
        </div>

        <!-- 광고 영역 -->
        <?php if (!empty($ad_code)): ?>
        <div class="ad-container">
            <?php echo $ad_code; ?>
        </div>
        <?php endif; ?>

        <!-- 정보 박스 -->
        <div class="info-box">
            <div class="info-box-header">
                <span class="info-box-icon">🏷️</span>
                <span class="info-box-title">신청 안하면 절대 못 받아요</span>
            </div>
            <div class="info-box-amount">1인 평균 127만원 환급</div>
            <p class="info-box-desc">대한민국 92%가 놓치고 있는 정부 지원금! 지금 확인하고 혜택 놓치지 마세요.</p>
        </div>

        <!-- 카드 그리드 -->
        <div class="info-card-grid">
            <?php 
            $card_count = 0;
            if ($cards_query->have_posts()): 
                while ($cards_query->have_posts()): 
                    $cards_query->the_post();
                    
                    // 광고 삽입 (3번째 카드마다)
                    if (!empty($ad_code) && $card_count > 0 && $card_count % 3 === 0):
            ?>
                        <div class="ad-container">
                            <?php echo $ad_code; ?>
                        </div>
            <?php 
                    endif;
                    $card_count++;
                    
                    // 메타 데이터 가져오기
                    $amount = get_post_meta(get_the_ID(), '_card_amount', true);
                    $amount_sub = get_post_meta(get_the_ID(), '_card_amount_sub', true);
                    $target = get_post_meta(get_the_ID(), '_card_target', true);
                    $period = get_post_meta(get_the_ID(), '_card_period', true);
                    $link = get_post_meta(get_the_ID(), '_card_link', true) ?: $main_url;
                    $is_featured = get_post_meta(get_the_ID(), '_card_featured', true);
            ?>
            
            <a class="info-card <?php echo $is_featured ? 'featured' : ''; ?>" 
               href="<?php echo esc_url($link); ?>">
                <div class="info-card-highlight">
                    <?php if ($is_featured): ?>
                        <span class="info-card-badge">🔥 인기</span>
                    <?php endif; ?>
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
                endwhile;
                wp_reset_postdata();
            endif; 
            ?>
        </div>

        <!-- 히어로 섹션 -->
        <div class="hero-section">
            <div class="hero-content">
                <span class="hero-urgent">🔥 신청마감 D-3일</span>
                <p class="hero-sub">숨은 지원금 1분만에 찾기!</p>
                <h2 class="hero-title">
                    나의 <span class="hero-highlight">숨은 지원금</span> 찾기
                </h2>
                <p class="hero-amount">신청자 <strong>1인 평균 127만원</strong> 수령</p>
                <a class="hero-cta" href="<?php echo esc_url($main_url); ?>">
                    30초만에 내 지원금 확인 <span class="cta-arrow">→</span>
                </a>
                <div class="hero-trust">
                    <span class="trust-item">✓ 무료 조회</span>
                    <span class="trust-item">✓ 30초 완료</span>
                    <span class="trust-item">✓ 개인정보 보호</span>
                </div>
                <div class="hero-notice">
                    <div class="notice-content">
                        <div class="notice-title">💡신청 안하면 못 받아요</div>
                        <p class="notice-desc">대한민국 92%가 놓치고 있는 정부 지원금, 지금 확인하고 혜택 놓치지 마세요!</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>
