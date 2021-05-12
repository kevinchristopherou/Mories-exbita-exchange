<template>
  <section class="main-content pt-0">
    <div class="container">
      <div class="col-12">
        <div class="col-12">
          <div class="content-title">
            <h1>{{$t('new_home_page.markets.markets_title')}}</h1>
            <p>{{$t('new_home_page.markets.markets_description')}}</p>
          </div>
        </div>
        <div class="data-table">
          <div class="table-responsive">
            <table class="table  table-borderless mt-1 mb-1">
              <thead>
              <tr>
                <th scope="col">{{ $t('market.market') }}</th>
                <th scope="col">{{ $t('market.current_price') }}</th>
                <th scope="col">{{ $t('market.change_24h') }}</th>
                <th scope="col">{{ $t('market.volume_24h') }}</th>
                <th scope="col">{{ $t('market.highest_24h') }}</th>
                <th scope="col">{{ $t('market.lowest_24h') }}</th>
              </tr>
              </thead>
              <tbody>

              <tr v-for="market in markets">
                <td>
                  <router-link :to="{ name: 'market.inner',params: {marketName: market.name}}">
                    {{ market.name }}
                  </router-link>
                </td>
                <td>{{market.last}} {{ market.quote_currency}}</td>
                <td :class="getChangeColorClass(market.change_24h_percent)">
                  {{market.change_24h_percent| round(2)}}%
                </td>
                <td>{{market.volume_24h }} {{ market.currency}}</td>
                <td>{{market.high_24h }} {{ market.currency}}</td>
                <td>{{market.low_24h }} {{ market.currency}}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!--
  <div class="container-fluid">
    <div id="market" class="row confidently-markets">
      <div class="col-lg-12 col-xl-7 bg-green">
        <div class="row">
          <div class="color-white confidently">
            <h2>{{$t('secure_exchange_title')}}</h2>
            <p>{{$t('secure_exchange_body')}}</p>
            <router-link :to="{name: 'register' }" v-if="!authenticated">
              <button type="button" class="btn btn-green btn-lg">{{$t('sign_up_now')}}</button>
            </router-link>
          </div>
        </div>
      </div>
      <div class="col-lg-12 col-xl-5 bg-white">
        <div class="row">
          <div class="markets color-dark-blue">
            <h3>{{$t('active_markets')}}</h3>
            <div class="table-responsive">
              <table class="table-custom table table-bordered mb-0">
                <thead>
                <tr>
                  <th>{{$t('market.market')}}</th>
                  <th>{{$t('current_value')}}</th>
                  <th>{{$t('change_percent')}}</th>
                  <th>{{$t('24h_exchange_volume')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr class="fisrt" v-for="market in markets">
                  <td>
                    <router-link :to="{ name: 'market.inner',params: {marketName: market.name}}">
                      {{ market.name }}
                    </router-link>
                  </td>
                  <td>{{market.last}} {{ market.quote_currency}}</td>
                  <td :class="getChangeColorClass(market.change_24h_percent)">
                    %{{market.change_24h_percent| round(2)}}
                  </td>
                  <td>{{market.volume_24h }} {{ market.currency}}</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  -->
</template>

<script>
  export default {
    computed: {
      markets: function () {
        let m = this.$store.getters.getMarkets.concat([]);

        m.sort((a, b) => (a.volume_24h < b.volume_24h) ? 1 : -1)

        return m;
      }
    },
  }
</script>
