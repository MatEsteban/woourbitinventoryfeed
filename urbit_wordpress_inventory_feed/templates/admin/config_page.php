<div class="wrap">
  <?php
   $active_tab = isset( $GET['tab' ] ) ? $_GET['tab'] : 'presentation';
  ?>

  <h1><?php echo get_admin_page_title() ?></h1>
  <h2 class="nav-tab-wrapper">
    <a href="#presentation" class="nav-tab <?php echo $active_tab == 'presentation' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Presentation', 'textdomain' ); ?></a>
    <a href="#config" class="nav-tab <?php echo $active_tab == 'config' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Module Configuration', 'textdomain' ); ?></a>
  </h2>

 <form action="options.php" method="POST" class="urbit_inventoryfeed_form">

      <div id="presentation" class="tabs">
        <div id="urbit-theme-texticon_390" class="urbit-theme__widget urbit-theme-texticon ABdmKOduM6 text-center" style="" data-tab-id="intro">
          <h2 class="h2">Main benefit of being our partner ?</h2>
          <br><br>
            <div class="row urbit-theme-texticon__element">
              <div class="col col-sm-2 col-xs-12">
                <div class="image-wrapper">
                  <img src="https://urb-it.com/fr/wp-content/uploads/sites/4/2017/11/Play.jpg" id="play">
                </div>
              </div>
              <div class="col col-sm-8 col-xs-12">
                <div class="title h h6">MOVE PRODCUTS FASTER</div>
                <div class="text b b3">Adding Urb-it as a sales channel for your physical store will move your inventory faster as it lower the barrier to purchase. Checking out with urb-it is seamless for customers and handover often happens within an hour of purchase.</div>
              </div>
            </div>
            <br>
      <div class="row urbit-theme">
        <div class="col col-sm-2 col-xs-12">
          <div class="image-wrapper">
            <img src="https://urb-it.com/fr/wp-content/uploads/sites/4/2017/11/Satisfaction.jpg">
          </div>
        </div>
        <div class="col col-sm-8 col-xs-12">
          <div class="title h h6">SATISFY THE ON-DEMAND NEED OF YOUR CUSTOMER</div>
          <div class="text b b3">Nowadays customers get inspired, shop and share their experience from all possible places and platforms; social media, the web, stores etc. Urb-it helps you to meet the exceeding expectations for “on demand” shopping this entails, by being accessible when and where they want to be inspired, shop, and receive their purchase.</div>
        </div>
      </div>
      <br><br>
      <div class="row urbit-theme-texticon__element">
        <div class="col col-sm-2 col-xs-12">
          <br>
          <div class="image-wrapper">
            <img src="https://urb-it.com/fr/wp-content/uploads/sites/4/2017/11/Rotation.jpg">
          </div>
        </div>
        <br>
        <div class="col col-sm-8 col-xs-12">
          <div class="title h h6">OFFER EXTRAORDINARY CUSTOMER EXPERIENCE FROM START TO FINISH</div>
          <div class="text b b3">With Urb-it you can be sure that your customer will be treated right. From a smooth, hassle-free checkout process to a personal handover of their purchase at a time that suits them best, your customer is in good hands.</div>
        </div>
      </div>
      </div>
      <br><br>
      <div class="row text-center">
        <div class="col col-sm-12">
            <h2 class="h2">How Does Urb-it Work?</h2>
          </div>
          <div class="col col-sm-12">
            <div class="row urbit-theme-circle__container">
              <div class="col col-sm-4 text-center urbit-theme-circle__element numerated">
                <div class="image image-small">
                  <img src="https://urb-it.com/wp-content/uploads/2017/10/Onboarding_1_500x500px_2-1.gif" alt="Un client achète un produit de votre boutique sur l’application, sur votre site e-commerce ou directement dans votre magasin.">
                </div>
                <div class="data">
                  <span class="num">1</span>
                  <div class="title">SHOP FOR YOURSELF, OR CHOOSE THE PERFECT GIFT FOR SOMEONE ELSE</div>
                  <div class="text"></div>
                </div>
              </div>
              <div class="col col-sm-4 text-center urbit-theme-circle__element numerated">
                <div class="image image-small">
                  <img src="https://urb-it.com/wp-content/uploads/2017/10/Onboarding_2_500x500px-3.gif" alt="Un Urber va chercher vos achats dans votre boutique.">
                </div>
                <div class="data">
                  <span class="num">2</span>
                  <div class="title">CHOOSE A TIME AND PLACE YOU WANT IT. IF IT’S A GIFT, YOU CAN LET THE GIFT RECIPIENT CHOOSE OR EVEN SEND IT AS A SURPRISE.</div>
                  <div class="text"></div>
                </div>
              </div>
              <div class="col col-sm-4 text-center urbit-theme-circle__element numerated">
                <div class="image image-small">
                  <img src="https://urb-it.com/wp-content/uploads/2017/10/Onboarding_3_500x500px-2.gif" alt="Votre Urber apporte vos produits à vos clients exactement à l’heure et à l’endroit qu’ils ont choisi. ">
                </div>
                <div class="data">
                  <span class="num">3</span>
                  <div class="title">AN URBER WILL BRING IT TO YOU, OR TO THE GIFT RECIPIENT</div>
                  <div class="text"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br><br>
        <div class="contact-btn text-center">
          <a href="https://urb-it.com/fr/contact-us/" target="_blank"><button type="button" class="btn btn-primary btn-lg">CONTACT US</button></a>
          </div>
    </div>
    <div id="config" class="tabs">

    <form action="options.php" method="POST" class="urbit_inventoryfeed_form">
        <?php
            settings_fields($option_group);

            /** @var UIF_Admin_Settings_Section $section */
            foreach ($sections as $section) {
                do_settings_sections($section->getPageId());
            }

            submit_button();
        ?>
        </form>
    </div>
</div>

<style>
    .urbit_inventoryfeed_form select {
        min-width: 190px;
    }
    img{
     width: 100px;
    
</style>

<script>

jQuery( document ).ready( function( $ ) {

   // Tabs
   var $navs = $( '.nav-tab' ),
       $tabs = $( '.tabs' ),
       toogle = function( hash ) {
          $navs.removeClass( 'nav-tab-active' );
          $( 'a[href=' + hash + ']' ).addClass( 'nav-tab-active' );
          $tabs.hide();
          $( hash ).show();
       };

   toogle( window.location.hash );

   $navs.on( 'click', function( e ) {
      e.preventDefault();
      toogle( e.target.hash );
   });

});
</script>
