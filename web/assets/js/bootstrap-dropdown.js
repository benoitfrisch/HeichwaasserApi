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


 /* DROPDOWN CLASS DEFINITION
  * ========================= */

  var toggle = '[data-toggle=dropdown]'
    , Dropdown = function (element) {
        var $el = $(element).on('click.dropdown.data-api', this.toggle);
        $('html').on('click.dropdown.data-api', function () {
          $el.parent().removeClass('open')
        })
      };

  Dropdown.prototype = {

    constructor: Dropdown

  , toggle: function (e) {
      var $this = $(this)
        , $parent
        , isActive;

      if ($this.is('.disabled, :disabled')) return;

      $parent = getParent($this);

      isActive = $parent.hasClass('open');

      clearMenus();

      if (!isActive) {
        $parent.toggleClass('open')
      }

      $this.focus();

      return false
    }

  , keydown: function (e) {
      var $this
        , $items
        , $active
        , $parent
        , isActive
        , index;

      if (!/(38|40|27)/.test(e.keyCode)) return;

      $this = $(this);

      e.preventDefault();
      e.stopPropagation();

      if ($this.is('.disabled, :disabled')) return;

      $parent = getParent($this);

      isActive = $parent.hasClass('open');

      if (!isActive || (isActive && e.keyCode == 27)) {
        if (e.which == 27) $parent.find(toggle).focus();
        return $this.click()
      }

      $items = $('[role=menu] li:not(.divider):visible a', $parent);

      if (!$items.length) return;

      index = $items.index($items.filter(':focus'));

      if (e.keyCode == 38 && index > 0) index--;                                        // up
      if (e.keyCode == 40 && index < $items.length - 1) index++;                        // down
      if (!~index) index = 0;

      $items
        .eq(index)
        .focus()
    }

  };

  function clearMenus() {
    $(toggle).each(function () {
      getParent($(this)).removeClass('open')
    })
  }

  function getParent($this) {
    var selector = $this.attr('data-target')
      , $parent;

    if (!selector) {
      selector = $this.attr('href');
      selector = selector && /#/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
    }

    $parent = selector && $(selector);

    if (!$parent || !$parent.length) $parent = $this.parent();

    return $parent
  }


  /* DROPDOWN PLUGIN DEFINITION
   * ========================== */

  var old = $.fn.dropdown;

  $.fn.dropdown = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('dropdown');
      if (!data) $this.data('dropdown', (data = new Dropdown(this)));
      if (typeof option == 'string') data[option].call($this)
    })
  };

  $.fn.dropdown.Constructor = Dropdown;


 /* DROPDOWN NO CONFLICT
  * ==================== */

  $.fn.dropdown.noConflict = function () {
    $.fn.dropdown = old;
    return this
  };


  /* APPLY TO STANDARD DROPDOWN ELEMENTS
   * =================================== */

  $(document)
    .on('click.dropdown.data-api', clearMenus)
    .on('click.dropdown.data-api', '.dropdown form', function (e) { e.stopPropagation() })
    .on('click.dropdown-menu', function (e) { e.stopPropagation() })
    .on('click.dropdown.data-api'  , toggle, Dropdown.prototype.toggle)
    .on('keydown.dropdown.data-api', toggle + ', [role=menu]' , Dropdown.prototype.keydown)

}(window.jQuery);
