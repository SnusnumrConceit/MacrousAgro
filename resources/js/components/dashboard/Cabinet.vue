<template>
    <div class="cabinet-container">
        <v-card v-if="! loading">
            <v-card-title>
                <h2> {{ $t('orders.orders') }} </h2>
            </v-card-title>
            <v-card-text>
                <v-tabs grow>
                    <v-tab v-for="tab in tabs" :key="tab.name" v-model="selectedTab">
                        {{ tab.name }}
                    </v-tab>

                    <v-tab-item>
                        <div v-if="order.positions.length">
                            <v-simple-table>
                                <template v-slot:default>
                                    <thead>
                                    <th v-for="header in table.headers" :key="header" class="text-left">
                                        {{ header }}
                                    </th>
                                    </thead>
                                    <order-products-list :positions="order.positions"
                                                         :can-edit="true"
                                                         @order-position-removed="removeFromCart" />
                                </template>

                                <template v-slot:no-data>
                                    {{ $t('orders.no_products')}}
                                </template>
                            </v-simple-table>
                            <h3 class="text-right" v-if="order.positions.length">
                                {{ $t('orders.amount_price', {price: order.price}) }}
                            </h3>

                            <v-card-actions>
                                <v-btn outlined color="success" @click="createOrder">
                                    {{ $t('buttons.pay') }}
                                </v-btn>
                                <v-btn outlined color="error" @click="removeOrder">
                                    {{ $t('buttons.cancel') }}
                                </v-btn>
                            </v-card-actions>
                        </div>
                        <v-banner single-line v-else>
                            {{ $t('orders.no_orders')}}
                        </v-banner>
                    </v-tab-item>

                    <v-tab-item>
                        <order-list :orders="orders.active.data" :headers="table.headers" />
                    </v-tab-item>

                    <v-tab-item>
                        <order-list :orders="orders.completed.data" :headers="table.headers" />
                    </v-tab-item>
                </v-tabs>


            </v-card-text>
        </v-card>

        <v-skeleton-loader type="card" v-else />
    </div>
</template>

<script>
  import OrderList from './orders/OrderList';
  import OrderProductsList from './orders/OrderProductsList';

  import {mapGetters, mapActions} from 'vuex';

  export default {
    name: "cabinet",

    components: {
      OrderList,
      OrderProductsList
    },

    data() {
      return {
        table: {
          header: this.$t('orders.table.header'),
          headers: [
            this.$t('cart.table.headers.title'),
            this.$t('cart.table.headers.price'),
            ''
          ],
        },

        tabs: [
          {
            name: this.$t('orders.tabs.pre_order') //'На оформлении'
          },
          {
            name: this.$t('orders.tabs.active') //'Активные'
          },
          {
            name: this.$t('orders.tabs.completed') //'Завершённые'
          }
        ],

        selectedTab: this.$t('orders.tabs.pre_order'),
      }
    },

    computed: {
      ...mapGetters('cart', [
        'order',
        'orders',
        'loading'
      ]),
    },

    methods: {
      ...mapActions('cart', [
        'removeOrder',
        'removeFromCart',
        'createOrder',
        'getOrders'
      ])
    },

    created() {
      this.getOrders();
    }
  }
</script>

<style scoped>
    .cabinet-container {
        max-height: 100%;
        max-width: 100%;
    }
</style>