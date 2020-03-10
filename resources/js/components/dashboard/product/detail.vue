<template>
    <div>
        <v-card>
            <v-card-title>
                {{ product.title }}
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col cols="6">
                        <v-img :src="product.url"></v-img>
                        <h3>
                            {{ product.price }} руб.
                        </h3>
                    </v-col>
                    <v-col cols="6">
                        <h3>
                            {{ product.title }}
                        </h3>
                        <p>
                            {{ product.description }}
                        </p>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn outlined color="success" @click="addToCart(product)">
                    В корзину
                </v-btn>
                <v-btn outlined color="default" @click="$router.go(-1)">
                    Назад
                </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
  import {mapGetters, mapActions} from 'vuex';

  export default {
    name: "detail",

    data() {
      return {
        product: {}
      }
    },

    computed: {
      ID() {
        return this.$route.params.id;
      }
    },

    methods: {
      async loadData() {
        const response = await axios.get(`${this.$attrs.apiRoute}/products/${this.ID}`);

        this.product = response.data.product;
      },

        ...mapActions('cart', [
          'addToCart'
        ]),
    },

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>