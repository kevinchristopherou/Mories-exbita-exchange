<template>
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
                  <td><strong>{{market.name}}</strong></td>
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
</template>

<script>
  export default {
    computed: {
      markets: function () {
        let m = this.$store.getters.getMarkets.concat([]);

        m.sort(function (a, b) {
          if (a.volume_24h < b.volume_24h) {
            return -1
          }
          else if (a.volume_24h > b.volume_24h) {
            return -1
          }

          return 0;
        });

        return m;
      }
    },
  }
</script>