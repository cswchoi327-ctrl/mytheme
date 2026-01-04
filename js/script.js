/* ========================================
   GitHub 블로그용 지원금 스킨 JavaScript
   제작: 아백 (아로스)
   생성일: 2026.01.04
======================================== */

// 이탈 방지 팝업 관련 변수
var popupShown = sessionStorage.getItem('exitPopupShown');
var closeCount = parseInt(sessionStorage.getItem('exitPopupCloseCount')) || 0;
var scrollTriggered = false;

// 페이지 로드 시 실행
window.onload = function() {
    
    // PC: 마우스 이탈 감지
    document.onmouseout = function(e) {
        e = e || window.event;
        var y = e.clientY;
        if (y < 0 && !popupShown && closeCount < 2) {
            showPopup();
        }
    };
    
    // PC + 모바일: 뒤로가기 감지 (무조건 팝업)
    history.pushState(null, '', location.href);
    window.onpopstate = function() {
        if (closeCount < 2) {
            showPopup();
        }
        history.pushState(null, '', location.href);
    };
    
    // 모바일: 스크롤 60% 도달 시 팝업
    window.onscroll = function() {
        var h = document.body.scrollHeight - window.innerHeight;
        var percent = (window.scrollY / h) * 100;
        
        if (percent > 60 && !popupShown && !scrollTriggered && closeCount < 2) {
            showPopup();
            scrollTriggered = true;
        }
    };
    
};

// 팝업 표시 함수
function showPopup() {
    var popup = document.getElementById('exitPopup');
    if (popup) {
        popup.style.display = 'flex';
    }
}

// 팝업 닫기 함수
function closePopup() {
    var popup = document.getElementById('exitPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}

// 팝업 닫고 스크롤 함수
function closePopupAndScroll() {
    closePopup();
    var hero = document.querySelector('.hero-section');
    if (hero) {
        hero.scrollIntoView({ behavior: 'smooth' });
    }
}

// 나중에 하기 버튼 함수
function closePopupNotNow() {
    closePopup();
    popupShown = true;
    closeCount++;
    sessionStorage.setItem('exitPopupShown', 'true');
    sessionStorage.setItem('exitPopupCloseCount', closeCount);
}

// 탭 활성화 스크립트
document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.tab-link');
    var hash = window.location.hash;
    var activeTabFound = false;

    tabs.forEach(function(tab) {
        if (hash) {
            if (tab.getAttribute('href') === hash) {
                tabs.forEach(function(t) {
                    t.classList.remove('active');
                });
                tab.classList.add('active');
                activeTabFound = true;
            }
        }
    });

    if (!activeTabFound) {
        var defaultActiveTab = document.querySelector('.tab-link.active');
        if (defaultActiveTab) {
            defaultActiveTab.classList.add('active');
        }
    }
});
