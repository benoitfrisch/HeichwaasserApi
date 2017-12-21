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


 /* BUTTON PUBLIC CLASS DEFINITION
  * ============================== */

  var Button = function (element, options) {
    this.$element = $(element);
    this.options = $.extend({}, $.fn.button.defaults, options)
  };

  Button.prototype.setState = function (state) {
    var d = 'disabled'
      , $el = this.$element
      , data = $el.data()
      , val = $el.is('input') ? 'val' : 'html';

    state = state + 'Text';
    data.resetText || $el.data('resetText', $el[val]());

    $el[val](data[state] || this.options[state]);

    // push to event loop to allow forms to submit
    setTimeout(function () {
      state == 'loadingText' ?
        $el.addClass(d).attr(d, d) :
        $el.removeClass(d).removeAttr(d)
    }, 0)
  };

  Button.prototype.toggle = function () {
    var $parent = this.$element.closest('[data-toggle="buttons-radio"]');

    $parent && $parent
      .find('.active')
      .removeClass('active');

    this.$element.toggleClass('active')
  };


 /* BUTTON PLUGIN DEFINITION
  * ======================== */

  var old = $.fn.button;

  $.fn.button = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('button')
        , options = typeof option == 'object' && option;
      if (!data) $this.data('button', (data = new Button(this, options)));
      if (option == 'toggle') data.toggle();
      else if (option) data.setState(option)
    })
  };

  $.fn.button.defaults = {
    loadingText: 'loading...'
  };

  $.fn.button.Constructor = Button;


 /* BUTTON NO CONFLICT
  * ================== */

  $.fn.button.noConflict = function () {
    $.fn.button = old;
    return this
  };


 /* BUTTON DATA-API
  * =============== */

  $(document).on('click.button.data-api', '[data-toggle^=button]', function (e) {
    var $btn = $(e.target);
    if (!$btn.hasClass('btn')) $btn = $btn.closest('.btn');
    $btn.button('toggle')
  })

}(window.jQuery);