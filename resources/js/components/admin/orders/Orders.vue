<template>
    <div style="height: 100%">
        <v-card>
            <order-toolbar />

            <v-card-text>
                <order-list />
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
  import OrderToolbar from './OrderToolbar';
  import OrderList from './OrderList';

  export default {
    name: "Orders",

    components: {
      OrderToolbar,
      OrderList
    }
  }
</script>