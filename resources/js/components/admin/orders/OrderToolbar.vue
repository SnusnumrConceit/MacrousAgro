<template>
    <v-toolbar>
        <v-toolbar-title>
            {{ $t('orders.orders')}}
        </v-toolbar-title>

        <v-divider class="mx-4" vertical inset></v-divider>

        <v-spacer />

        <order-toolbar-search />

        <v-spacer />

        <order-export />

    </v-toolbar>
</template>

<script>
  import OrderToolbarSearch from './OrderToolbarSearch';
  import OrderExport from './OrderExport';

  export default {
    name: "OrderToolbar",

    components: {
      OrderToolbarSearch,
      OrderExport
    }
  }
</script>