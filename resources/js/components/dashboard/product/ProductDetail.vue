<template>
    <div>
        <v-card v-show="! loading">
            <v-row>
                <v-col cols="9">
                    <v-parallax :src="product.src || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'">
                        <v-card-title>
                            {{ product.title }}
                        </v-card-title>
                    </v-parallax>
                    <v-card-text class="text-justify body-1">
                        {{ product.description }}
                    </v-card-text>
                </v-col>
                <v-col cols="3" class="d-flex justify-center align-center border-1">
                    <h3 class="display-3">
                        {{ product.price }} руб.
                    </h3>
                    <v-card-actions>
                        <v-btn outlined color="success" @click="addToCart(product)" v-if="isCustomer">
                            {{ $t('buttons.to_cart') }}
                        </v-btn>
                        <v-btn outlined color="default" @click="$router.go(-1)">
                            {{ $t('buttons.back') }}
                        </v-btn>
                    </v-card-actions>
                </v-col>
            </v-row>
        </v-card>
        <v-skeleton-loader v-show="loading" type="card" />
    </div>
</template>

<script>
  import {mapGetters, mapActions} from 'vuex';

  export default {
    name: "detail",

    data() {
      return {
        product: {},

        loading: false
      }
    },

    computed: {
      ...mapGetters('auth', {
        'isCustomer': 'isCustomer'
      }),

      ID() {
        return this.$route.params.id;
      }
    },

    methods: {
      /**
       * Получить детальную информацию о товаре
       *
       * @return {Promise<void>}
       */
      async getProduct() {
        const response = await axios.get(`${this.$attrs.apiRoute}/products/${this.ID}`);

        this.product = response.data.product;
      },

      ...mapActions('cart', [
        'addToCart'
      ]),

      /**
       * Инициализация данных компонента
       *
       * @return {Promise<void>}
       */
      async initData() {
        this.loading = true;

        await this.getProduct();

        this.loading = false;
      }
    },

    created() {
      this.initData();
    }
  }
</script>