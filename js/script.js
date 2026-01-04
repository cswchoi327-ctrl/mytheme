/**
 * 지원금 스킨 JavaScript
 * Vanilla JS (jQuery 불필요)
 */

(function() {
    'use strict';

    // DOM 로드 완료 후 실행
    document.addEventListener('DOMContentLoaded', function() {
        initTabs();
        initExitPopup();
        initSmoothScroll();
        initCardAnimations();
    });

    /**
     * 탭 초기화
     */
    function initTabs() {
        const tabs = document.querySelectorAll('.tab-link');
        const hash = window.location.hash;
        
        if (hash) {
            tabs.forEach(tab => {
                tab.classList.remove('active');
                if (tab.getAttribute('href') === hash) {
                    tab.classList.add('active');
                }
            });
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                // 내부 링크인 경우에만 탭 전환
                if (href.startsWith('#')) {
                    e.preventDefault();
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    window.location.hash = href;
                }
            });
        });
    }

    /**
     * 이탈 방지 팝업
     */
    function initExitPopup() {
        let popupShown = sessionStorage.getItem('exitPopupShown');
        let closeCount = parseInt(sessionStorage.getItem('exitPopupCloseCount')) || 0;
        let scrollTriggered = false;

        const popup = document.getElementById('exitPopup');
        if (!popup) return;

        // 팝업 표시
        function showPopup() {
            if (closeCount < 2 && !popupShown) {
                popup.classList.add('show');
            }
        }

        // 팝업 닫기
        function closePopup() {
            popup.classList.remove('show');
        }

        // PC: 마우스 이탈 감지
        document.addEventListener('mouseout', function(e) {
            if (e.clientY < 0) {
                showPopup();
            }
        });

        // 뒤로가기 감지
        history.pushState(null, '', location.href);
        window.addEventListener('popstate', function() {
            showPopup();
            history.pushState(null, '', location.href);
        });

        // 모바일: 스크롤 60% 도달
        window.addEventListener('scroll', function() {
            const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
            const percent = (window.scrollY / scrollHeight) * 100;
            
            if (percent > 60 && !scrollTriggered) {
                showPopup();
                scrollTriggered = true;
            }
        });

        // 전역 함수로 노출 (HTML onclick에서 사용)
        window.closePopupAndScroll = function() {
            closePopup();
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.scrollIntoView({ behavior: 'smooth' });
            }
        };

        window.closePopupNotNow = function() {
            closePopup();
            popupShown = true;
            closeCount++;
            sessionStorage.setItem('exitPopupShown', 'true');
            sessionStorage.setItem('exitPopupCloseCount', closeCount);
        };

        // 오버레이 클릭 시 닫기
        popup.addEventListener('click', function(e) {
            if (e.target.id === 'exitPopup') {
                window.closePopupNotNow();
            }
        });
    }

    /**
     * 부드러운 스크롤
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const target = document.querySelector(href);
                
                if (target) {
                    e.preventDefault();
                    const offsetTop = target.offsetTop - 100;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * 카드 애니메이션
     */
    function initCardAnimations() {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.info-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(card);
            });
        }
    }

})();
