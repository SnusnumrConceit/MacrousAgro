<template>
    <v-btn color="success"
           outlined
           @click="exportOrders">
        {{ $t('buttons.export') }}
    </v-btn>
</template>

<script>
  export default {
    name: "OrderExport",

    methods: {
      /**
       * Экспорт заказов
       */
      exportOrders() {
        axios({
          url: `/orders/export`,
          method: 'POST',
          responseType: 'blob', // important
        }).then((response) => {
          console.log(response.data);
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'orders.xlsx');
          document.body.appendChild(link);
          link.click();
        }).catch(e => {
          this.showNotification({ type: 'error', message: e.data.message});
        });
      }
    }
  }
</script>