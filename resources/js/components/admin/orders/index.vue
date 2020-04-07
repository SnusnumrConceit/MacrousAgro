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

                <v-spacer></v-spacer>

                <v-select v-model="search.status" :items="Object.keys(status_codes)" label="Статус" name="status" item-text="name">
                    <!--<template v-slot:item="{item, index}">-->
                        <!--<v-list-item>-->
                            <!--<v-list-item-content>-->
                                <!--<v-list-item-title>-->
                                    <!--{{ $t(`orders.statuses.${status_codes[index]}`) }}-->
                                <!--</v-list-item-title>-->
                            <!--</v-list-item-content>-->
                        <!--</v-list-item>-->
                    <!--</template>-->
                </v-select>

                <v-spacer></v-spacer>

                <v-text-field
                        @click="calendar = true"
                        v-model="search.created_at"
                        label="Дата публикации"
                        prepend-icon="event"
                        hint="Формат даты: ГГГГ-ММ-ДД"
                        dense
                        readonly
                        clearable
                        persistent-hint
                        single-line

                ></v-text-field>

                <v-date-picker :locale="$i18n.locale"
                               width="550"
                               :style="{position: 'absolute', right: '10%', top: '100%', 'z-index': 3}"
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
                                        <!--<td :colspan="table.detailHeaders.length" class="text-center">-->

                                        <!--</td>-->

                                        <td>
                                            {{ position.product.title }}
                                        </td>
                                        <td>
                                            <span :class="`${ getStatusIndication(position.order_item_status_code) }--text`">
                                                {{ $t(`orders.statuses.${position.order_item_status_code}`) }}
                                            </span>
                                        </td>
                                        <td>
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
          created_at: '',
          status: ''
        },

        table: {
          headers: [
            'Идентификатор заказа',
            'Покупатель',
            'Статус',
            'Стоимость',
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
        }
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
        this.status_codes = response.data;
      },

      async loadData() {
        const response = await axios.get(`${this.$attrs.apiRoute}/orders`, {
          params: {
            page: this.pagination.page
          }
        });

        this.orders = (this.pagination.page === 1) ? response.data.orders.data : this.orders.concat(response.data.orders.data);
        this.pagination.last_page = response.data.orders.last_page;
        this.pagination.total = response.data.orders.total;
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

        await this.loadStatusCodes();

        await this.loadData();

        this.loading = false;
      },

      onScroll: function () {
        this.paginationScroll(this, $('.v-data-table__wrapper')[0]);
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
      async loadOrderDetail(id) {
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
        const route = itemId ? `${this.$attrs.apiRoute}/order_items/${itemId}`: `${this.$attrs.apiRoute}/orders/${this.modal.order.id}`;
        // const response = await axios.put(`${this.$attrs.apiRoute}/orders/${this.modal.order.id}${itemId ? `/item/${itemId}` : ''}`, {
        const response = await axios.patch(route, {
           [`order_${itemId ? 'item_' : ''}status_code` ]: status
        });

        switch (response.status) {
          case 200: this.$swal('Успешно!', 'Статус успешно изменён!', 'success'); break;
          case 500: this.$swal('Ошибка!', `${response.data.msg}`, 'error'); return;
        }

        this.displayModal(false);
      }

    },

    watch: {
      'search': {
        handler: function(after, before) {
          if (after.status || after.created_at || after.keyword.length) {
            this.pagination.page = 1;
            this.searching = true;

            this.searchData(this);
          }
        },

        deep: true
      },

      'search.keyword': function (after, before) {
        if (after.length > 3) {
          this.onSearch();
        }
      }
    },

    created() {
      this.initData();
    },

    mounted() {
      $('.v-data-table__wrapper')[0].addEventListener('scroll', this.onScroll);

      this.$on('fetch-order-detail', this.loadOrderDetail);

      this.$on('clear-modal', this.clearOrderDetail);
    }


  }
</script>

<style scoped>

</style>