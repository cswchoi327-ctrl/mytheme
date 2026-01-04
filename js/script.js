/**
 * 워드프레스 지원금 스킨 JavaScript
 */

(function($) {
    'use strict';

    // DOM 준비
    $(document).ready(function() {
        initTabs();
        initExitPopup();
        initSmoothScroll();
    });

    /**
     * 탭 초기화
     */
    function initTabs() {
        const tabs = $('.tab-link');
        const hash = window.location.hash;
        
        if (hash) {
            tabs.removeClass('active');
            tabs.filter('[href="' + hash + '"]').addClass('active');
        }

        tabs.on('click', function(e) {
            const href = $(this).attr('href');
            
            // 내부 링크인 경우에만 탭 전환
            if (href.startsWith('#')) {
                e.preventDefault();
                tabs.removeClass('active');
                $(this).addClass('active');
                window.location.hash = href;
            }
        });
    }

    /**
     * 이탈 방지 팝업
     */
    function initExitPopup() {
        let popupShown = sessionStorage.getItem('exitPopupShown');
        let closeCount = parseInt(sessionStorage.getItem('exitPopupCloseCount')) || 0;
        let scrollTriggered = false;

        // 팝업 HTML 동적 생성
        if ($('#exitPopup').length === 0) {
            const popupHTML = `
                <div class="exit-popup-overlay" id="exitPopup" style="display:none;">
                    <div class="exit-popup">
                        <div class="exit-popup-title">🎁 잠깐! 놓치신 혜택이 있어요</div>
                        <div class="exit-popup-desc">
                            지금 확인 안 하면<br/>
                            <strong>최대 300만원</strong> 지원금을 못 받을 수 있어요!
                        </div>
                        <button class="exit-popup-btn" id="exitPopupConfirm">
                            내 지원금 확인하기 →
                        </button>
                        <button class="exit-popup-close" id="exitPopupClose">
                            다음에 할게요
                        </button>
                    </div>
                </div>
            `;
            $('body').append(popupHTML);
        }

        const $popup = $('#exitPopup');

        // 팝업 표시
        function showPopup() {
            if (closeCount < 2 && !popupShown) {
                $popup.fadeIn(300);
            }
        }

        // 팝업 닫기
        function closePopup() {
            $popup.fadeOut(300);
        }

        // PC: 마우스 이탈 감지
        $(document).on('mouseout', function(e) {
            if (e.clientY < 0) {
                showPopup();
            }
        });

        // 뒤로가기 감지
        history.pushState(null, '', location.href);
        $(window).on('popstate', function() {
            showPopup();
            history.pushState(null, '', location.href);
        });

        // 모바일: 스크롤 60% 도달
        $(window).on('scroll', function() {
            const scrollHeight = $(document).height() - $(window).height();
            const percent = ($(window).scrollTop() / scrollHeight) * 100;
            
            if (percent > 60 && !scrollTriggered) {
                showPopup();
                scrollTriggered = true;
            }
        });

        // 확인 버튼 클릭
        $(document).on('click', '#exitPopupConfirm', function() {
            closePopup();
            $('.hero-section').get(0).scrollIntoView({ behavior: 'smooth' });
        });

        // 닫기 버튼 클릭
        $(document).on('click', '#exitPopupClose', function() {
            closePopup();
            popupShown = true;
            closeCount++;
            sessionStorage.setItem('exitPopupShown', 'true');
            sessionStorage.setItem('exitPopupCloseCount', closeCount);
        });

        // 오버레이 클릭 시 닫기
        $(document).on('click', '#exitPopup', function(e) {
            if (e.target.id === 'exitPopup') {
                $('#exitPopupClose').click();
            }
        });
    }

    /**
     * 부드러운 스크롤
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const href = $(this).attr('href');
            const $target = $(href);
            
            if ($target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $target.offset().top - 100
                }, 500);
            }
        });
    }

    /**
     * 카드 애니메이션
     */
    function initCardAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, { threshold: 0.1 });

        $('.info-card').each(function() {
            observer.observe(this);
        });
    }

    // 페이지 로드 후 애니메이션 초기화
    $(window).on('load', function() {
        initCardAnimations();
    });

})(jQuery);
