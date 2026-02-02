(function () {
  'use strict';

  // Wait for DOM to load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    // Cache DOM elements
    const timeline = document.querySelector('.js-timeline');
    const timelineLine = document.querySelector('.js-timeline_line');
    const timelineLineProgress = document.querySelector('.js-timeline_line-progress');
    const timelineItems = document.querySelectorAll('.js-timeline_item');
    
    // Check if timeline exists
    if (!timeline || !timelineLine || !timelineLineProgress || timelineItems.length === 0) {
      return;
    }

    // State Variables
    let windowHeight = window.innerHeight;
    let windowOuterHeight = window.outerHeight || window.innerHeight;
    let scrollPos = window.pageYOffset || document.documentElement.scrollTop;
    let lastScrollPos = -1;
    let rafPending = false;

    // Event Listener with Throttling
    window.addEventListener('scroll', handleScroll, { passive: true });
    window.addEventListener('resize', debounce(handleResize, 150));

    // Initial Update
    updateFrame();

    function handleScroll() {
      scrollPos = window.pageYOffset || document.documentElement.scrollTop;
      updateFrame();
    }

    function handleResize() {
      scrollPos = window.pageYOffset || document.documentElement.scrollTop;
      windowHeight = window.innerHeight;
      windowOuterHeight = window.outerHeight || window.innerHeight;
      updateFrame();
    }

    function updateFrame() {
      if (rafPending) return;
      
      rafPending = true;
      requestAnimationFrame(updateWindow);
    }

    function updateWindow() {
      rafPending = false;

      // Place timeline linie
      const firstItem = timelineItems[0];
      const lastItem = timelineItems[timelineItems.length - 1];
      
      const firstPointBox = firstItem.querySelector('.js-timeline-card_point-box');
      const lastPointBox = lastItem.querySelector('.js-timeline-card_point-box');
      
      if (!firstPointBox || !lastPointBox) return;

      const firstPointTop = getOffsetTop(firstPointBox);
      const firstItemTop = getOffsetTop(firstItem);
      const lastPointTop = getOffsetTop(lastPointBox);
      const timelineTop = getOffsetTop(timeline);
      const timelineBottom = timelineTop + timeline.offsetHeight;

      timelineLine.style.top = (firstPointTop - firstItemTop) + 'px';
      timelineLine.style.bottom = (timelineBottom - lastPointTop) + 'px';

      // update progress only when scroll position has changed
      if (lastScrollPos !== scrollPos) {
        lastScrollPos = scrollPos;
        updateProgress();
      }
    }

    function updateProgress() {
      const lastItem = timelineItems[timelineItems.length - 1];
      const lastPointBox = lastItem.querySelector('.js-timeline-card_point-box');
      
      if (!lastPointBox) return;

      const lastPointTop = getOffsetTop(lastPointBox);
      const progressTop = getOffsetTop(timelineLineProgress);
      const viewportCenter = scrollPos + windowOuterHeight / 2;
      
      // calculate progress height
      let progressHeight = viewportCenter - progressTop;
      const maxProgress = lastPointTop - progressTop;
      
      if (progressHeight > maxProgress) {
        progressHeight = maxProgress;
      }

      timelineLineProgress.style.height = progressHeight + 'px';

      // activate/deactivate items
      timelineItems.forEach(function (item) {
        const pointBox = item.querySelector('.js-timeline-card_point-box');
        if (!pointBox) return;
        
        const pointTop = getOffsetTop(pointBox);
        
        if (pointTop < viewportCenter) {
          item.classList.add('js-jem-active');
        } else {
          item.classList.remove('js-jem-active');
        }
      });
    }

    // helper function: calculate offset from top
    function getOffsetTop(element) {
      let offsetTop = 0;
      while (element) {
        offsetTop += element.offsetTop;
        element = element.offsetParent;
      }
      return offsetTop;
    }

    // debounce helper function
    function debounce(func, wait) {
      let timeout;
      return function executedFunction() {
        const later = () => {
          clearTimeout(timeout);
          func();
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }
  }
})();
