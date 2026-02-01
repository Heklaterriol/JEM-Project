(function ($) {
  'use strict';

  $(function () {
    // Cache DOM-Elemente
    const $window = $(window);
    const $timeline = $('.js-timeline');
    const $timelineLine = $('.js-timeline_line');
    const $timelineLineProgress = $('.js-timeline_line-progress');
    const $timelinePoint = $('.js-timeline-card_point-box');
    const $timelineItems = $('.js-timeline_item');
    
    // State-Variablen
    let windowHeight = $window.height();
    let windowOuterHeight = $window.outerHeight();
    let scrollPos = $window.scrollTop();
    let lastScrollPos = -1;
    let rafPending = false;

    // Event-Listener mit Throttling
    $window.on('scroll', handleScroll);
    $window.on('resize', debounce(handleResize, 150));

    // Initial-Update
    updateFrame();

    function handleScroll() {
      scrollPos = $window.scrollTop();
      updateFrame();
    }

    function handleResize() {
      scrollPos = $window.scrollTop();
      windowHeight = $window.height();
      windowOuterHeight = $window.outerHeight();
      updateFrame();
    }

    function updateFrame() {
      if (rafPending) return;
      
      rafPending = true;
      requestAnimationFrame(updateWindow);
    }

    function updateWindow() {
      rafPending = false;

      // Timeline-Linie positionieren
      const firstPointTop = $timelineItems.first().find($timelinePoint).offset().top;
      const firstItemTop = $timelineItems.first().offset().top;
      const lastPointTop = $timelineItems.last().find($timelinePoint).offset().top;
      const timelineTop = $timeline.offset().top;
      const timelineBottom = timelineTop + $timeline.outerHeight();

      $timelineLine.css({
        top: firstPointTop - firstItemTop,
        bottom: timelineBottom - lastPointTop
      });

      // Progress nur updaten wenn sich Scroll-Position geändert hat
      if (lastScrollPos !== scrollPos) {
        lastScrollPos = scrollPos;
        updateProgress();
      }
    }

    function updateProgress() {
      const lastPointTop = $timelineItems.last().find($timelinePoint).offset().top;
      const progressTop = $timelineLineProgress.offset().top;
      const viewportCenter = scrollPos + windowOuterHeight / 2;
      
      // Progress-Höhe berechnen
      let progressHeight = viewportCenter - progressTop;
      const maxProgress = lastPointTop - progressTop;
      
      if (progressHeight > maxProgress) {
        progressHeight = maxProgress;
      }

      $timelineLineProgress.css({ height: `${progressHeight}px` });

      // Items aktivieren/deaktivieren
      $timelineItems.each(function () {
        const $item = $(this);
        const pointTop = $item.find($timelinePoint).offset().top;
        
        if (pointTop < viewportCenter) {
          $item.addClass('js-jem-active');
        } else {
          $item.removeClass('js-jem-active');
        }
      });
    }

    // Debounce-Hilfsfunktion
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
  });
})(jQuery);