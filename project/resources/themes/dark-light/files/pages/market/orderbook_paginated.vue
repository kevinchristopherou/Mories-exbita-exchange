<template>
  <div>
    <b-table show-empty
             stacked="xs"
             :items="buyOrders"
             :fields="buyOrderFields"
             :current-page="buyOrdersCurrentPage"
             class="table-striped table-bordered table-sm table-buy"
             :empty-text="$t('order_book.no_buy_orders')"
             :per-page="perPage">
      <template slot="HEAD_actions" slot-scope="data" class="text-center">
        <div class="text-center">
          <icon name="star"></icon>
        </div>
      </template>
      <template slot="actions" slot-scope="order">
        <div class="qbar" v-bind:style="{width : getQbarWidthBuy(order.item.sum) + 'px'}"></div>
        <div class="text-center">
          <button class="btn btn-xs" v-if="order.item.uuids.length > 0"
                  v-b-tooltip.hover.html
                  :title="getTableCancelTooltipText(order.item)"
                  v-on:click="cancelOrders(order.item.uuids)">
            <icon name="times"></icon>
          </button>
        </div>
      </template>
      <template slot="rateField" slot-scope="order">
        <div v-b-tooltip.hover.html :title="getRowTooltipText(order.item)">
          {{order.item.rate | round(2)}}
        </div>
      </template>
      <template slot="quantityField" slot-scope="order">
        <div v-b-tooltip.hover.html :title="getRowTooltipText(order.item)">
          {{order.item.quantity | round(8)}}
        </div>
      </template>
      <template slot="totalField" slot-scope="order">
        <div v-b-tooltip.hover.html :title="getRowTooltipText(order.item)">
          {{order.item.total | round(2)}}
        </div>
      </template>
      <template slot="sumField" slot-scope="order">
        <div>
          {{order.item.sum | round(2)}}
        </div>
      </template>
    </b-table>

    <b-pagination class="mt-2 pagination-sm float-left"
                  v-if="buyOrders.length > perPage"
                  :total-rows="buyOrders.length"
                  :per-page="perPage"
                  v-model="buyOrdersCurrentPage"></b-pagination>

    <strong class="float-right">{{ buyOrdersTotal | round(2) }} {{ market.quote_currency}}</strong>

    <b-table v-if="false"
             show-empty
             stacked="xs"
             :items="sellOrders"
             :fields="sellOrderFields"
             :current-page="sellOrdersCurrentPage"
             class="table-striped table-bordered table-sm table-sell"
             :empty-text="$t('order_book.no_sell_orders')"
             :per-page="perPage">
      <template slot="HEAD_actions" slot-scope="data">
        <div class="text-center">
          <icon name="star"></icon>
        </div>
      </template>
      <template slot="actions" slot-scope="order">
        <div class="text-center">
          <button class="btn btn-xs" v-if="order.item.uuids.length > 0"
                  v-b-tooltip.hover.html
                  :title="getTableCancelTooltipText(order.item)"
                  v-on:click="cancelOrders(order.item.uuids)">
            <icon name="times"></icon>
          </button>
        </div>
      </template>
      <template slot="rateField" slot-scope="order">
        <div v-b-tooltip.hover.html :title="getRowTooltipText(order.item)">
          {{order.item.rate | round(2)}}
        </div>
      </template>
      <template slot="quantityField" slot-scope="order">
        <div v-b-tooltip.hover.html :title="getRowTooltipText(order.item)">
          {{order.item.quantity | round(8)}}
        </div>
      </template>
      <template slot="totalField" slot-scope="order">
        <div v-b-tooltip.hover.html :title="getRowTooltipText(order.item)">
          {{order.item.total | round(2)}}
        </div>
      </template>
      <template slot="sumField" slot-scope="order">
        <div class="qbar" v-bind:style="{ width : getQbarWidthSell(order.item.sum) + 'px'}"></div>
        <div>
          {{order.item.sum | round(2)}}
        </div>
      </template>

    </b-table>

    <b-pagination class="mt-2 pagination-sm float-left"
                  v-if="sellOrders.length > perPage"
                  :total-rows="sellOrders.length"
                  :per-page="perPage"
                  v-model="sellOrdersCurrentPage"></b-pagination>
  </div>
</template>