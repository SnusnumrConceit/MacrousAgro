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
                        <v-data-table :items="orders" :headers="headers">
                            <template v-slot:body="{ items }">
                                <tbody>
                                    <tr v-for="item in items" :key="item.id">
                                        <td>{{ item.products.title }}</td>
                                        <td>{{ item.price }}</td>
                                        <td>{{ item.status_code.description }}</td>
                                        <td>
                                            <v-icon
                                                    small
                                                    @click="remove(item)"
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
                        </v-data-table>
                        <h3 class="text-right">Итого: 1000 руб.</h3>
                        <v-card-actions>
                            <v-btn outlined color="success">Оплатить</v-btn>
                            <v-btn outlined color="error">Отменить</v-btn>
                        </v-card-actions>
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
  export default {
    name: "cabinet",

    data() {
      return {
        table: {
          header: this.$t('orders.table.header')
        },

        tabs: [
          {
            name: 'Текущие'
          },
          {
            name: 'Завершённые'
          }
        ],

        selectedTab: 'Текущие',

        headers: [
          {
            text: 'Наименование',
            value: `product.name`,
            align: 'center'
          },
          {
            text: 'Стоимость',
            value: 'price',
            align: 'center'
          },
          {
            text: 'Статус',
            value: `status_code.id`,
            align: 'center'
          },
          {
            text: '',
            value: '',
            align: 'center'
          }
        ],

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

    methods: {
      remove(item) {
        this.orders = this.orders.filter(order => order !== item);
      }
    }
  }
</script>

<style scoped>
    .cabinet-container {
        max-height: 100%;
        max-width: 100%;
    }
</style>