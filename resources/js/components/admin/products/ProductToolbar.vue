<template>
    <v-toolbar>
        <v-toolbar-title>
            {{ $t('products.products')}}
        </v-toolbar-title>

        <v-divider class="mx-4" vertical inset></v-divider>

        <v-spacer />

        <product-toolbar-search />

        <v-spacer></v-spacer>

        <product-modal-create-form />
    </v-toolbar>
</template>

<script>
  import ProductModalCreateForm from './ProductModalCreateForm';
  import ProductToolbarSearch from './ProductToolbarSearch';

  export default {
    name: "ProductToolbar",

    components: {
      ProductModalCreateForm,
      ProductToolbarSearch
    }
  }
</script>