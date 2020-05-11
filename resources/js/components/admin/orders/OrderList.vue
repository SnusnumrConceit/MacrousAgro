<template>
    <div>
        <v-simple-table v-show="! loading && orders.length" :height="750" fixed-header>
            <thead>
            <th v-for="header in table.headers" class="text-center" :key="header">
                {{ header }}
            </th>
            </thead>
            <tbody>
            <tr v-for="order in orders" :key="order.id">
                <td>
                    <v-btn text @click="displayModal(true, order.id)">
                        {{ $t('orders.number', {id: order.id}) }}
                    </v-btn>
                </td>
                <td>
                    <span v-if="order.customer">{{ order.customer.full_name }}</span>
                </td>
                <td>
                    <span :class="`${ getStatusIndication(order.order_status_code) }--text`">
                        {{ $t(`orders.statuses.${order.order_status_code}`) }}
                    </span>
                </td>
                <td>
                    <span v-if="order.invoice">{{ order.invoice.payment_amount }} руб. </span>
                </td>
                <td>
                    <span>
                        {{ order.display_created_at }}
                    </span>
                </td>
                <td class="text-right">
                    <v-tooltip top color="primary">
                        <template v-slot:activator="{ on }">
                            <v-icon v-on="on"
                                    small
                                    @click="$emit('show-order-detail-modal', {id: order.id})">
                                mdi-pencil
                            </v-icon>
                        </template>
                        <span>
                            {{ $t('tooltips.edit') }}
                        </span>
                    </v-tooltip>
                </td>
            </tr>
            </tbody>
        </v-simple-table>

        <span v-show="! loading && orders.length" class="d-flex flex-row-reverse">
            {{ $t('total', {total: pagination.total}) }}
        </span>

        <order-modal-detail :get-status-indication="getStatusIndication" @order-updated="refreshStatus"/>

        <v-skeleton-loader type="table-row-divider@6" v-show="loading" />

        <v-alert color="info" outlined v-if="! orders.length && ! loading">
            <span v-show="! searching">
                {{ $t('orders.no_orders') }}
            </span><span v-show="searching">
                {{ $t('no_results') }}
            </span>
        </v-alert>
    </div>
</template>

<script>
  import { mapActions } from 'vuex';

  import OrderModalDetail from './OrderModalDetail';

  import debounce from '../../../debounce';
  import scroll from '../../../mixins/infinite_scroll';

  export default {
    name: "OrderList",

    components: {
      OrderModalDetail
    },

    mixins: [scroll],

    data() {
      return {
        orders: [],

        pagination: {
          page: 1,
          last_page: 1,
          total: ''
        },

        table: {
          headers: [
            this.$t('orders.table.labels.id'),
            this.$t('orders.table.labels.customer'),
            this.$t('orders.table.labels.status'),
            this.$t('orders.table.labels.price'),
            this.$t('orders.table.labels.created_at'),
            ''
          ]
        },

        searching: false,
        loading: false,
      }
    },

    methods: {
      /**
       * Показ ошибок в форме
       */
      ...mapActions('errors', [
        'resetErrors',
        'setErrors'
      ]),

      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', [
        'showNotification'
      ]),

      /**
       * Получить список заказов
       *
       */
      async getOrders() {
        try {
          const response = await axios.get(`/orders`, {
            params: {
              page: this.pagination.page
            }
          });

          this.orders = (this.pagination.page === 1)
              ? response.data.orders.data
              : this.orders.concat(response.data.orders.data);

          this.pagination.last_page = response.data.orders.last_page;
          this.pagination.total = response.data.orders.total;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.data.message});
        }
      },

      /**
       * Обработчик события для debounce-поиска заказа
       */
      async onSearch({search, page}) {
        this.loading = true;

        if (page) {
          this.pagination.page = page;
        }

        this.searching = true;

        await this.searchData(this, search);

        this.loading = false;
      },

      /**
       * Поиск заказов
       */
      searchData: debounce((vm, search) => {
        axios.get(`/orders`, {
          params: {
            page: vm.pagination.page,
            keyword: search.keyword,
            created_at: search.created_at,
            status: search.status
          }
        }).then(response => {
          vm.orders = (vm.pagination.page === 1) ? response.data.orders.data : vm.orders.concat(response.data.orders.data);
          vm.pagination.last_page = response.data.orders.last_page;
          vm.pagination.total = response.data.orders.total;
        }).catch(error => console.error(error));
      }, 300),

      /**
       * Изменение статуса в списке заказов
       *
       * @param status
       * @param id
       */
      refreshStatus({status, id}) {
        this.orders.forEach(order => {
          if (order.id === id) {
            order.order_status_code = status;
          }
        })
      },

      /**
       * Последовательная загрузка данных с дальнейшей инициализацией
       *
       * @returns {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getOrders();

        this.loading = false;
      },

      /**
       * Обработчик события скролла
       */
      onScroll: function () {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0], 'getOrders');
      },

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
            return 'success';
          case 'canceled' :
            return 'error';
          case 'delivery' :
            return 'primary';
          case 'payed'    :
            return 'primary';
        }
      }
    },

    created() {
      this.initData();
    },

    mounted() {
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);

      this.$root.$on('order-searching', this.onSearch)
    },

    destroyed() {
      $('.v-data-table__wrapper')[0].removeEventListener('scroll', this.onScroll);
    }
  }
</script>