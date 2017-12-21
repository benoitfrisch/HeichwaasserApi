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


 /* TAB CLASS DEFINITION
  * ==================== */

  var Tab = function (element) {
    this.element = $(element)
  };

  Tab.prototype = {

    constructor: Tab

  , show: function () {
      var $this = this.element
        , $ul = $this.closest('ul:not(.dropdown-menu)')
        , selector = $this.attr('data-target')
        , previous
        , $target
        , e;

      if (!selector) {
        selector = $this.attr('href');
        selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
      }

      if ( $this.parent('li').hasClass('active') ) return;

      previous = $ul.find('.active:last a')[0];

      e = $.Event('show', {
        relatedTarget: previous
      });

      $this.trigger(e);

      if (e.isDefaultPrevented()) return;

      $target = $(selector);

      this.activate($this.parent('li'), $ul);
      this.activate($target, $target.parent(), function () {
        $this.trigger({
          type: 'shown'
        , relatedTarget: previous
        })
      })
    }

  , activate: function ( element, container, callback) {
      var $active = container.find('> .active')
        , transition = callback
            && $.support.transition
            && $active.hasClass('fade');

      function next() {
        $active
          .removeClass('active')
          .find('> .dropdown-menu > .active')
          .removeClass('active');

        element.addClass('active');

        if (transition) {
          element[0].offsetWidth; // reflow for transition
          element.addClass('in')
        } else {
          element.removeClass('fade')
        }

        if ( element.parent('.dropdown-menu') ) {
          element.closest('li.dropdown').addClass('active')
        }

        callback && callback()
      }

      transition ?
        $active.one($.support.transition.end, next) :
        next();

      $active.removeClass('in')
    }
  };


 /* TAB PLUGIN DEFINITION
  * ===================== */

  var old = $.fn.tab;

  $.fn.tab = function ( option ) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('tab');
      if (!data) $this.data('tab', (data = new Tab(this)));
      if (typeof option == 'string') data[option]()
    })
  };

  $.fn.tab.Constructor = Tab;


 /* TAB NO CONFLICT
  * =============== */

  $.fn.tab.noConflict = function () {
    $.fn.tab = old;
    return this
  };


 /* TAB DATA-API
  * ============ */

  $(document).on('click.tab.data-api', '[data-toggle="tab"], [data-toggle="pill"]', function (e) {
    e.preventDefault();
    $(this).tab('show')
  })

}(window.jQuery);