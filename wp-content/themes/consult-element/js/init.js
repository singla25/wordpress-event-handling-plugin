jQuery(document).ready(function($) {
    function installPluginFromOrg() {
        // Create a deferred object
        var deferred = $.Deferred();
        $.ajax({
          url: ins_plug_ajax_obj.ajax_url,
          type: 'POST',
          data: {
            action: 'install_plug',
            'check_plug_install_nonce': ins_plug_ajax_obj.nonce,
          },
          beforeSend: function(response) {
            $('.url_ins').removeClass('notice-action');
          },
          success: function(response) {
            console.log(response); // You can handle the response as needed
            console.log('Plugin installation completed successfully.');
            deferred.resolve();
          },
          error: function(errorThrown) {
            console.error(errorThrown); // You can handle the error as needed
            console.log('Plugin installation failed.');
            
            // Reject the deferred object if the installation failed
            deferred.reject();
          }
        });
        // Return the promise object associated with the deferred object
        return deferred.promise();
      }
      // Trigger the plugin installation when the button is clicked
      $('.url_ins').on('click', function(e) {
        e.preventDefault();
        $this = $(this);
        var loading = $('<span class="loading">' +
                          '<span></span>' +
                          '<span></span>' +
                          '<span></span>' +
                          '<span></span>' +
                          '<span></span>' +
                        '</span>');
        $this.removeClass('notice-action');
        $this.addClass('notice-install');
        $this.empty();
        $this.text("Installing Anant Sites Elementor Library");
        $this.append(loading);
        installPluginFromOrg()
          .done(function() {
            console.log('Anant Sites installed successfully.');
            $this.text("Redirecting Template Library Importer");
            redirectToImportPage(ins_plug_ajax_obj.import_url);
          })
          .fail(function() {
            console.log('Failed to install Anant Sites.');
          });
      });
      function redirectToImportPage(url) {
        window.location.href = url;
      }

      $( document ).on( 'click', '.esh-el-notice .notice-dismiss', function () {
        var $this= $(this);
        var type = $( this ).closest( '.esh-el-notice' ).data( 'notice' );
        // Make an AJAX call
        $.ajax( ajaxurl,
        {
          type: 'POST',
          data: {
            action: 'dismissed_notice',
            type: type,
          },
          success: function(response) {
            $this.closest('.esh-el-notice').remove();
          },
        } );
      } );
});