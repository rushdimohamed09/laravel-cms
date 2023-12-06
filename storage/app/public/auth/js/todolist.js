(function($) {
    'use strict';
    $(function() {
      var todoListItem = $('.todo-list');
      var todoListInput = $('.todo-list-input');
      var sectionInput = $('#section-input');
      var itemDropdown = $('.item-dropdown');

      $('.todo-list-add-btn').on("click", function(event) {
        event.preventDefault();

        var item = $(this).prevAll('.todo-list-input').val();

        if (item) {
          todoListItem.append("<li><div class='form-check'><label class='form-check-label'><input class='checkbox' type='checkbox'/>" + item + "<i class='input-helper'></i></label></div><i class='remove mdi mdi-close-circle-outline'></i></li>");
          todoListInput.val("");
        }

      });

      todoListItem.on('change', '.checkbox', function() {
        if ($(this).attr('checked')) {
          $(this).removeAttr('checked');
        } else {
          $(this).attr('checked', 'checked');
        }

        $(this).closest("li").toggleClass('completed');

      });

      todoListItem.on('click', '.remove', function() {
        $(this).parent().remove();
      });

      sectionInput.on('input', function() {
          var input = $(this).val();
          if (input.length >= 3) {
            $.ajax({
              url: '/get-sections', // Replace with your Laravel route
              method: 'GET',
              data: { name: input },
              success: function(data) {
                // Clear previous items
                itemDropdown.empty();

                // Display the list of items in the dropdown
                $.each(data, function(index, item) {
                  // Append each item to the dropdown
                //   itemDropdown.append('<div class="item">' + item.title + '</div>');
                var itemName = item.title;
                var matchingPart = itemName.match(new RegExp(input, 'i')); // Case-insensitive match
                if (matchingPart) {
                    // Highlight the matching part by wrapping it in <strong> tags
                    var formattedItem = itemName.replace(new RegExp(matchingPart, 'i'), '<strong>$&</strong>');
                    // Append the formatted item to the dropdown
                    itemDropdown.append('<div class="item">' + formattedItem + '</div>');
                }
                });

                // Handle item selection and populate the input field
                itemDropdown.on('click', '.item', function() {
                  sectionInput.val($(this).text());
                  itemDropdown.empty(); // Clear the item dropdown
                });
              },
              error: function(error) {
                console.error(error);
              }
            });
          } else {
            itemDropdown.empty(); // Clear the item dropdown if the input is less than 3 characters
          }
      });

    });
  })(jQuery);
