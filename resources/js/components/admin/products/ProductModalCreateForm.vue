<template>
    <v-dialog v-model="show" max-width="500px" persistent>
        <template v-slot:activator="{on}">
            <v-btn color="success"
                   outlined
                   v-on="on">
                <i class="pe-7s-plus"></i>
                {{ $t('buttons.add')}}
            </v-btn>
        </template>

        <product-form @hide-modal="hide"/>
    </v-dialog>
</template>

<script>
  import ProductForm from './ProductForm';

  export default {
    name: "ProductModalCreateForm",

    components: {
      ProductForm
    },

    data() {
      return {
        show: false
      }
    },

    methods: {
      hide() {
        this.show = false;
      }
    }
  }
</script>