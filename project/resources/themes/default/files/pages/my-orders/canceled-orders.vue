<template>
  <div class="row">
    <div class="col-12">

      <div class="table-title">
        {{$t('my_orders.canceled_orders')}}
      </div>

      <div class="table-filter">
        {{ $t('market.select_market')}}:
        <button class="ml-2 btn btn-sm btn-outline dropdown-toggle" type="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
          {{ marketName ? marketName : $t('please_select')}}
        </button>
        <div class="dropdown-menu">
          <a class="dropdown-item"
             @click="marketName = null">
            {{ $t('all') }}
          </a>
          <a v-for="market in markets"
             class="dropdown-item"
             @click="marketName = market.name">
            {{ market.name }}
          </a>
        </div>
      </div>

      <b-table show-empty
               stacked="xs"
               :items="orders.data"
               :fields="fields"
               class="table-striped table-bordered table-sm table-responsive"
               :empty-text="$t('my_orders.no_canceled_orders')">

        <template v-slot:cell(dateField)="order">
          <div>
            {{order.item.created_at | tz_datetime}}
          </div>
        </template>
        <template v-slot:cell(canceledDateField)="order">
          <div>
            {{order.item.deleted_at | tz_datetime}}
          </div>
        </template>
        <template v-slot:cell(marketField)="order">
          <div>
            <router-link :to="{ name: 'market.inner',params: {marketName: order.item.market}}">{{ order.item.market }}
            </router-link>
          </div>
        </template>
        <template v-slot:cell(typeField)="order">
          <div>
            <span v-if="order.item.type === 'SELL_LIMIT' || order.item.type === 'SELL'" class="text-danger">
            <icon name="arrow-down"></icon>
            {{$t('market.transaction_sell')}}
            </span>
            <span v-if="order.item.type === 'BUY_LIMIT' || order.item.type === 'BUY'" class="text-success">
            <icon name="arrow-up"></icon>
            {{$t('market.transaction_buy')}}
            </span>
          </div>
        </template>
        <template v-slot:cell(rateField)="order">
          <div>
            <span v-if="order.item.rate > 0">
              {{order.item.rate | round(2)}} {{getOrderMarketQuoteCurrency(order.item)}}
            </span>
            <span v-else>
              {{$t('order.quick_order')}}
            </span>
          </div>
        </template>
        <template v-slot:cell(rateActualField)="order">
          <div>
            {{order.item.rate_actual | round(2)}} {{getOrderMarketQuoteCurrency(order.item)}}
          </div>
        </template>
        <template v-slot:cell(quantityField)="order">
          <div>
            {{order.item.quantity | round(8)}} {{getOrderMarketCurrency(order.item)}}
          </div>
        </template>
        <template v-slot:cell(quantityRemainingField)="order">
          <div>
            {{order.item.quantity_remaining | round(8)}} {{getOrderMarketCurrency(order.item)}}
          </div>
        </template>
        <template v-slot:cell(fillRatioField)="order">
          <div>
            {{((order.item.quantity - order.item.quantity_remaining) * 100 / order.item.quantity) | round(2)}} %
          </div>
        </template>
        <template v-slot:cell(HEAD_actions)="order">
          {{ $t('abort')}}
        </template>
      </b-table>

      <b-pagination class="mt-2 pagination-sm"
                    v-if="orders.meta.last_page > 1"
                    :total-rows="orders.meta.total"
                    :per-page="Number(orders.meta.per_page)"
                    v-model="orders.meta.current_page"
                    @input="loadOrders"></b-pagination>
    </div>
  </div>
</template>

<script>
  import axios from 'axios';

  export default {

    data: function () {
      return {
        marketName: null,
        orders: {
          data: [],
          meta: {
            total: 0,
            per_page: 10,
            from: 0,
            to: 0,
            current_page: 1,
            last_page: 1
          }
        },
        fields: [
          {
            key: 'marketField',
            label: this.$t('market.market')
          },
          {
            key: 'dateField',
            label: this.$t('order.created_date')
          },
          {
            key: 'canceledDateField',
            label: this.$t('order.cancel_date')
          },
          {
            key: 'typeField',
            label: this.$t('order.order_type')
          },
          {
            key: 'rateField',
            label: this.$t('order.rate')
          },
          {
            key: 'rateActualField',
            label: this.$t('order.rate_actual')
          },
          {
            key: 'quantityField',
            label: this.$t('order_book.amount')
          },
          {
            key: 'quantityRemainingField',
            label: this.$t('order_book.amount_remaining')
          },
          {
            key: 'fillRatioField',
            label: this.$t('order.fill_ratio')
          }
        ],
      }
    },

    mounted() {
      this.loadOrders();
    },
    computed: {
      markets: function () {
        return this.$store.getters.getMarkets;
      }
    },
    watch: {
      marketName: function (newVal, oldVal) {
        this.loadOrders();
      }
    },
    methods: {
      loadOrders: function () {
        let url = 'orders?status=canceled&perpage=' + this.orders.meta.per_page + '&page=' + this.orders.meta.current_page;
        if (this.marketName !== '') {
          url += '&market=' + this.marketName;
        }

        axios.get(url).then((response) => {
          this.orders = response.data;
        });
      }
    }

  }
</script>
