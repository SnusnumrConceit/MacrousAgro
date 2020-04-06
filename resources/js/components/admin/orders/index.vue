<template>
    <div>
        <v-card>
            <v-toolbar>
                <v-toolbar-title>
                    {{ $t('orders.table.header')}}
                </v-toolbar-title>

                <v-divider class="mx-4" vertical inset></v-divider>
                <v-spacer></v-spacer>

                <v-text-field v-model="search.keyword"
                              @keyup.enter="onSearch"
                              append-icon="search"
                              label="Поиск"
                              single-line>
                </v-text-field>
            </v-toolbar>

            <v-card-text>
                <v-simple-table v-show="! loading && orders.length">
                    <thead>
                        <th v-for="header in table.headers">
                            {{ header }}
                        </th>
                    </thead>
                    <tbody>
                    <tr v-for="order in orders" :key="order.id">
                        <td>
                            {{ order.customer.full_name }}
                        </td>
                        <td>
                            {{ order.status.description }}
                        </td>
                        <td>
                            {{ order.invoice.payment_amount }} руб.
                        </td>
                        <td class="text-right">
                            <v-tooltip top color="primary">
                                <template v-slot:activator="{ on }">
                                    <v-icon v-on="on"
                                            small
                                            @click="$router.push(`/admin/orders/${order.id}`)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                </template>
                                <span>
                                        Править
                                    </span>
                            </v-tooltip>

                            <!--<v-tooltip top color="error">-->
                                <!--<template v-slot:activator="{ on }">-->
                                    <!--<v-icon color="red"-->
                                            <!--v-on="on"-->
                                            <!--small-->
                                            <!--@click="remove(order.id)"-->
                                    <!--&gt;-->
                                        <!--mdi-delete-->
                                    <!--</v-icon>-->
                                <!--</template>-->
                                <!--<span>-->
                                        <!--Удалить-->
                                    <!--</span>-->
                            <!--</v-tooltip>-->
                        </td>
                    </tr>
                    </tbody>
                </v-simple-table>

                <!--<span class="d-flex flex-row-reverse" v-show="! loading && orders.length">-->
                    <!--Всего {{ pagination.total }}-->
                <!--</span>-->

                <v-skeleton-loader type="table-row-divider@6" v-show="loading"></v-skeleton-loader>

                <v-alert color="info" outlined v-if="! orders.length && ! loading">
                    <div class="">
                        <span v-show="! searching">
                            Заказы отсутствуют в системе
                        </span>
                        <span v-show="searching">
                            По Вашему запросу ничего не найдено
                        </span>
                    </div>
                </v-alert>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import debounce from '../../../debounce';

  import infiniteScrollMixin from '../../../mixins/infinite_scroll';

  export default {
    name: "index",

    mixins: [infiniteScrollMixin],

    data() {
      return {

        status_codes: [],
        orders: [],

        order: {
          products: [],
          status_code: '',
          customer: ''
        },

        pagination: {
          page: 1,
          last_page: 1,
          total: ''
        },

        search: {
          keyword: '',
          created_at: ''
        },

        table: {
          headers: [
            'Наименование Товара',
            'Статус',
            'Стоимость',
            ''
          ]
        },

        searching: false,
        loading: false,
        modal: false,
      }
    },

    methods: {

      async save() {
        try {
          const response = await axios.post(`${this.$attrs.apiRoute}/orders`, {
            order: this.order
          });

          this.$swal('Успешно!', response.data.msg, 'success');
        } catch (e) {
          this.$swal('Ошибка!', e.message, 'error');
        }
      },

      async remove(id) {
        try {
          const response = await axios.delete(`${this.$attrs.apiRoute}/orders/${id}`);

          this.$swal('Успешно!', response.data.msg, 'success');
        } catch (e) {
          this.$swal('Ошибка!', e.message, 'error');
        }
      },

      cancel() {
        this.modal = false;
        this.$refs.form.reset();
      },

      async loadStatusCodes() {
        const response = await axios.get('/api/order_status_codes');

        console.log('statuses', response.data);
        this.status_codes = response.data;
      },

      async loadOrders() {
        const response = await axios.get(`${this.$attrs.apiRoute}/orders`, {
          params: {
            page: this.page
          }
        });

        console.log('orders', response.data);
        this.orders = response.data.orders;
        this.pagination.page = response.data.last_page;

        console.log(this.orders);
      },

      async searchOrders() {
        const response = await axios.get(`${this.$attrs.apiRoute}/orders/search`, {
          params: {
            page: this.page,
            search: this.search
          }
        });

        this.orders = response.data.orders;
        this.pagination.page = response.data.last_page;
      },

      async initData() {
        this.loading = true;

        await this.loadStatusCodes();

        await this.loadOrders();

        this.loading = false;
      },

      onScroll: function() {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0]);
      },
    },

    created() {
      this.initData();
    },

    mounted() {
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);
    }


  }
</script>

<style scoped>

</style>