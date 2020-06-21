<template>
    <v-slide-group class="pa-4">
        <v-slide-item v-for="product in products" :key="product.id">
            <v-card
                    class="mx-3"
                    max-width="400"
                    style="cursor: pointer"
                    @click="$router.push(`/products/${product.id}`)"
            >
                <v-img :src="product.src || 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'"
                       class="white--text align-end"
                       height="200px"
                >
                    <v-card-title>
                        {{ product.title }}
                    </v-card-title>
                </v-img>
            </v-card>
        </v-slide-item>
    </v-slide-group>
</template>

<script>
  export default {
    name: "ProductList",

    props: {
      'products': Array
    }
  }
</script>