<template>
    <v-card v-if="products.length" style="background: none;" class="text--white">
        <v-card-title>
            <h2 class="font-weight-black display-3" style="color: #2fff82; text-shadow: 5px 5px #558ABB">
                {{ $t('landing.production')}}
            </h2>
        </v-card-title>
        <v-card-text>
            <product-list :products="products" />
        </v-card-text>
    </v-card>
</template>

<script>
  import ProductList from './ProductList';

  export default {
    name: "Products",

    components: {
      ProductList
    },

    props: {
      'products': Array
    }
  }
</script>