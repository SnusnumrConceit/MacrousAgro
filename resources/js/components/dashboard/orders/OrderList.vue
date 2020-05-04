<template>
    <div v-if="orders.length">
        <v-card v-for="order in orders" :key="order.id" class="mb-4">
            <v-card-title>
                <v-badge :color="getStatusIndication(order.order_status_code)" :content="$t(`orders.statuses.${order.order_status_code}`)">
                    {{ $t('orders.number', { id: order.id }) }}
                </v-badge>
            </v-card-title>

            <v-card-text>
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                        <th v-for="header in headers" :key="header" class="text-left">
                            {{ header }}
                        </th>
                        </thead>
                        <order-products-list :positions="positions(order)"></order-products-list>
                    </template>

                    <template v-slot:no-data>
                        {{ $t('orders.no_products')}}
                    </template>
                </v-simple-table>
                <h3 class="text-right" v-if="order.invoice.payment_amount.length">
                    {{ $t('orders.amount_price', {'price': order.invoice.payment_amount}) }}
                </h3>
            </v-card-text>
        </v-card>
    </div>
    <v-banner single-line v-else>
        Заказы отсутствуют
    </v-banner>
</template>

<script>
  import OrderProductsList from './OrderProductsList';

  export default {
    name: "OrderList",

    components: {
      OrderProductsList
    },

    props: {
      orders: {
        default: [],
        required: true
      },

      headers: {
        default: [],
        required: true
      }
    },

    computed: {
      positions() {
        return order => order.positions ? order.positions : [];
      }
    },

    methods: {
      /**
       * Получить стиль индикации статуса в зависимости от статуса
       *
       * @param status
       * @returns {string}
       */
      getStatusIndication(status) {
        switch (status) {
          case 'completed':
            return 'success';
          case 'created':
            return 'primary';
          case 'canceled' :
            return 'error';
          case 'delivery' :
            return 'info';
          case 'payed'    :
            return 'purple';
        }
      },
    }
  }
</script>

<style scoped>

</style>