<template>
  <div>
    <section class="bg-white">
      <div id="currencies" class="welcome-block"></div>
      <div class="container-fluid">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-xl-7">
            <div class="digital-currency">
              <div class="color-dark-blue">
                <h4></h4>
                <h2 class="title-h2 text-left">{{$t('Bitcoin & Ethereum Nodes Integrated')}}</h2>
              </div>
              <p>{{$t('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nec dictum risus. Duis ligula orci, tincidunt ut massa et, vulputate rutrum felis. Morbi imperdiet quam nec metus fermentum')}}</p>
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-12 col-xl-4">
            <div class="currency-ul-out">
              <ul class="row currency-ul">
                <li v-for="currency in currencies" v-if="currency.abbr == 'BTC' || currency.abbr == 'ETH'" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 currency-li">
                  <div class="row">
                    <a class="currency-box" href="#" data-toggle="modal" :data-target="'#modal-' +currency.abbr">
                      <i v-bind:class="currency.class" :title="currency.abbr"></i>
                      <span>{{currency.name}}</span>
                    </a>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div v-for="currency in currencies" :id="'modal-' + currency.abbr"
         class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{currency.name}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="modal-currencies-text">
              <p v-html="currency.desc"></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: () => ({
      currencies: [
        {
          class: 'cc BTC',
          name: 'BITCOIN',
          abbr: 'BTC',
          desc: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the\n' +
          '                industry\'s standard dummy text ever since the 1500s, when an unknown',
        },
        {
          class: 'cc ETH',
          name: 'ETHEREUM',
          abbr: 'ETH',
          desc: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the\n' +
          '                industry\'s standard dummy text ever since the 1500s, when an unknown.',
        },
      ]
    }),
  }
</script>
