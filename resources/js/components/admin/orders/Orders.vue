<template>
    <div style="height: 100%">
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

                <v-spacer></v-spacer>

                <v-select v-model="search.status"
                          clearable
                          :items="status_codes"
                          label="Статус">
                    <template v-slot:item="{item}">
                        <v-list>
                        <v-list-item>
                            <v-list-item-content>
                                <v-list-item-title>
                                    {{ $t(`orders.statuses.${item}`) }}
                                </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        </v-list>
                    </template>
                </v-select>

                <v-spacer></v-spacer>

                <v-menu ref="search-calendar-menu"
                        v-model="calendar"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="200px">
                    <template v-slot:activator="{ on }">
                        <v-text-field
                                v-on="on"
                                v-model="search.display_created_at"
                                label="Дата создания"
                                prepend-icon="event"
                                hint="Дата создания"
                                dense
                                readonly
                                clearable
                                persistent-hint
                                single-line

                        ></v-text-field>
                    </template>

                    <v-date-picker :locale="$i18n.locale"
                                   width="290"
                                   no-title
                                   scrollable
                                   first-day-of-week="1"
                                   color="primary"
                                   v-if="calendar"
                                   v-model="search.created_at">
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" @click="calendar = false" text>
                            {{ $t('users.btn.cancel') }}
                        </v-btn>
                        <v-btn color="primary" outlined @click="calendar = false">OK</v-btn>
                    </v-date-picker>
                </v-menu>

                <v-spacer></v-spacer>

                <v-btn color="success"
                       outlined
                       @click="exportOrders">
                    Экспорт
                </v-btn>

            </v-toolbar>

            <v-card-text>
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
                                Заказ #{{order.id}}
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
                                            @click="displayModal(true, order.id)">
                                        mdi-pencil
                                    </v-icon>
                                </template>
                                <span>
                                    Править
                                </span>
                            </v-tooltip>
                        </td>
                    </tr>
                    </tbody>
                </v-simple-table>
                <span v-show="! loading && orders.length" class="d-flex flex-row-reverse">
                    Всего: {{ pagination.total }}
                </span>

                <v-dialog v-model="modal" max-width="850px" v-if="modal.display" scrollable persistent>
                    <v-skeleton-loader type="card" v-show="modal.loading"></v-skeleton-loader>>

                    <v-card v-if="! modal.loading">
                        <v-card-title>
                            <v-row>
                                <v-col cols="6" class="text-left">
                                    Заказ #{{ modal.order.id }}
                                </v-col>

                                <v-col>
                                    <v-alert :color="getStatusIndication(modal.order.order_status_code)" class="float-right" outlined>
                                        {{ $t(`orders.statuses.${modal.order.order_status_code}`).toUpperCase() }}
                                    </v-alert>
                                </v-col>
                            </v-row>
                        </v-card-title>

                        <v-card-text>
                            <errors></errors>
                            <v-row>
                                <v-col cols="2">
                                    <v-avatar color="primary">
                                        <span class="white--text headline">{{ getCustomerInitials(modal.order.customer.full_name) }}</span>
                                    </v-avatar>
                                </v-col>
                                <v-col cols="10" class="text-left">
                                    <div>{{ modal.order.customer.full_name }}</div>

                                    <div>{{ modal.order.customer.email }}</div>
                                </v-col>
                            </v-row>

                            <v-simple-table v-if="modal.order.positions.length">
                                <thead>
                                    <th v-for="header in table.detailHeaders" :key="header" class="text-center">
                                        {{ header }}
                                    </th>
                                </thead>
                                <tbody>
                                    <tr v-for="position in modal.order.positions" :key="position.id">
                                        <td v-if="position.product">
                                            {{ position.product.title }}
                                        </td>
                                        <td>
                                            <span :class="`${ getStatusIndication(position.order_item_status_code) }--text`">
                                                {{ $t(`orders.statuses.${position.order_item_status_code}`) }}
                                            </span>
                                        </td>
                                        <td v-if="position.product">
                                            {{ position.product.price }}
                                        </td>
                                        <td>
                                            <v-btn outlined
                                                   small
                                                   icon
                                                   text
                                                   :color="getStatusIndication(status)"
                                                   @click="changeStatus(status, position.id)"
                                                   v-for="status in status_codes"
                                                   :key="status">
                                                <v-tooltip top :color="getStatusIndication(status)">
                                                    <template v-slot:activator="{ on }">
                                                        <v-icon v-on="on">
                                                            {{ icons[status]}}
                                                        </v-icon>
                                                    </template>
                                                    <span class="white--text">
                                                        {{ $t(`orders.statuses.${status}`) }}
                                                    </span>
                                                </v-tooltip>
                                            </v-btn>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-simple-table>

                            <v-alert color="primary" outlined v-else>
                                Позиции по заказу отсутствуют
                            </v-alert>
                        </v-card-text>

                        <v-card-actions>
                            <v-btn outlined
                                   :color="getStatusIndication(status)"
                                   @click="changeStatus(status)"
                                   v-for="status in status_codes"
                                   :key="status">
                                {{ $t(`orders.statuses.${status}`) }}
                                <v-icon>
                                    {{ icons[status]}}
                                </v-icon>
                            </v-btn>

                            <v-spacer></v-spacer>

                            <v-btn outlined color="default" @click="displayModal(false)">
                                Закрыть
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

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

  import { mapActions } from 'vuex';

  export default {
    name: "index",

    mixins: [infiniteScrollMixin],

    data() {
      return {

        status_codes: [],
        orders: [],

        order: {
          products: [],
          order_status_code: '',
          customer: ''
        },

        pagination: {
          page: 1,
          last_page: 1,
          total: ''
        },

        search: {
          keyword: '',
          created_at: null,
          display_created_at: new Date().toLocaleString('ru', {year: 'numeric', month: 'numeric', day: 'numeric', timezone: 'utc'}),
          status: ''
        },

        table: {
          headers: [
            'Идентификатор заказа',
            'Покупатель',
            'Статус',
            'Стоимость',
            'Дата заказа',
            ''
          ],

          detailHeaders: [
            'Наименование товара',
            'Статус',
            'Стоимость',
            ''
          ]
        },

        calendar: false,
        searching: false,
        loading: false,
        modal: {
          display: false,
          loading: true,
          order: {}
        },

        icons: {
          completed: 'mdi-check-outline',
          created: 'mdi-clipboard-outline',
          canceled: 'mdi-cancel',
          delivery: 'mdi-truck-delivery-outline',
          payed: 'mdi-cash',
        },
      }
    },

    methods: {
      /**
       * Показ ошибок в форме
       */
      ...mapActions('errors', {
        'resetErrors': 'resetErrors',
        'setErrors': 'setErrors'
      }),

      /**
       * Показ / обнуление уведомлений
       */
      ...mapActions('notifications', {
        'showNotification': 'showNotification',
        'hideNotification': 'hideNotification'
      }),

      async save() {
        try {
          const response = await axios.post(`${this.$attrs.apiRoute}/orders`, {
            order: this.order
          });

          this.showNotification({ type: 'success', message: response.data.message});
        } catch (e) {
          this.setErrors(e.response.data.error);
        }
      },

      cancel() {
        this.modal = false;
        this.$refs.form.reset();
        this.resetErrors();
      },

      /**
       * Получить список статус кодов
       */
      async getStatusCodes() {
        try {
          const response = await axios.get('/api/order_status_codes');
          this.status_codes = response.data;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.data.message});
        }
      },

      /**
       * Получить список заказов
       *
       */
      async getOrders() {
        try {
          const response = await axios.get(`${this.$attrs.apiRoute}/orders`, {
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
       * Экспорт заказов
       */
      exportOrders() {
        axios({
          url: `${this.$attrs.apiRoute}/orders/export`,
          method: 'POST',
          responseType: 'blob', // important
        }).then((response) => {
          console.log(response.data);
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'orders.xlsx');
          document.body.appendChild(link);
          link.click();
        }).catch(e => {
          this.showNotification({ type: 'error', message: e.data.message});
        });
      },

      onSearch() {
        this.searchData(this);
      },

      searchData: debounce(vm => {
        axios.get(`${vm.$attrs.apiRoute}/orders`, {
          params: {
            page: vm.pagination.page,
            keyword: vm.search.keyword,
            created_at: vm.search.created_at,
            status: vm.search.status
          }
        }).then(response => {
          vm.orders = (vm.pagination.page === 1) ? response.data.orders.data : vm.orders.concat(response.data.orders.data);
          vm.pagination.last_page = response.data.orders.last_page;
          vm.pagination.total = response.data.orders.total;
        }).catch(error => console.error(error));
      }, 300),

      async initData() {
        this.loading = true;

        await this.getStatusCodes();

        await this.getOrders();

        this.loading = false;
      },

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
      },

      /**
       * Показать модальное окно с детальной информацией
       *
       * @param show
       */
      displayModal(show, id = null) {
        this.$emit(show ? 'fetch-order-detail' : 'clear-modal', id);
        this.modal.display = show;

        if (! show) {
          this.modal.loading = ! show;
        }
      },

      /**
       * Загрузить детальную информацию о заказе
       *
       * @param id
       * @returns {Promise<void>}
       */
      async getOrderDetail(id) {
        const response = await axios.get(`${this.$attrs.apiRoute}/orders/${id}`);

        this.modal.order = response.data.order;

        this.modal.loading = false;
      },

      /**
       * Обнуление детального заказа
       */
      clearOrderDetail() {
        this.modal.order = {};
      },

      /**
       * Форматирование инициалов
       *
       * @param name
       * @returns {string}
       */
      getCustomerInitials(name) {
        return name.split(' ').map((name) => (name[0])).join('');
      },

      /**
       * Смена статуса как заказа целиком, так и конкретной позиции
       *
       * @param String  status
       * @param Boolean isItem
       * @param Number  itemId
       * @returns {Promise<void>}
       */
      async changeStatus(status, itemId = null) {
        try {
          const route = itemId ? `${this.$attrs.apiRoute}/order_items/${itemId}`: `${this.$attrs.apiRoute}/orders/${this.modal.order.id}`;
          const response = await axios.patch(route, {
            [`order_${itemId ? 'item_' : ''}status_code` ]: status
          });

          this.$swal('Успешно!', 'Статус успешно изменён!', 'success');
          this.displayModal(false);
        } catch (e) {
          this.errors = e.response.data.errors;
        }
      }

    },

    watch: {
      'search': {
        handler: function(after, before) {
          this.pagination.page = 1;
          this.searching = true;
          if (after.status || after.created_at || after.keyword.length) {
            this.searchData(this);
          } else if (! after.status && ! after.created_at && ! after.keyword.length) {
            this.getOrders();
          }
        },

        deep: true
      },

      'search.keyword': function (after, before) {
        if (after.length > 3) {
          this.onSearch();
        }
      },

      'search.created_at': function (after, before) {
        if (after !== null) {
          this.search.display_created_at = after.split('-').reverse().join('.');
        }
      },

      'search.display_created_at': function (after, before) {
        if (after === null) {
          this.search.created_at = null;
        }
      }
    },

    created() {
      this.initData();
    },

    mounted() {
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);

      this.$on('fetch-order-detail', this.getOrderDetail);

      this.$on('clear-modal', this.clearOrderDetail);
    },

    beforeDestroy() {
      this.hideNotification();
    }
  }
</script>

<style scoped>

</style>