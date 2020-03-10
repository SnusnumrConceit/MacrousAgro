<template>
    <div class="cabinet-container">
        <v-card>
            <v-card-title>
                <h2> Заказы </h2>
            </v-card-title>
            <v-card-text>
                <v-tabs grow>
                    <v-tab v-for="tab in tabs" :key="tab.name" v-model="selectedTab">
                        {{ tab.name }}
                    </v-tab>

                    <v-tab-item>
                        <div v-if="order.products.length">
                            <v-simple-table>
                                <template v-slot:default>
                                    <thead>
                                    <th v-for="header in table.headers" :key="header" class="text-left">
                                        {{ header }}
                                    </th>
                                    </thead>
                                    <tbody>
                                    <tr v-for="product in order.products" :key="product.id" v-if="order.products.length">
                                        <td class="text-left">{{ product.title }}</td>
                                        <td class="text-left">{{ product.price }}</td>
                                        <td>
                                            <v-icon
                                                    small
                                                    @click="removeFromCart(product)"
                                            >
                                                mdi-delete
                                            </v-icon>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>

                                <template v-slot:no-data>
                                    У Вас нет заказов
                                </template>
                            </v-simple-table>
                            <h3 class="text-right" v-if="order.products.length">Итого: {{ order.price }} руб.</h3>

                            <v-card-actions>
                                <v-btn outlined color="success">Оплатить</v-btn>
                                <v-btn outlined color="error" @click="removeOrder">Отменить</v-btn>
                            </v-card-actions>
                        </div>
                        <v-banner single-line v-else>
                            У Вас нет текущих заказов
                        </v-banner>
                    </v-tab-item>

                    <v-tab-item>
                        <v-banner single-line>
                            Нет ни одного заказа в обработке
                        </v-banner>
                    </v-tab-item>

                    <v-tab-item>
                        <v-banner
                                single-line
                        >
                            Завершённые заказы отсутствуют
                        </v-banner>
                    </v-tab-item>
                </v-tabs>


            </v-card-text>
        </v-card>


        <!--<v-parallax-->
        <!--dark-->
        <!--src="https://cdn.vuetifyjs.com/images/backgrounds/vbanner.jpg"-->
        <!--&gt;-->

        <!--</v-parallax>-->
    </div>
</template>

<script>
  import {mapGetters, mapActions} from 'vuex';

  export default {
    name: "cabinet",

    data() {
      return {
        table: {
          header: this.$t('orders.table.header'),
          headers: [
            'Наименование',
            'Стоимость',
            // 'Статус',
            ''
          ],
        },

        tabs: [
          {
            name: 'Текущие'
          },
          {
            name: 'В обработкке'
          },
          {
            name: 'Завершённые'
          }
        ],

        selectedTab: 'Текущие',

        orders: [
          {
            id: 1024,
            products: {
              title: 'biba'
            },
            price: '1000',
            status_code: {
              id: 1,
              description: 'В обработке'
            }
          },
          {
            id: 1023,
            products: {
              title: 'foo 235'
            },
            price: '1200',
            status_code: {
              id: 1,
              description: 'В обработке'
            }
          },
        ],

        loading: true
      }
    },

    computed: {
      ...mapGetters('cart', {
        'order': 'order'
      }),
    },

    methods: {
      ...mapActions('cart', [
        'removeOrder',
        'removeFromCart'
      ])
    }
  }
</script>

<style scoped>
    .cabinet-container {
        max-height: 100%;
        max-width: 100%;
    }
</style>