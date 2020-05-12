<template>
    <div>
        <div>
            <v-card>
                <product-toolbar />

                <v-card-text>
                    <product-list />
                </v-card-text>
            </v-card>
        </div>
    </div>
</template>

<script>
  import ProductList from './ProductList';
  import ProductToolbar from './ProductToolbar';

  export default {
    name: "products",

    components: {
      ProductList,
      ProductToolbar
    }
  }
</script>