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


 /* AFFIX CLASS DEFINITION
  * ====================== */

  var Affix = function (element, options) {
    this.options = $.extend({}, $.fn.affix.defaults, options);
    this.$window = $(window)
      .on('scroll.affix.data-api', $.proxy(this.checkPosition, this))
      .on('click.affix.data-api',  $.proxy(function () { setTimeout($.proxy(this.checkPosition, this), 1) }, this));
    this.$element = $(element);
    this.checkPosition()
  };

  Affix.prototype.checkPosition = function () {
    if (!this.$element.is(':visible')) return;

    var scrollHeight = $(document).height()
      , scrollTop = this.$window.scrollTop()
      , position = this.$element.offset()
      , offset = this.options.offset
      , offsetBottom = offset.bottom
      , offsetTop = offset.top
      , reset = 'affix affix-top affix-bottom'
      , affix;

    if (typeof offset != 'object') offsetBottom = offsetTop = offset;
    if (typeof offsetTop == 'function') offsetTop = offset.top();
    if (typeof offsetBottom == 'function') offsetBottom = offset.bottom();

    affix = this.unpin != null && (scrollTop + this.unpin <= position.top) ?
      false    : offsetBottom != null && (position.top + this.$element.height() >= scrollHeight - offsetBottom) ?
      'bottom' : offsetTop != null && scrollTop <= offsetTop ?
      'top'    : false;

    if (this.affixed === affix) return;

    this.affixed = affix;
    this.unpin = affix == 'bottom' ? position.top - scrollTop : null;

    this.$element.removeClass(reset).addClass('affix' + (affix ? '-' + affix : ''))
  };


 /* AFFIX PLUGIN DEFINITION
  * ======================= */

  var old = $.fn.affix;

  $.fn.affix = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('affix')
        , options = typeof option == 'object' && option;
      if (!data) $this.data('affix', (data = new Affix(this, options)));
      if (typeof option == 'string') data[option]()
    })
  };

  $.fn.affix.Constructor = Affix;

  $.fn.affix.defaults = {
    offset: 0
  };


 /* AFFIX NO CONFLICT
  * ================= */

  $.fn.affix.noConflict = function () {
    $.fn.affix = old;
    return this
  };


 /* AFFIX DATA-API
  * ============== */

  $(window).on('load', function () {
    $('[data-spy="affix"]').each(function () {
      var $spy = $(this)
        , data = $spy.data();

      data.offset = data.offset || {};

      data.offsetBottom && (data.offset.bottom = data.offsetBottom);
      data.offsetTop && (data.offset.top = data.offsetTop);

      $spy.affix(data)
    })
  })


}(window.jQuery);