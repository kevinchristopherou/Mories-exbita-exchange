<template>
    <section class="main-content">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="content-title">
              <h1>{{$t('new_home_page.features_title')}}</h1>
              <p>{{$t('new_home_page.features_description')}}</p>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="pad-15">
              <div class="grid-block">
                <div class="d-flex">
                  <img src="/images/homepage/support.svg" alt="">
                  <div class="content-card">
                    <h4>{{$t('new_home_page.features.feature1_title')}}</h4>
                    <p>
                      {{$t('new_home_page.features.feature1_description')}}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="pad-15">
              <div class="grid-block">
                <div class="d-flex">
                  <img src="/images/homepage/shild.svg" alt="">
                  <div class="content-card">
                    <h4>{{$t('new_home_page.features.feature2_title')}}</h4>
                    <p>
                      {{$t('new_home_page.features.feature2_description')}}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="pad-15">
              <div class="grid-block">
                <div class="d-flex">
                  <img src="/images/homepage/lock.svg" alt="">
                  <div class="content-card">
                    <h4>{{$t('new_home_page.features.feature3_title')}}</h4>
                    <p>
                      {{$t('new_home_page.features.feature3_description')}}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6">
            <div class="pad-15">
              <div class="grid-block">
                <div class="d-flex">
                  <img src="/images/homepage/2fa.svg" alt="">
                  <div class="content-card">
                    <h4>{{$t('new_home_page.features.feature4_title')}}</h4>
                    <p>
                      {{$t('new_home_page.features.feature4_description')}}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--
    <div class="modal fade" id="turkey-based" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Estonia Based</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <img src="/images/estonia.png" class="img-responsive" alt="Image">


            <div class="modal-currencies-text">
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy text ever since the 1500s, when an unknown</p>
              <p>printer took a galley of type and scrambled it to make a type specimen book. It has survived not only
                five centuries, but also the leap into electronic</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="security-first" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Security First</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <img src="/images/security.jpg" class="img-responsive" alt="Image">
            <div class="modal-currencies-text">
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy text ever since the 1500s, when an unknown</p>
              <p>printer took a galley of type and scrambled it to make a type specimen book. It has survived not only
                five centuries, but also the leap into electronic</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="instant-support" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Instant Support</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <img src="/images/support.jpg" class="img-responsive" alt="Image">


            <div class="modal-currencies-text">
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy text ever since the 1500s, when an unknown</p>
              <p>printer took a galley of type and scrambled it to make a type specimen book. It has survived not only
                five centuries, but also the leap into electronic</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <h2 lang="en" class="title-h2 color-dark-blue text-center text-uppercase">{{$t('why')}} {{ sitesettings.SITE_NAME.value }}?</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
          <div class="service-1">
            <img src="/images/Estonia-based.png" class="img-responsive center-block" height="80" width="80" alt="Image">
            <h3>{{$t('turkey_based_title')}}</h3>
            <p>{{$t('turkey_based_body')}}</p>
            <a href="#" data-toggle="modal" data-target="#turkey-based"
               class="text-uppercase">{{$t('learn_more')}}</a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
          <div class="service-1">
            <img src="/images/Security-First.png" class="img-responsive center-block" height="80" width="80"
                 alt="Image">
            <h3>{{$t('security_first_title')}}</h3>
            <p>{{$t('security_first_body')}}</p>
            <a href="#" data-toggle="modal" data-target="#security-first"
               class="text-uppercase">{{$t('learn_more')}}</a>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
          <div class="service-1">
            <img src="/images/Support-for-Algorithmic-Trading.png" class="img-responsive center-block" height="80"
                 width="80" alt="Image">
            <h3>{{$t('instant_support_title')}}</h3>
            <p>{{$t('instant_support_body')}}</p>
            <a href="#" data-toggle="modal" data-target="#instant-support"
               class="text-uppercase">{{$t('learn_more')}}</a>
          </div>
        </div>
      </div>
    </div>
    -->
</template>

<script>
  export default {

  }
</script>
