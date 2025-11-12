

<script>
      function successNotification(title,message) {
           Swal.fire({
               title: title,
               text: message,
               icon: 'success',
               confirmButtonText: 'OK'
            });
       
    }
    
     function errorNotification(title,message) {
           Swal.fire({
               title: title,
               text: message,
               icon: 'error',
               confirmButtonText: 'OK'
            });
       
    }
</script>


<footer class="footer footer-static footer-light navbar-border navbar-shadow">
      <div style='color:<?= $color_background; ?>' class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">2025  &copy; Copyright <a class="text-bold-800 grey darken-2" href="<?= $external_link; ?>" target="_blank"><?= $website_name; ?></a></span>
        <ul class="list-inline float-md-right d-block d-md-inline-blockd-none d-lg-block mb-0">
          <li style='color:<?= $color_background; ?>' class="list-inline-item"><a class="my-1" href="<?= $external_link; ?>" target="_blank"> More Tools</a></li>
          <li style='color:<?= $color_background; ?>' class="list-inline-item"><a class="my-1" href="https://wa.me/<?= $supportPhoneNumber; ?>" target="_blank">Support</a></li>
          
        </ul>
      </div>
    </footer>