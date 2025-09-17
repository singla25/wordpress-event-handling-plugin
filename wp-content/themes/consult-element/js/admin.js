jQuery(document).ready(function($) {

  $('.esh-btn-link, .ins-ant-site').on('click', function(e) {
      e.preventDefault();
      $this = $(this);
      var plugin = $this.attr('plug');
      var status = $this.attr('status');
      if(status !== 'active'){
        $.ajax({
          url: admin_ajax_obj.ajax_url,
          type: 'POST',
          data: {
            action: 'admin_install_plug',
            'plugin_name': plugin,
            'esh_admin_nonce': admin_ajax_obj.nonce,
          },
          beforeSend: function(response) {
            //   $('.url_ins').removeClass('notice-action');
            console.log('yes plugin installation start');
            var loading = $(`<span class="ins-ant-site-spin"><i class="dashicons dashicons-image-rotate spinning"></i></span>`);
            $this.empty();
            if(status == 'not-installed'){
              $this.text("Installing");
            }else if(status == 'not-active'){
              $this.text("Activating");
            }
            $this.append(loading);
          },
          success: function(response) {
            console.log(response); // You can handle the response as needed
            console.log('Plugin installation completed successfully.');
            location.reload();
          },
          error: function(errorThrown) {
            console.error(errorThrown); // You can handle the error as needed
            console.log('Plugin installation failed.');
            
          }
        });
      }
  });

});