<template>
    <v-dialog v-model="show" max-width="850px" v-if="show" scrollable persistent>
        <v-skeleton-loader type="card" v-show="loading" />>

        <v-card v-if="! loading">
            <v-card-title>
                <v-row>
                    <v-col cols="6" class="text-left">
                        {{ $t('orders.number',{id: order.id})}}
                    </v-col>

                    <v-col>
                        <v-alert :color="getStatusIndication(order.order_status_code)" class="float-right" outlined>
                            {{ $t(`orders.statuses.${order.order_status_code}`).toUpperCase() }}
                        </v-alert>
                    </v-col>
                </v-row>
            </v-card-title>

            <v-card-text>
                <errors></errors>
                <v-row>
                    <v-col cols="2">
                        <v-avatar color="primary">
                            <span class="white--text headline">{{ getCustomerInitials(order.customer.full_name) }}</span>
                        </v-avatar>
                    </v-col>
                    <v-col cols="10" class="text-left">
                        <div>{{ order.customer.full_name }}</div>

                        <div>{{ order.customer.email }}</div>
                    </v-col>
                </v-row>

                <v-simple-table v-if="order.positions.length">
                    <thead>
                    <th v-for="header in table.headers" :key="header" class="text-center">
                        {{ header }}
                    </th>
                    </thead>
                    <tbody>
                    <tr v-for="position in order.positions" :key="position.id">
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
                    {{ $t('orders.no_products') }}
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

                <v-btn outlined color="default" @click="hide()">
                    {{ $t('buttons.close') }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import {mapActions} from 'vuex';

  export default {
    name: "OrderModalDetail",

    props: {
      getStatusIndication: {
        type: Function,
        required: true
      }
    },

    data() {
      return {
        order: {
          products: [],
          order_status_code: '',
          customer: ''
        },

        table: {
          headers: [
            this.$t('orders.table.labels.product_name'),
            this.$t('orders.table.labels.status'),
            this.$t('orders.table.labels.price'),
            ''
          ]
        },

        icons: {
          completed: 'mdi-check-outline',
          created: 'mdi-clipboard-outline',
          canceled: 'mdi-cancel',
          delivery: 'mdi-truck-delivery-outline',
          payed: 'mdi-cash',
        },

        status_codes: [],

        show: false,
        loading: false
      }
    },

    computed: {

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
       * Загрузить детальную информацию о заказе
       *
       * @param id
       * @returns {Promise<void>}
       */
      async getOrder(id) {
        try {
          const response = await axios.get(`/orders/${id}`);

          this.order = response.data.order;
        } catch (e) {

        } finally {
          this.loading = false;
        }
      },

      /**
       * Получить список статус кодов
       */
      async getStatusCodes() {
        try {
          const response = await axios.get('/order_status_codes');
          this.status_codes = response.data;
        } catch (e) {
          this.showNotification({ type: 'error', message: e.data.message});
        }
      },

      /**
       * Смена статуса как заказа целиком, так и конкретной позиции
       *
       * @param String  status
       * @param Number  itemId
       *
       * @returns {Promise<void>}
       */
      async changeStatus(status, itemId = null) {
        try {
          const route = itemId ? `/order_items/${itemId}`: `/orders/${this.order.id}`;
          const response = await axios.patch(route, {
            [`order_${itemId ? 'item_' : ''}status_code` ]: status
          });

          this.showNotification({ type: 'success', message: response.data.message });

          if (! itemId) {
            this.$emit('order-updated', { status: status, id: this.order.id });
          }

          this.hide();
        } catch (e) {
          this.setErrors(e.response.data.errors);
        }
      },

      /**
       * Обнуление детального заказа
       */
      clearModal() {
        this.order = {};
        this.resetErrors();
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
       * Закрыть модальное окно
       *
       * Close dialog window
       *
       */
      hide() {
        this.show = false;
        this.clearModal();
      },

      /**
       * Последовательная загрузка данных с дальнейшей инициализацией
       *
       * @param id
       * @returns {Promise<void>}
       */
      async initData({id}) {
        this.loading = true;

        this.show = true;

        await this.getOrder(id);

        await this.getStatusCodes();

        this.loading = false;
      }
    },

    mounted() {
      this.$parent.$on('show-order-detail-modal', this.initData)
    }
  }
</script>