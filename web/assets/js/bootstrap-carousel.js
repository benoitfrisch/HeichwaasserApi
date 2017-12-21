/*
 * This file is part of PremiereLu.
 *
 * Copyright (c) 2017 Benoît FRISCH
 *
 * PremiereLu is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PremiereLu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PremiereLu If not, see <http://www.gnu.org/licenses/>.
 */


!function ($) {

  "use strict"; // jshint ;_;


 /* CAROUSEL CLASS DEFINITION
  * ========================= */

  var Carousel = function (element, options) {
    this.$element = $(element);
    this.$indicators = this.$element.find('.carousel-indicators');
    this.options = options;
    this.options.pause == 'hover' && this.$element
      .on('mouseenter', $.proxy(this.pause, this))
      .on('mouseleave', $.proxy(this.cycle, this))
  };

  Carousel.prototype = {

    cycle: function (e) {
      if (!e) this.paused = false;
      if (this.interval) clearInterval(this.interval);
      this.options.interval
        && !this.paused
        && (this.interval = setInterval($.proxy(this.next, this), this.options.interval));
      return this
    }

  , getActiveIndex: function () {
      this.$active = this.$element.find('.item.active');
      this.$items = this.$active.parent().children();
      return this.$items.index(this.$active)
    }

  , to: function (pos) {
      var activeIndex = this.getActiveIndex()
        , that = this;

      if (pos > (this.$items.length - 1) || pos < 0) return;

      if (this.sliding) {
        return this.$element.one('slid', function () {
          that.to(pos)
        })
      }

      if (activeIndex == pos) {
        return this.pause().cycle()
      }

      return this.slide(pos > activeIndex ? 'next' : 'prev', $(this.$items[pos]))
    }

  , pause: function (e) {
      if (!e) this.paused = true;
      if (this.$element.find('.next, .prev').length && $.support.transition.end) {
        this.$element.trigger($.support.transition.end);
        this.cycle(true)
      }
      clearInterval(this.interval);
      this.interval = null;
      return this
    }

  , next: function () {
      if (this.sliding) return;
      return this.slide('next')
    }

  , prev: function () {
      if (this.sliding) return;
      return this.slide('prev')
    }

  , slide: function (type, next) {
      var $active = this.$element.find('.item.active')
        , $next = next || $active[type]()
        , isCycling = this.interval
        , direction = type == 'next' ? 'left' : 'right'
        , fallback  = type == 'next' ? 'first' : 'last'
        , that = this
        , e;

      this.sliding = true;

      isCycling && this.pause();

      $next = $next.length ? $next : this.$element.find('.item')[fallback]();

      e = $.Event('slide', {
        relatedTarget: $next[0]
      , direction: direction
      });

      if ($next.hasClass('active')) return;

      if (this.$indicators.length) {
        this.$indicators.find('.active').removeClass('active');
        this.$element.one('slid', function () {
          var $nextIndicator = $(that.$indicators.children()[that.getActiveIndex()]);
          $nextIndicator && $nextIndicator.addClass('active')
        })
      }

      if ($.support.transition && this.$element.hasClass('slide')) {
        this.$element.trigger(e);
        if (e.isDefaultPrevented()) return;
        $next.addClass(type);
        $next[0].offsetWidth; // force reflow
        $active.addClass(direction);
        $next.addClass(direction);
        this.$element.one($.support.transition.end, function () {
          $next.removeClass([type, direction].join(' ')).addClass('active');
          $active.removeClass(['active', direction].join(' '));
          that.sliding = false;
          setTimeout(function () { that.$element.trigger('slid') }, 0)
        })
      } else {
        this.$element.trigger(e);
        if (e.isDefaultPrevented()) return;
        $active.removeClass('active');
        $next.addClass('active');
        this.sliding = false;
        this.$element.trigger('slid')
      }

      isCycling && this.cycle();

      return this
    }

  };


 /* CAROUSEL PLUGIN DEFINITION
  * ========================== */

  var old = $.fn.carousel;

  $.fn.carousel = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('carousel')
        , options = $.extend({}, $.fn.carousel.defaults, typeof option == 'object' && option)
        , action = typeof option == 'string' ? option : options.slide;
      if (!data) $this.data('carousel', (data = new Carousel(this, options)));
      if (typeof option == 'number') data.to(option);
      else if (action) data[action]();
      else if (options.interval) data.pause().cycle()
    })
  };

  $.fn.carousel.defaults = {
    interval: 5000
  , pause: 'hover'
  };

  $.fn.carousel.Constructor = Carousel;


 /* CAROUSEL NO CONFLICT
  * ==================== */

  $.fn.carousel.noConflict = function () {
    $.fn.carousel = old;
    return this
  };

 /* CAROUSEL DATA-API
  * ================= */

  $(document).on('click.carousel.data-api', '[data-slide], [data-slide-to]', function (e) {
    var $this = $(this), href
      , $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) //strip for ie7
      , options = $.extend({}, $target.data(), $this.data())
      , slideIndex;

    $target.carousel(options);

    if (slideIndex = $this.attr('data-slide-to')) {
      $target.data('carousel').pause().to(slideIndex).cycle()
    }

    e.preventDefault()
  })

}(window.jQuery);